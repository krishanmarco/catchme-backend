<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use Map\LocationTableMap;

use Models\Queries\User\UserQueriesWrapper;
use SFCriteria;
use Models\Calculators\UserModel;

use Location;
use LocationQuery;





class UserSuggestedLocations {
    const CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS = 15;




    public function __construct(UserModel $userModel, $seed) {
        $this->userModel = $userModel;
        $this->seed = intval($seed);
    }


    /** @var UserModel $userModel */
    private $userModel;
    public function getUser() { return $this->userModel->getUser(); }


    /** @var int $seed */
    private $seed = 0;




    /** @return UserSuggestedLocationsResult */
    public function execute() {

        // Select all this users location ids, count
        // will always be 1 so do not use orderByCount
        $favoriteLocIds = UserQueriesWrapper::getUsersLocationIds([$this->getUser()->getId()]);

        // Select all this users confirmed friends, only the ids are needed
        $friendIds = UserQueriesWrapper::getUsersFriendIds([$this->getUser()->getId()]);

        // Select all the location ids the users friends are subscribed to
        // We use user count as a ranking parameter, set $orderByCount to true
        $suggestedLocIds = UserQueriesWrapper::getUsersLocationIds($friendIds);

        // Delete all the favoriteLocIds from the suggestedLocIds
        // We do not want to suggest locations that the user already has
        $suggestedLocIds = array_values(array_diff($suggestedLocIds, $favoriteLocIds));

        // If there are no suggested locations return a list of locations closest to this user
        if (sizeof($suggestedLocIds) <= 0) {
            return $this->getLocationsByPosition();
        }

        // Start from (seed * self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS)
        // and add self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS number
        // of ids to the $suggestedIdsSubset array, if you reach the end
        // start back at the bottom (%)
        $suggestedIdsSubset = [];
        $startIdx = $this->seed * self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS;

        for ($i = 0; $i < self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS; $i++) {
            $realIndex = ($startIdx + $i) % sizeof($suggestedLocIds);
            array_push($suggestedIdsSubset, $suggestedLocIds[$realIndex]);
        }

        // We cannot be sure that the same index from $suggestedLocIds
        // was not selected more than once
        $suggestedIdsSubset = array_values(array_unique($suggestedIdsSubset));

        // Map the $suggestedIdsSubset to locations ordering by $suggestedIdsSubset,
        // we need to maintain the original order because it is ranked
        $criteria = new SFCriteria();
        $criteria->addOrderByField(LocationTableMap::COL_ID, $suggestedIdsSubset);

        $suggestedLocations = LocationQuery::create(null, $criteria)
            ->filterByVerified(true)
            ->orderById()
            ->findPks($suggestedIdsSubset)
            ->getData();


        return new UserSuggestedLocationsResult($suggestedLocations);
    }

    /** @return UserSuggestedLocationsResult */
    private function getLocationsByPosition($maxLocations = SUGGEST_LOCATIONS_MAX_RANDOM) {
        // todo:
        // Use town most common town in the users other favorite locations
        // If the result is not as long as $maxLocations merge with random
        // locations positioned near the requests ip address

        return new UserSuggestedLocationsResult([]);
    }


}


class UserSuggestedLocationsResult {

    /**
     * LocationSuggestedResult constructor.
     * @param Location[] $suggestedLocations
     */
    public function __construct(array $suggestedLocations) {
        $this->suggestedLocations = $suggestedLocations;
    }


    /** @var Location[] $suggestedLocations */
    private $suggestedLocations;
    public function getSuggestedLocations() { return $this->suggestedLocations; }


}