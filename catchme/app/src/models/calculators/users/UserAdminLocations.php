<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Users;

use User as DbUser;
use Models\UserAdminLocationsResult;
use LocationQuery;

class UserAdminLocations {

    public function __construct(DbUser $user) {
        $this->user = $user;
        $this->calculateUserAdminLocations();
    }

    /** @var DbUser $user */
    private $user;

    /** @var UserAdminLocationsResult $result */
    private $result;

    /** @return UserAdminLocationsResult */
    public function getResult() {
        return $this->result;
    }

    private function calculateUserAdminLocations() {
        $adminOfLocations = LocationQuery::create()
            ->findByAdminId($this->user->getId())
            ->getData();

        $this->result = new UserAdminLocationsResult($adminOfLocations);
    }

}
