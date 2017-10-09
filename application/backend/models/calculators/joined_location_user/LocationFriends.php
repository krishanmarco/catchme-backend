<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\JoinedLocationUser;
use Models\Calculators\JoinedLocationUserModel;
use User as User;




class LocationFriends {

    public function __construct(JoinedLocationUserModel $joinedLocationUserModel) {
        $this->joinedLocationUserModel = $joinedLocationUserModel;
    }

    /** @var JoinedLocationUserModel $joinedLocationUserModel; */
    private $joinedLocationUserModel;
    public function getLocationModel() { return $this->joinedLocationUserModel->getLocationModel(); }
    public function getUserModel() { return $this->joinedLocationUserModel->getUserModel(); }




    public function execute() {

        // Get all the users in the current location
        $locationUsersResult = $this->getLocationModel()
            ->getLocationUsersResult();

        // Get all the current users friends
        $userFriends = $this->getUserModel()
            ->getUserConnectionsResult()
            ->getFriends();



        // Find the intersection between $userFriends and the locations now users
        $nowFriends = [];
        foreach ($locationUsersResult->getUsersNow() as $userNow)
            foreach ($userFriends as $userFriend)
                if ($userNow->getUserId() == $userFriend->getId())
                    array_push($nowFriends, $userFriend);

        // Find the intersection between $userFriends and the locations future users
        $futureFriends = [];
        foreach ($locationUsersResult->getUsersFuture() as $userFuture)
            foreach ($userFriends as $userFriend)
                if ($userFuture->getUserId() == $userFriend->getId())
                    array_push($futureFriends, $userFriend);

        return new LocationFriendsResult($nowFriends, $futureFriends);
    }

}




class LocationFriendsResult {

    public function __construct(array $now, array $future) {
        $this->now = $now;
        $this->future = $future;
    }


    /* @var User[] $now */
    private $now;
    public function getNow() { return $this->now; }

    /* @var User[] $future */
    private $future;
    public function getFuture() { return $this->future; }

}