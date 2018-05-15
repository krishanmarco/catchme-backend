<?php /** Created by Krishan Marco Madan on 15-May-18. */

namespace Models\Calculators\Users;
use Models\Calculators\UserModel;
use User as DbUser;
use Models\Queries\User\UserQueriesWrapper;

/**
 * This class handles all calculations that involve the relationship between users
 * It always has one constant reference point (the current user) and based on that
 * it calculates a variable (connectionStrength) that indicates the strength
 * of the connection between the current user and another (generic) user.
 *
 * $connectionStrength[x => 1]: means that this user is friends with all of {x}s friends
 *                              and all of {x}s friends are friends with this user (all friends in common).
 *
 * $connectionStrength[x => -1]: means that this user and {x} have no friends in common.
 *                              the item x => 0 would not even be in the $connectionStrength
 *                              array to begin with but this is the extreme case.
 *
 * Class UserConnectionManager
 * @package Models\Calculators\Users
 */
class UserConnectionManager {

    public function constructor(DbUser $user) {
        $this->userModel = UserModel::fromUser($user);
    }

    /** @var UserModel $userModel */
    private $userModel;

    private function getUser() {
        return $this->userModel->getUser();
    }

    /** @var array(User => float) $connectionStrength */
    private $connectionStrengths = [];


    private function calculateConnections() {

        // Given the current user, get all his friends ids
        $usersFriendIds = UserQueriesWrapper::getUsersFriendIds([$this->getUser()->getId()]);

        // Foreach friend, get that friends friends
        // array(friendId => [friendsFriendId, friendsFriendsId, ...])
        $friendsFriends = UserQueriesWrapper::getUsersFriendsIdsGroupedByUserId($usersFriendIds);

        // Build the connection array
        foreach ($friendsFriends as $friendId => $friendsFriendsIds) {
            // Sum starts at 2 because $friendId and this user have each-other in common
            // but $friendId will not be in $friendsFriendsIds value so whe $id == $friendId a -1 will apply
            $sum = 2;
            foreach ($usersFriendIds as $id) {
                if (in_array($id, $friendsFriendsIds))
                    $sum += 1;          // This friends friend is in common
                else
                    $sum -= 1;          // This friends friend is not in common
            }

            $this->connectionStrengths[$friendId] = $sum / sizeof($usersFriendIds);
        }

        // Order the $connectionStrength array by strength
        $this->connectionStrengths = asort($this->connectionStrengths);
        return $this->connectionStrengths;
    }

}