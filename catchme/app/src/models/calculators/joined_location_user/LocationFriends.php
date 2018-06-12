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

    /** @var DbUser[] */
    private $friendsNow = [];

    /** @var DbUser[] */
    private $friendsLater = [];

    /** @return DbUser[] */
    public function getFriendsNow() {
        return $this->friendsNow;
    }

    /** @return DbUser[] */
    public function getFriendsLater() {
        return $this->friendsLater;
    }

    private function calculateLocationFriends() {

        // Get all the current users friends
        $userFriends = UserModel::fromUser($this->user)
            ->getUserConnections()->getUserFriends();

        // Get all the users in the current location
        $locationUsers = LocationModel::fromLocation($this->location)
            ->getLocationUsers();

        // Get all the users in the current location (now)
        $locationUsersNow = $locationUsers->getUsersNow();

        // Get all the users in the current location (later)
        $locationUsersLater = $locationUsers->getUsersLater();

        // Find the intersection between $userFriends and the locations now users
        foreach ($locationUsersNow as $userNow)
            foreach ($userFriends as $userFriend)
                if ($userNow->getUserId() == $userFriend->getId())
                    array_push($this->friendsNow, $userFriend);

        // Find the intersection between $userFriends and the locations future users
        foreach ($locationUsersLater as $userFuture)
            foreach ($userFriends as $userFriend)
                if ($userFuture->getUserId() == $userFriend->getId())
                    array_push($this->friendsLater, $userFriend);
    }

}
