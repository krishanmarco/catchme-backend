<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Users;

use cache\Cacheable;
use cache\CacheableConstants;
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
    const CONFIG_POSITION_SEARCH_AREA_KM = 50;

    public function __construct(DbUser $user, $seed, $userLatLng = null) {
        parent::__construct(
            CacheableConstants::CACHE_TABLE_USER_SUGGESTED_LOCATION,
            $user->getId(),
            function () { return $this->getSuggestedLocationIds(); }
        );

        $this->user = $user;
        $this->seed = intval($seed);
        $this->userLatLng = $userLatLng;
        $this->suggestLocations();
    }

    /** @var DbUser */
    private $user;

    /** @var int */
    private $seed = 0;

    /** @var null|LatLng */
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
        $uslcFav = $this->getLocationsAccumulatedFromFavorites();
        $uslcFavDist = $uslcFav->distFromCenterOfBiggestCluster($this->userLatLng);
        $uslcFav->setWeight(1 / $uslcFavDist);

        // Get the locations accumulated from friends
        // Calculate the center coordinates of the biggest cluster
        // and get the distance from $userLatLng
        $uslcFri = $this->getLocationsAccumulatedFromFriends();
        $uslcFriDist = $uslcFri->distFromCenterOfBiggestCluster($this->userLatLng);
        $uslcFri->setWeight(1 / $uslcFriDist);

        // The $locPosWeight is based on if the positioning data is precise or not
        $uslcPos = $this->getLocationsAccumulatedFromPosition(self::CONFIG_POSITION_SEARCH_AREA_KM);
        $uslcPos->setWeight(((int)!is_null($this->userLatLng)) * $uslcFavDist * $uslcFavDist);

        $wgc = new WeightedGroupCalculator([
            $uslcFav->getWeightCalculator(),
            $uslcFri->getWeightCalculator(),
            $uslcPos->getWeightCalculator(),
        ]);

        // Extract data (locationId) from the weighted units
        return array_map(
            function (WeightedUnit $wu) { return $wu->data; },
            $wgc->calculateUniqueAccumulatedSimple()
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
                    ->joinWithLocationAddress()
                    ->filterByUserId($this->user->getId())
                    ->find()
                    ->getData();

                return $this->mapLocPosQueryToLocIdCoords($locations, UserLocationFavoriteTableMap::COL_LOCATION_ID);
            },


            // Define function to map a LocIdCoord to WeightedUnit
            function (LocIdCoord $locIdCoord) {
                return new WeightedUnit($locIdCoord->lid, $locIdCoord->clusterData->size);
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
                    ])
                    ->joinWithLocationAddress()
                    ->filterByUserId($friendIds, Criteria::IN)
                    ->find()
                    ->getData();

                return $this->mapLocPosQueryToLocIdCoords($locations, UserLocationFavoriteTableMap::COL_LOCATION_ID);
            },

            // Define function to map a LocIdCoord to WeightedUnit
            function (LocIdCoord $locIdCoord) {
                return new WeightedUnit($locIdCoord->lid, $locIdCoord->clusterData->size);
            }

        );
    }

    /**
     * Gets locations close to this user where the sorting criteria
     * Is the distance from this user, the search area is a class constant
     * @param $weight float: Weight of this parameter
     * @return UserSuggestedLocationsCalc
     */
    private function getLocationsAccumulatedFromPosition($areaKm) {
        $userPosDeg = [$this->userLatLng->lat, $this->userLatLng->lng];
        $areaDeg = kmToDeg($areaKm) / 2;

        return new UserSuggestedLocationsCalc(

        // Define the function that gets locations
            function () use ($areaKm, $userPosDeg, $areaDeg) {

                // Get all location ids in the users area
                $locations = LocationAddressQuery::create()
                    ->select([
                        LocationAddressTableMap::COL_LOCATION_ID,
                        LocationAddressTableMap::COL_LAT,
                        LocationAddressTableMap::COL_LNG
                    ])
                    ->filterByLat(['min' => $userPosDeg[0] - $areaDeg, 'max' => $userPosDeg[0] + $areaDeg])
                    ->filterByLng(['min' => $userPosDeg[1] - $areaDeg, 'max' => $userPosDeg[1] + $areaDeg])
                    ->find()
                    ->getData();

                return $this->mapLocPosQueryToLocIdCoords($locations, LocationAddressTableMap::COL_LOCATION_ID);
            },


            // Define function to map a LocIdCoord to WeightedUnit
            // Build WeightUnits[] with weights based on $areaDeg - distance (Closer locations -> higher weight)
            function (LocIdCoord $locIdCoord) use ($userPosDeg, $areaDeg) {
                // Weight = How far is this location from the outer bound
                // Farther the location is from the outer bound the closer it is to the user
                // Hence the higher the weight
                $weight = LatLng::distToWeight1([$locIdCoord->lat, $locIdCoord->lng], $userPosDeg);
                return new WeightedUnit($locIdCoord->lid, $weight);
            }

        );
    }

    /**
     * @param array $locationsQueryResult
     * @return LocIdCoord[]
     */
    private function mapLocPosQueryToLocIdCoords(array $locationsQueryResult, $colId) {
        return array_map(function ($locData) use ($colId) {
            $lid = intval($locData[$colId]);
            $lat = doubleval($locData[LocationAddressTableMap::COL_LAT]);
            $lng = doubleval($locData[LocationAddressTableMap::COL_LNG]);
            return new LocIdCoord($lid, $lat, $lng);
        }, $locationsQueryResult);
    }

}
