<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 */

namespace Controllers;

use Api\Map\ModelToApiUsers;
use Api\User as ApiUser;
use Models\Calculators\UserModel;
use User as DbUser;

class ControllerUsers {

    public function __construct(DbUser $authUser, $userId) {
        $this->authUser = $authUser;
        $this->userId = $userId;
    }

    /** @var DbUser */
    private $authUser;

    /** @var int */
    private $userId;

    /** @return ApiUser */
    public function get() {
        $userModel = UserModel::fromId($this->userId);
        return ModelToApiUsers::single($userModel->getUser())
            ->applyPrivacyPolicy($this->authUser)
            ->get();
    }

    /** @return ApiUser */
    public function getProfile() {
        $userModel = UserModel::fromId($this->userId);
        return ModelToApiUsers::single($userModel->getUser())
            ->withEmail()
            ->withPhone()
            ->withLocations($userModel->getUserLocations())
            ->withConnections($userModel->getUserConnections())
            ->applyPrivacyPolicy($this->authUser)
            ->get();
    }

}