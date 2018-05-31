<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use Map\UserTableMap;
use Models\Queries\User\UserQueriesWrapper;
use SFCriteria;
use User as DbUser;
use UserQuery;
use Models\UserSuggestedFriendsResult;

class UserSuggestedFriends {
    const CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS = 15;

    public function __construct(DbUser $user, $seed) {
        $this->user = $user;
        $this->seed = intval($seed);
        $this->calculateSuggestedFriends();
    }

    /** @var DbUser */
    private $user;

    /** @var int */
    private $seed = 0;

    /** @var UserSuggestedFriendsResult */
    private $result;

    /** @return UserSuggestedFriendsResult */
    public function getResult() {
        return $this->result;
    }

    private function calculateSuggestedFriends() {

        // Select all this users confirmed friends
        // only the ids are needed
        $friendIds = UserQueriesWrapper::getUsersFriendIds([$this->user->getId()]);

        // Get the friends of the selected friends
        // Note, the array returned by array_unique is ASSOCIATIVE
        $suggestedIds = array_unique(UserQueriesWrapper::getUsersFriendIds($friendIds));

        // Remove the current user from the selected user ids
        // Note, the array returned by array_search is ASSOCIATIVE
        if (($key = array_search($this->user->getId(), $suggestedIds)) !== false)
            unset($suggestedIds[$key]);

        $suggestedIds = array_values($suggestedIds);


        // If there are no suggested connections return an empty list
        if (sizeof($suggestedIds) <= 0) {
            $this->result = new UserSuggestedFriendsResult([]);
            return;
        }

        // Start from (seed * self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS)
        // and add self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS number
        // of ids to the $suggestedIdsSubset array, if you reach the end
        // start back at the bottom (%)
        $suggestedIdsSubset = [];
        $startIdx = $this->seed * self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS;

        for ($i = 0; $i < self::CONFIG_TOTAL_NUMBER_OF_SUGGESTIONS; $i++) {
            $realIndex = ($startIdx + $i) % sizeof($suggestedIds);
            array_push($suggestedIdsSubset, $suggestedIds[$realIndex]);
        }


        // We cannot be sure that the same index from $suggestedIds
        // was not selected more than once
        $suggestedIdsSubset = array_values(array_unique($suggestedIdsSubset));


        // Map the $suggestedIdsSubset to users ordering by $suggestedIdsSubset,
        // we need to maintain the original order because it is ranked
        $criteria = new SFCriteria();
        $criteria->addOrderByField(UserTableMap::COL_ID, $suggestedIdsSubset);

        $suggestedUsers = UserQuery::create(null, $criteria)
            ->orderById()
            ->findPks($suggestedIdsSubset)
            ->getData();


        $this->result = new UserSuggestedFriendsResult($suggestedUsers);
    }

}
