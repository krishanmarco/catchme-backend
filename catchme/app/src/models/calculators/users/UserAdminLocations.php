<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Users;

use Location as DbLocation;
use LocationQuery;
use User as DbUser;

class UserAdminLocations {

    public function __construct(DbUser $user) {
        $this->user = $user;
        $this->calculateUserAdminLocations();
    }

    /** @var DbUser $user */
    private $user;

    /** @var DbLocation[] (int => DbLocation) */
    private $accDbLocations = [];

    /** @var int[] */
    private $locationIds = [];

    /** @return int[] */
    public function getLocationIds() {
        return $this->locationIds;
    }

    /** @return DbLocation[] (int => DbLocation) */
    public function getAccDbLocations() {
        return $this->accDbLocations;
    }

    private function calculateUserAdminLocations() {
        $locations = LocationQuery::create()
            ->findByAdminId($this->user->getId());

        foreach ($locations as $l) {
            $this->locationIds[] = $l->getId();
            $this->accDbLocations[$l->getId()] = $l;
        }
    }

}
