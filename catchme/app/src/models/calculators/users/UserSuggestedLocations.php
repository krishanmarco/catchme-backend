<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use cache\Cacheable;
use cache\CacheableConstants;
use cache\CacheableHelper;
use Map\LocationAddressTableMap;
use Map\LocationTableMap;
use Map\UserLocationFavoriteTableMap;
use Models\Queries\User\UserQueriesWrapper;
use Propel\Runtime\ActiveQuery\Criteria;
use SFCriteria;
use Models\UserSuggestedLocationsResult;
use User as DbUser;
use LocationQuery;
use LocationAddressQuery;
use UserLocationFavoriteQuery;
use LatLng;
use WeightedCalculator\WeightedGroupCalculator;
use WeightedCalculator\WeightedUnit;
use Models\Calculators\Helpers\UserSuggestedLocationsCalc;
use Models\Calculators\Helpers\LocIdCoord;

class UserSuggestedLocations extends Cacheable {
    const CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS = 15;
    const CONFIG_POSITION_SEARCH_AREA_KM = 30;

    public function __construct(DbUser $user, $seed, LatLng $userLatLng) {
        parent::__construct(
            CacheableConstants::CACHE_TABLE_USER_SUGGESTED_LOCATION,
            $user->getId(),
            function () { return $this->getSuggestedLocationIds(); }
        );

        $this->user = $user;
        $this->seed = intval($seed);
        $this->userLatLng = $userLatLng;
    }

    /** @var DbUser */
    private $user;

    /** @var int */
    private $seed = 0;

    /** @var LatLng */
    private $userLatLng;

    /** @var UserSuggestedLocationsResult */
    private $result;

    /** @return UserSuggestedLocationsResult */
    public function getResult() {
        return $this->result;
    }

    public function suggestLocations() {
        // Start from (seed * self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS)
        // and add self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS number
        // of ids to the $suggestedIdsSubset array, if you reach the end
        // start back at the bottom (%)
        $suggestedIdsSubset = [];
        $startIdx = $this->seed * self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS;

        $orderedUniqueLocationIds = $this->getCachedData();
        for ($i = 0; $i < self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS; $i++) {
            $realIndex = ($startIdx + $i) % sizeof($orderedUniqueLocationIds);
            array_push($suggestedIdsSubset, $orderedUniqueLocationIds[$realIndex]);
        }

        // Map the $suggestedIdsSubset to locations ordering by $suggestedIdsSubset,
        // we need to maintain the original order because it is ranked
        $criteria = new SFCriteria();
        $criteria->addOrderByField(LocationTableMap::COL_ID, $orderedUniqueLocationIds);

        $suggestedLocations = LocationQuery::create(null, $criteria)
            ->filterByVerified(true)
            ->findPks($suggestedIdsSubset)
            ->getData();

        $this->result = new UserSuggestedLocationsResult($suggestedLocations);
    }


    private function getSuggestedLocationIds() {
        // Get the locations accumulated from favorites
        // Calculate the center coordinates of the biggest cluster
        // and get the distance from $userLatLng
        $uslcFavorites = $this->getLocationsAccumulatedFromFavorites();
        $uslcFavorites->setWeight(1 / $uslcFavorites->getCenterOfBiggestClusterDistanceFrom($this->userLatLng));

        // Get the locations accumulated from friends
        // Calculate the center coordinates of the biggest cluster
        // and get the distance from $userLatLng
        $uslcFriends = $this->getLocationsAccumulatedFromFriends();
        $uslcFriends->setWeight(1 / $uslcFriends->getCenterOfBiggestClusterDistanceFrom($this->userLatLng));

        // The $locPosWeight is based on if the positioning data is precise or not
        $uslcPosition = $this->getLocationsAccumulatedFromPosition(self::CONFIG_POSITION_SEARCH_AREA_KM);
        $uslcPosition->setWeight($this->userLatLng->isPrecise ? 0.8 : 0.2);

        $wgc = new WeightedGroupCalculator([
            $uslcFavorites->getWeightCalculator(),
            $uslcFriends->getWeightCalculator(),
            $uslcPosition->getWeightCalculator(),
        ]);

        // Extract data (locationId) from the weighted units
        return array_map(
            function (WeightedUnit $wu) { return $wu->data; },
            $wgc->calculateUniqueAccumulatedSimple()
        );
    }

