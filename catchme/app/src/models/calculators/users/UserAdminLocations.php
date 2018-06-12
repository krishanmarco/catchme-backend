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

    /** @var DbLocation[] */
    private $locations;

    /** @return DbLocation[] */
    public function getLocations() {
        return $this->locations;
    }

    private function calculateUserAdminLocations() {
        $this->locations = LocationQuery::create()
            ->findByAdminId($this->user->getId())
            ->getData();
    }

}
