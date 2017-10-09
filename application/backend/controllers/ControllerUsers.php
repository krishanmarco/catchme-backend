<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;
use Api\Map\ModelToApiUsers;
use Models\Calculators\UserModel;
use User as DbUser;
use Api\User as ApiUser;


class ControllerUsers {
    
    public function __construct(DbUser $authenticatedUser, $userId) {
        $this->authenticatedUser = $authenticatedUser;
        $this->userModel = UserModel::fromId($userId);
    }


    /** @var DbUser $authenticatedUser */
    private $authenticatedUser;



    /** @var UserModel $userModel */
    private $userModel;

    private function getUser() {
        return $this->userModel->getUser();
    }





    /** @return ApiUser */
    public function get() {
        return ModelToApiUsers::single($this->getUser())
            ->applyPrivacyPolicy($this->authenticatedUser)
            ->get();
    }




    /** @return ApiUser */
    public function getProfile() {
        return ModelToApiUsers::single($this->getUser())
            ->withEmail()
            ->withPhone()
            ->withLocations($this->userModel->getUserLocationsResult())
            ->withConnections($this->userModel->getUserConnectionsResult())
            ->applyPrivacyPolicy($this->authenticatedUser)
            ->get();
    }


}