<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Routes\Accessors;

use Controllers\ControllerLocations;
use Controllers\ControllerMediaPut;
use Controllers\ControllerSearch;
use Controllers\ControllerUser;
use Controllers\ControllerUsers;
use User as DbUser;


class ControllerAccessorAuth {

    public function __construct(DbUser $user) {
        $this->authenticatedUser = $user;
    }

    /** @var DbUser $authenticatedUser */
    private $authenticatedUser;


    public function locations($locationId) {
        return new ControllerLocations($this->authenticatedUser, $locationId);
    }

    public function search() {
        return new ControllerSearch($this->authenticatedUser);
    }

    public function users($userId) {
        return new ControllerUsers($this->authenticatedUser, $userId);
    }

    public function user() {
        return new ControllerUser($this->authenticatedUser);
    }

    public function media() {
        return new ControllerMediaPut($this->authenticatedUser);
    }

}