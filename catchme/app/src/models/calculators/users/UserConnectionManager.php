<?php /** Created by Krishan Marco Madan on 15-May-18. */

namespace Models\Calculators\Users;

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

    public function __construct(DbUser $user) {
        $this->user = $user;
        $this->calculateConnectionStrengths();
    }

    /** @var DbUser $user */
    private $user;

    /** @var array(User => float) $connStrs */
    private $connStrs = [];

    /** @return array(User => float) $connectionStrength */
    public function getConnectionStrengths() {
        return $this->connStrs;
    }

    private function calculateConnectionStrengths() {

        // Given the current user, get all his friends ids
        $usersFriendIds = UserQueriesWrapper::getUsersFriendIds([$this->user->getId()]);

        // Foreach friend, get that friends friends
        // array(friendId => [friendsFriendId, friendsFriendsId, ...])
        $friendsFriends = UserQueriesWrapper::getUsersFriendsIdsGroupedByUserIdUnique($usersFriendIds);

        // Build the connection array
        foreach ($friendsFriends as $friendId => $friendIdsFriends) {
            // Sum starts at 1 because $friendId and this user have each-other in common
            $sum = 1;
            foreach ($usersFriendIds as $id) {
                if (in_array($id, $friendIdsFriends))
                    $sum += 1;          // This friends friend is in common
                else
                    $sum -= 1;          // This friends friend is not in common
            }

            $this->connStrs[$friendId] = $sum / sizeof($usersFriendIds);
        }

        // Order the $connectionStrength array by strength
        arsort($this->connStrs);
    }

}