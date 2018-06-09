<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 */

namespace Controllers;

use Api\Map\ModelToApiLocations;
use Models\Calculators\LocationModel;
use Models\Calculators\JoinedLocationUserModel;
use User as DbUser;
use Api\Location as ApiLocation;

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
            ->applyPrivacyPolicy($this->authUser)
            ->withAddress()
            ->withImages($locationModel->getLocationImages()->getResult())
            ->withPeople($locationModel->getLocationCount()->getResult())
            ->withConnections($joinedLocationUserModel->getLocationFriends()->getResult())
            ->get();
    }

}