    /**
     * Gets locations close to this user where the sorting criteria
     * Is the distance from this user, the search area is a class constant
     * @param $weight float: Weight of this parameter
     * @return UserSuggestedLocationsCalc
     */
    private function getLocationsAccumulatedFromPosition($areaKm) {
        $userPos = [$this->userLatLng->lat, $this->userLatLng->lng];
        $areaDeg = kmToDeg($areaKm) / 2;

        return new UserSuggestedLocationsCalc(

        // Define the function that gets locations
            function () use ($areaKm, $userPos, $areaDeg) {

                // Get all location ids in the users area
                $locations = LocationAddressQuery::create()
                    ->select([
                        LocationAddressTableMap::COL_LOCATION_ID,
                        LocationAddressTableMap::COL_LAT,
                        LocationAddressTableMap::COL_LNG
                    ])
                    ->filterByLat(['min' => degToKm($userPos[0]) - $areaDeg, 'max' => degToKm($userPos[0]) + $areaDeg])
                    ->filterByLng(['min' => degToKm($userPos[1]) - $areaDeg, 'max' => degToKm($userPos[1]) + $areaDeg])
                    ->find()
                    ->getData();

                return $this->mapLocPosQueryToLocIdCoords($locations);
            },


            // Define function to map a LocIdCoord to WeightedUnit
            // Build WeightUnits[] with weights based on $areaDeg - distance (Closer locations -> higher weight)
            function (LocIdCoord $locIdCoord) use ($userPos, $areaDeg) {
                $dist = LatLng::getDist([$locIdCoord->lat, $locIdCoord->lng], $userPos);
                return new WeightedUnit($locIdCoord->lid, $areaDeg - $dist);
            }

        );
    }

    /**
     * Gets locations of this users friends where the sorting criteria
     * Is the number of times a location appears in the list
     * Eg. if two friends have as favorite, location X and only one friend
     * has as favorite location Y then X > Y.
     * @param $weight float: Weight of this parameter
     * @return UserSuggestedLocationsCalc
     */
    private function getLocationsAccumulatedFromFriends() {
        return new UserSuggestedLocationsCalc(

        // Define function to get locations
            function () {
                // Select all this users confirmed friend ids
                $friendIds = UserQueriesWrapper::getUsersFriendIds([$this->user->getId()]);

                // Select all the location ids the users friends are subscribed to
                // Use the location count as a ranking parameter
                $locations = UserLocationFavoriteQuery::create()
                    ->select([
                        UserLocationFavoriteTableMap::COL_LOCATION_ID,
                        LocationAddressTableMap::COL_LAT,
                        LocationAddressTableMap::COL_LNG,
                        'COUNT(*)'
                    ])
                    ->joinWith(LocationAddressTableMap::COL_LOCATION_ID)
                    ->filterByUserId($friendIds, Criteria::IN)
                    ->groupByLocationId()
                    ->find()
                    ->getData();

                return $this->mapLocPosQueryToLocIdCoords($locations);
            },

            // Define function to map a LocIdCoord to WeightedUnit
            function (LocIdCoord $locIdCoord) {
                return new WeightedUnit($locIdCoord->lid, $locIdCoord->count);
            }

        );
    }

    /**
     * Gets locations close to the other favorites of this user where
     * the sorting criteria si based on the size of the cluster in which
     * that location is.
     * Eg: If the user has 3 favorites [1, 2, 3] and they are clusterized by
     * position as [[1, 2], [3]], then (weight(1) == weight(2)) > weight(3)
     * @param $weight float: Weight of this parameter
     * @return UserSuggestedLocationsCalc
     */
    private function getLocationsAccumulatedFromFavorites() {
        return new UserSuggestedLocationsCalc(

        // Define function to get locations
            function () {
                $locations = UserLocationFavoriteQuery::create()
                    ->select([
                        UserLocationFavoriteTableMap::COL_LOCATION_ID,
                        LocationAddressTableMap::COL_LAT,
                        LocationAddressTableMap::COL_LNG
                    ])
                    ->joinWith(LocationAddressTableMap::COL_LOCATION_ID)
                    ->filterByUserId($this->user->getId())
                    ->find()
                    ->getData();

                return $this->mapLocPosQueryToLocIdCoords($locations);
            },


            // Define function to map a LocIdCoord to WeightedUnit
            function (LocIdCoord $locIdCoord) {
                return new WeightedUnit($locIdCoord->lid, $locIdCoord->clusterData->size);
            }

        );
    }

    /**
     * @param array $locationsQueryResult
     * @return LocIdCoord[]
     */
    private function mapLocPosQueryToLocIdCoords(array $locationsQueryResult) {
        return array_map(function ($locData) {
            $lid = $locData[UserLocationFavoriteTableMap::COL_LOCATION_ID];
            $lat = $locData[LocationAddressTableMap::COL_LAT];
            $lng = $locData[LocationAddressTableMap::COL_LNG];
            $count = $locData['COUNT(*)'];
            return new LocIdCoord($lid, $lat, $lng, $count);
        }, $locationsQueryResult);
    }

}
