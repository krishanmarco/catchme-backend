<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\JoinedLocationUser;

use Models\LocationFriendsResult;
use Models\Calculators\LocationModel;
use Models\Calculators\UserModel;
use User as DbUser;
use Location as DbLocation;

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

    /** @var LocationFriendsResult */
    private $result;

    /** @return LocationFriendsResult */
    public function getResult() {
        return $this->result;
    }

    private function calculateLocationFriends() {

        // Get all the users in the current location
        $locationUsersResult = LocationModel::fromLocation($this->location)
            ->getLocationUsers()->getResult();

        // Get all the current users friends
        $userFriends = UserModel::fromUser($this->user)
            ->getUserConnections()->getResult()->friends;


        // Find the intersection between $userFriends and the locations now users
        $nowFriends = [];
        foreach ($locationUsersResult->usersNow as $userNow)
            foreach ($userFriends as $userFriend)
                if ($userNow->getUserId() == $userFriend->getId())
                    array_push($nowFriends, $userFriend);

        // Find the intersection between $userFriends and the locations future users
        $futureFriends = [];
        foreach ($locationUsersResult->usersFuture as $userFuture)
            foreach ($userFriends as $userFriend)
                if ($userFuture->getUserId() == $userFriend->getId())
                    array_push($futureFriends, $userFriend);

        $this->result = new LocationFriendsResult($nowFriends, $futureFriends);
    }

}
