<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Routes\Accessors;
use Controllers\ControllerLocations;
use Controllers\ControllerMediaPut;
use Controllers\ControllerUsers;
use User;
use Controllers\ControllerUser;
use Controllers\ControllerSearch;


class ControllerAccessorAuth {

    public function __construct(User $user) {
        $this->authenticatedUser = $user;
    }

    /** @var User $authenticatedUser */
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