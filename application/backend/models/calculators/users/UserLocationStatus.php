<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use Models\Calculators\UserModel;
use UserLocation;
use UserLocationQuery;


class UserLocationStatus {


    public function __construct(UserModel $userModel) {
        $this->userModel = $userModel;
    }


    /** @var UserModel $userModel */
    private $userModel;
    public function getUser() { return $this->userModel->getUser(); }



    public function execute() {
        $userLocationStatus = UserLocationQuery::create()
            ->findByUserId($this->getUser()->getId())
            ->getData();

        return new UserLocationStatusResult($userLocationStatus);
    }


}


class UserLocationStatusResult {

    /**
     * UserLocationStatusResult constructor.
     * @param UserLocation[] $userLocationStatus
     */
    public function __construct(array $userLocationStatus) {
        $this->userLocationStatus = $userLocationStatus;
    }


    /** @var UserLocation[] $userLocationStatus */
    private $userLocationStatus;

    public function getUserLocationStatusList() {
        return $this->userLocationStatus;
    }


}

