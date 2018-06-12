<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Users;

use User as DbUser;
use UserLocation as DbUserLocation;
use UserLocationQuery;

class UserLocationStatus {

    public function __construct(DbUser $user) {
        $this->user = $user;
        $this->calculateUserLocationStatus();
    }

    /** @var DbUser */
    private $user;

    /** @var DbUserLocation[] */
    public $userLocationStatuses = [];

    /** @return DbUserLocation[] */
    public function getUserLocations() {
        return $this->userLocationStatuses;
    }

    private function calculateUserLocationStatus() {
        $this->userLocationStatuses = UserLocationQuery::create()
            ->findByUserId($this->user->getId())
            ->getData();
    }
}

