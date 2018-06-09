<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Users;

use UserLocationQuery;
use User as DbUser;
use Models\UserLocationStatusResult;

class UserLocationStatus {

    public function __construct(DbUser $user) {
        $this->user = $user;
        $this->calculateUserLocationStatus();
    }

    /** @var DbUser */
    private $user;

    /** @var UserLocationStatusResult */
    private $result;

    /** @return UserLocationStatusResult */
    public function getResult() {
        return $this->result;
    }

    private function calculateUserLocationStatus() {
        $userLocationStatus = UserLocationQuery::create()
            ->findByUserId($this->user->getId())
            ->getData();

        $this->result = new UserLocationStatusResult($userLocationStatus);
    }
}

