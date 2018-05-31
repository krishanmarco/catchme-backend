<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use Map\LocationTableMap;
use Models\Queries\User\UserQueriesWrapper;
use SFCriteria;
use Models\UserSuggestedLocationsResult;
use User as DbUser;
use LocationQuery;

class UserSuggestedLocations {
    const CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS = 15;

    public function __construct(DbUser $user, $seed) {
        $this->user = $user;
        $this->seed = intval($seed);
        $this->calculateSuggestedLocations();
    }

    /** @var DbUser $user */
    private $user;

    /** @var int $seed */
    private $seed = 0;

    /** @var UserSuggestedLocationsResult */
    private $result;

    /** @return UserSuggestedLocationsResult */
    public function getResult() {
        return $this->result;
    }

    private function calculateSuggestedLocations() {

        // Select all this users location ids, count
        // will always be 1 so do not use orderByCount
        $favoriteLocIds = UserQueriesWrapper::getUsersLocationIds([$this->user->getId()]);

        // Select all this users confirmed friends, only the ids are needed
        $friendIds = UserQueriesWrapper::getUsersFriendIds([$this->user->getId()]);

        // Select all the location ids the users friends are subscribed to
        // We use user count as a ranking parameter, set $orderByCount to true
        $suggestedLocIds = UserQueriesWrapper::getUsersLocationIds($friendIds);

        // Delete all the favoriteLocIds from the suggestedLocIds
        // We do not want to suggest locations that the user already has
        $suggestedLocIds = array_values(array_diff($suggestedLocIds, $favoriteLocIds));

        // If there are no suggested locations return a list of locations closest to this user
        if (sizeof($suggestedLocIds) <= 0) {
            $this->getLocationsByPosition();
            return;
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


        $this->result = new UserSuggestedLocationsResult($suggestedLocations);
    }

    private function getLocationsByPosition($maxLocations = SUGGEST_LOCATIONS_MAX_RANDOM) {
        // todo:
        // Use town most common town in the users other favorite locations
        // If the result is not as long as $maxLocations merge with random
        // locations positioned near the request ip address

        $this->result = new UserSuggestedLocationsResult([]);
    }


}
