<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use Map\LocationTableMap;
use Models\Queries\User\UserQueriesWrapper;
use SFCriteria;
use Models\UserSuggestedLocationsResult;
use User as DbUser;
use LocationQuery;
use DbLatLng;
use WeightedCalculator\WeightedGroupCalculator;
use WeightedCalculator\IWeightCalculator;
use WeightedCalculator\WeightedUnit;
use WeightedCalculator\WeightCalculator;

class UserSuggestedLocations {
    const CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS = 15;

    public function __construct(DbUser $user, $seed, DbLatLng $userLatLng) {
        $this->user = $user;
        $this->seed = intval($seed);
        $this->userLatLng = $userLatLng;
    }

    /** @var DbUser */
    private $user;

    /** @var int */
    private $seed = 0;

    /** @var DbLatLng */
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
        // Calculate the center coordinates (Cluster by cluster)
        // and get the distance from $userLatLng
        $favWeightedUnits = $this->getLocationsAccumulatedFromFavorites();
        $favCenterToUserDist = 0;
        $locFavWeight = 0;

        // Get the locations accumulated from friends
        // Calculate the center coordinates (Cluster by cluster)
        // and get the distance from $userLatLng
        $friWeightedUnits = $this->getLocationsAccumulatedFromFriends();
        $friCenterToUserDist = 0;
        $locFriWeight = 0;

        // The $locPosWeight is based on if the positioning data is precise or not
        $posWeightedUnits = $this->getLocationsAccumulatedFromPosition();
        $locPosWeight = $this->userLatLng->isPrecise ? 0.8 : 0.2;

        $wgc = new WeightedGroupCalculator([
            new WeightCalculator($locPosWeight, function() use ($posWeightedUnits) { return $posWeightedUnits; }),
            new WeightCalculator($locFriWeight, function() use ($friWeightedUnits) { return $friWeightedUnits; }),
            new WeightCalculator($locFavWeight, function() use ($favWeightedUnits) { return $favWeightedUnits; })
        ]);

        // Calculate the unique locations
        $orderedUniqueWeightedUnits = $wgc->calculateUniqueAccumulatedSimple();

        // Map each WeightedUnit to its location id
        return array_map(
            function(WeightedUnit $wu) { return $wu->data; },
            $orderedUniqueWeightedUnits
        );
    }

    /**
     * Gets locations close to this user where the sorting criteria
     * Is the distance from this user, the search area is a class constant
     * @param $weight float: Weight of this parameter
     * @return WeightedUnit[]
     */
    private function getLocationsAccumulatedFromPosition() {
        // todo

        // Get all locations

        // Sort by distance from user DESC

        // Build WeightUnits[] with weights based on index

        return [];
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
     * @return WeightedUnit[]
     */
    private function getLocationsAccumulatedFromFavorites() {
        // todo:

        // Get this users favorites

        // Clusterize and order clusters by size DESC

        // Build WeightUnits[] with weights based on cluster index
        return [];
    }


}
