<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;
use Api\Map\ModelToApiLocations;
use Models\Calculators\LocationModel;
use Models\Calculators\UserModel;
use Models\Calculators\JoinedLocationUserModel;
use User as DbUser;
use Api\Location as ApiLocation;


class ControllerLocations {
    
    public function __construct(DbUser $authenticatedUser, $locationId) {
        $this->authenticatedUser = $authenticatedUser;
        $this->locationId = $locationId;
        $this->locationModel = LocationModel::fromId($locationId);
    }


    /** @var DbUser $authenticatedUser */
    private $authenticatedUser;

    /** @var int $locationId */
    private $locationId;



    /** @var LocationModel $locationModel */
    private $locationModel;

    private function getLocation() {
        return $this->locationModel->getLocation();
    }





    /** @return ApiLocation */
    public function get() {
        return ModelToApiLocations::single($this->getLocation())->get();
    }




    /** @return ApiLocation */
    public function getProfile() {

        $joinedLocationUserModel = new JoinedLocationUserModel(
            $this->locationModel,
            UserModel::fromUser($this->authenticatedUser)
        );

        return ModelToApiLocations::single($this->getLocation())
            ->applyPrivacyPolicy($this->authenticatedUser)
            ->withAddress()
            ->withImages($this->locationModel->getLocationImagesResult())
            ->withPeople($this->locationModel->getLocationCountResult())
            ->withConnections($joinedLocationUserModel->getLocationFriendsResult())
            ->get();
    }




}