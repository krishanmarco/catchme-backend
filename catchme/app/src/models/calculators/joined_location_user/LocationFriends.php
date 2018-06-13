<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\JoinedLocationUser;

use Location as DbLocation;
use Models\Calculators\LocationModel;
use Models\Calculators\UserModel;
use User as DbUser;

class LocationFriends {

    public function __construct(DbLocation $location, DbUser $user) {
        $this->location = $location;
        $this->user = $user;
        $this->calculateLocationFriends();
    }

    /** @var DbLocation */
    private $location;

    /** @var DbUser */
    private $user;

    /** @var DbUser[] (int => DbUser) */
    private $accUsers = [];

    /** @var int[] */
    private $friendsNowIds = [];

    /** @var int[] */
    private $friendsLaterIds = [];

    /** @return int[] */
    public function getFriendsNowIds() {
        return $this->friendsNowIds;
    }

    /** @return int[] */
    public function getFriendsLaterIds() {
        return $this->friendsLaterIds;
    }

    /** @return DbUser[] (int => DbUser) */
    public function getAccDbUsers() {
        return $this->accUsers;
    }

    private function calculateLocationFriends() {

        // Get all the current users friends
        $userFriendIds = UserModel::fromUser($this->user)
            ->getUserConnections()
            ->getUserFriendIds();

        // Get the LocationUsers calculator from the LocationModel
        $locationUsers = LocationModel::fromLocation($this->location)
            ->getLocationUsers();

        // Get all the users in the current location (now)
        $userNowIds = $locationUsers->getUsersNowIds();

        // Get all the users in the current location (later)
        $userLaterIds = $locationUsers->getUserLaterIds();

        // Get all the users associated to the ids in $userNowIds and $userLaterIds
        $accUsers = $locationUsers->getAccDbUsers();

        // Find the intersection between $userFriends and the locations now users
        foreach ($userNowIds as $userNowId)
            foreach ($userFriendIds as $userFriendId)
                if ($userNowId == $userFriendId) {
                    $this->friendsNowIds[] = $userFriendId;
                    $this->accUsers[$userFriendId] = $accUsers[$userFriendId];
                }

        // Find the intersection between $userFriends and the locations future users
        foreach ($userLaterIds as $userLaterId)
            foreach ($userFriendIds as $userFriendId)
                if ($userLaterId == $userFriendId) {
                    $this->friendsLaterIds[] = $userFriendId;
                    $this->accUsers[$userFriendId] = $accUsers[$userFriendId];
                }
    }

}
