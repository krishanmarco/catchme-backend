<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use KMeans\Cluster;
use KMeans\Clusterizer;
use KMeans\ClusterPoint;
use Map\LocationAddressTableMap;
use Map\LocationTableMap;
use Map\UserLocationFavoriteTableMap;
use Models\LocIdCoord;
use Models\Queries\User\UserQueriesWrapper;
use SFCriteria;
use Models\UserSuggestedLocationsResult;
use Models\UserSuggestedLocationsResWrapper;
use User as DbUser;
use LocationQuery;
use LocationAddressQuery;
use UserLocationFavoriteQuery;
use LatLng;
use WeightedCalculator\WeightedGroupCalculator;
use WeightedCalculator\IWeightCalculator;
use WeightedCalculator\WeightedUnit;
use WeightedCalculator\WeightCalculator;
use Models\Calculators\Helpers\UserSuggestedLocationsCalc;

class UserSuggestedLocations {
    const CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS = 15;
    const CONFIG_POSITION_SEARCH_AREA_KM = 30;

    public function __construct(DbUser $user, $seed, LatLng $userLatLng) {
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
    private $result;// todo should be cached

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

        $orderedUniqueLocationIds = $this->calculate();
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


    private function calculate() {
        // Get the locations accumulated from favorites
        // Calculate the center coordinates of the biggest cluster
        // and get the distance from $userLatLng
        $favWeightedUnits = $this->getLocationsAccumulatedFromFavorites();
        $favCluster = new Clusterizer($this->mapWeightedUnitsToClusterPoints($favWeightedUnits));
        $centerBiggestFavCluster = $favCluster->getCenterOfBiggestCluster();
        $favCenterToUserDist = LatLng::getDist([$this->userLatLng->lat, $this->userLatLng->lng], $centerBiggestFavCluster);
        $locFavWeight = 1 / $favCenterToUserDist;

        // Get the locations accumulated from friends
        // Calculate the center coordinates of the biggest cluster
        // and get the distance from $userLatLng
        $friWeightedUnits = $this->getLocationsAccumulatedFromFriends();
        $friCluster = new Clusterizer($this->mapWeightedUnitsToClusterPoints($friWeightedUnits));
        $centerBiggestFriCluster = $friCluster->getCenterOfBiggestCluster();
        $friCenterToUserDist = LatLng::getDist([$this->userLatLng->lat, $this->userLatLng->lng], $centerBiggestFriCluster);
        $locFriWeight = 1 / $friCenterToUserDist;

        // The $locPosWeight is based on if the positioning data is precise or not
        $posWeightedUnits = $this->getLocationsAccumulatedFromPosition(self::CONFIG_POSITION_SEARCH_AREA_KM);
        $locPosWeight = $this->userLatLng->isPrecise ? 0.8 : 0.2;

        $wgc = new WeightedGroupCalculator([
            new WeightCalculator($locPosWeight, function () use ($posWeightedUnits) {
                return $posWeightedUnits;
            }),
            new WeightCalculator($locFriWeight, function () use ($friWeightedUnits) {
                return $friWeightedUnits;
            }),
            new WeightCalculator($locFavWeight, function () use ($favWeightedUnits) {
                return $favWeightedUnits;
            })
        ]);

        // Calculate the unique locations
        $orderedUniqueWeightedUnits = $wgc->calculateUniqueAccumulatedSimple();

        // Map each WeightedUnit to its location id
        return array_map(
            function (WeightedUnit $wu) {
                return $wu->data;
            },
            $orderedUniqueWeightedUnits
        );
    }

    /**
     * Gets locations close to this user where the sorting criteria
     * Is the distance from this user, the search area is a class constant
     * @param $weight float: Weight of this parameter
     * @return WeightedUnit[]
     */
    private function getLocationsAccumulatedFromPosition($areaKm) {
        $areaDeg = kmToDeg($areaKm) / 2;
        $userPos = [$this->userLatLng->lat, $this->userLatLng->lng];

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

        // Build WeightUnits[] with weights based on $areaDeg - distance (Closer locations -> higher weight)
        $result = [];
        foreach ($locations as $loc) {
            $lid = $loc[LocationAddressTableMap::COL_LOCATION_ID];
            $locPos = [$loc[LocationAddressTableMap::COL_LAT], $loc[LocationAddressTableMap::COL_LNG]];
            $weight = $areaDeg - LatLng::getDist($locPos, $userPos);
            array_push($result, new WeightedUnit($lid, $weight));
        }

        return $result;
    }

    /**
     * Gets locations of this users friends where the sorting criteria
     * Is the number of times a location appears in the list
     * Eg. if two friends have as favorite, location X and only one friend
     * has as favorite location Y then X > Y.
     * @param $weight float: Weight of this parameter
     * @return WeightedUnit[]
     */
    private function getLocationsAccumulatedFromFriends() {
        // Select all this users confirmed friend ids
        $friendIds = UserQueriesWrapper::getUsersFriendIds([$this->user->getId()]);

        // Select all the location ids the users friends are subscribed to
        // Use the location count as a ranking parameter
        return UserQueriesWrapper::getUsersLocationIdsWeightedUnits($friendIds);
    }

    /**
     * Gets locations close to the other favorites of this user where
     * the sorting criteria si based on the size of the cluster in which
     * that location is.
     * Eg: If the user has 3 favorites [1, 2, 3] and they are clusterized by
     * position as [[1, 2], [3]], then (weight(1) == weight(2)) > weight(3)
     * @param $weight float: Weight of this parameter
     * @return UserSuggestedLocationsResWrapper
     */
    private function getLocationsAccumulatedFromFavorites() {
        // Get this users favorites
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

        // Map the location id to cluster points
        $locIdCoords = $this->mapLocPosQueryToLocIdCoords($locations);
        $clusterPoints = $this->mapLocIdCoordsToClusterPoints($locIdCoords);

        $result = new UserSuggestedLocationsResWrapper();
        $result->clusterizer = new Clusterizer($clusterPoints);
        $result->weightedUnits = array_map(function (ClusterPoint $cp) {
            return new WeightedUnit($cp->data, $cp->inClusterWithSize);
        }, $result->clusterizer->getFlatPoints());

        return $result;
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
            return new LocIdCoord($lid, $lat, $lng);
        }, $locationsQueryResult);
    }

    /**
     * @param LocIdCoord[] $locIdCoords
     * @return ClusterPoint[]
     */
    private function mapLocIdCoordsToClusterPoints(array $locIdCoords) {
        return array_map(function (LocIdCoord $locIdCoord) {
            return new ClusterPoint([$locIdCoord->lat, $locIdCoord->lng], $locIdCoord);
        }, $locIdCoords);
    }

    /**
     * @param WeightedUnit[] $weightedUnits
     * @return LocIdCoord[]
     */
    private function mapWeightedUnitsToLocIdCoords(array $weightedUnits) {
        return array_map(function (WeightedUnit $weightedUnit) {
            return $weightedUnit->data;
        }, $weightedUnits);
    }

    /**
     * @param WeightedUnit[] $weightedUnits
     * @return ClusterPoint[]
     */
    private function mapWeightedUnitsToClusterPoints(array $weightedUnits) {
        return $this->mapLocIdCoordsToClusterPoints($this->mapWeightedUnitsToLocIdCoords($weightedUnits));
    }

    /**
     * @param ClusterPoint[] $clusterPoints
     * @return LocIdCoord
     */
    private function mapClusterPointsToLocIdCoords(array $clusterPoints) {
        return array_map(function (ClusterPoint $clusterPoint) {
            return $clusterPoint->data;
        }, $clusterPoints);
    }


}
