<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 */

namespace Controllers;

use Api\Location as ApiLocation;
use Api\Map\ModelToApiLocations;
use Models\Calculators\JoinedLocationUserModel;
use Models\Calculators\LocationModel;
use User as DbUser;

class ControllerLocations {

    public function __construct(DbUser $authenticatedUser, $locationId) {
        $this->authUser = $authenticatedUser;
        $this->locationId = $locationId;
    }

    /** @var DbUser $authUser */
    private $authUser;

    /** @var int $locationId */
    private $locationId;

    /** @return ApiLocation */
    public function get() {
        $locationModel = LocationModel::fromId($this->locationId);
        return ModelToApiLocations::single($locationModel->getLocation())->get();
    }

    /** @return ApiLocation */
    public function getProfile() {
        $locationModel = LocationModel::fromId($this->locationId);

        $joinedLocationUserModel = JoinedLocationUserModel::fromIds(
            $this->locationId,
            $this->authUser->getId()
        );

        return ModelToApiLocations::single($joinedLocationUserModel->getLocation())
            ->withAddress()
            ->withImages($locationModel->getLocationImages())
            ->withPeople($locationModel->getLocationCount())
            ->withConnections($joinedLocationUserModel->getLocationFriends())
            ->applyPrivacyPolicy($this->authUser)
            ->get();
    }

}