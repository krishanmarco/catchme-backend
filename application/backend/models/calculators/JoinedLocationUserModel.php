<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */


namespace Models\Calculators;

use Models\Calculators\JoinedLocationUser\LocationFriends;
use Models\Calculators\JoinedLocationUser\LocationFriendsResult;


class JoinedLocationUserModel {

    public function __construct(LocationModel $locationModel, UserModel $userModel) {
        $this->locationModel = $locationModel;
        $this->userModel = $userModel;
    }


    /** @var LocationModel $locationModel ; */
    private $locationModel;
    public function getLocationModel() { return $this->locationModel; }


    /** @var UserModel $userModel */
    private $userModel;
    public function getUserModel() { return $this->userModel; }



    /** @var LocationFriendsResult $locationFriendsResult */
    private $locationFriendsResult;

    public function getLocationFriendsResult() {
        if (is_null($this->locationFriendsResult)) {
            $locationFriends = new LocationFriends($this);
            $this->locationFriendsResult = $locationFriends->execute();
        }

        return $this->locationFriendsResult;
    }




}