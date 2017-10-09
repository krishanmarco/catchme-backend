<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use Models\Calculators\UserModel;
use Location;
use LocationQuery;


class UserAdminLocations {


    public function __construct(UserModel $userModel) {
        $this->userModel = $userModel;
    }


    /** @var UserModel $userModel */
    private $userModel;
    public function getUser() { return $this->userModel->getUser(); }



    public function execute() {
        $adminOfLocations = LocationQuery::create()
            ->findByAdminId($this->getUser()->getId())
            ->getData();

        return new UserAdminLocationsResult($adminOfLocations);
    }


}


class UserAdminLocationsResult {

    /**
     * UserAdminLocationsResult constructor.
     * @param array $adminOfLocations
     */
    public function __construct(array $adminOfLocations) {
        $this->adminOfLocations = $adminOfLocations;
    }


    /** @var Location[] $suggestedFriends */
    private $adminOfLocations;

    public function getAdminOfLocations() {
        return $this->adminOfLocations;
    }


}

