<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Locations;

use Location as DbLocation;
use User as DbUser;
use UserLocation as DbUserLocation;
use UserLocationQuery;

class LocationUsers {

    public function __construct(DbLocation $location) {
        $this->location = $location;
        $this->calculateLocationUsers();
    }

    /** @var DbLocation */
    private $location;

    /** @var DbUser[] (int => DbUser) */
    private $accUsers;

    /** @var int[] */
    private $usersNowIds;

    /** @var int[] */
    private $userLaterIds;

    /** @var DbUser[] (int => DbUser) */
    public function getAccDbUsers() {
        return $this->accUsers;
    }

    /** @return int[] */
    public function getUsersNowIds() {
        return $this->usersNowIds;
    }

    /** @return int[] */
    public function getUserLaterIds() {
        return $this->userLaterIds;
    }

    private function calculateLocationUsers() {
        $userLocations = UserLocationQuery::create()
            ->filterByLocationId($this->location->getId())
            ->joinWithUser()
            ->find();

        foreach ($userLocations as $ul) {
            // Check if $ul user is at this
            // location now or in the future

            if ($ul->getFromTs() <= time() && $ul->getUntilTs() >= time()) {
                $this->usersNowIds[] = $ul->getUserId();
                $this->accUsers[$ul->getUserId()] = $ul->getUser();

            } else if ($ul->getFromTs() >= time()) {
                $this->userLaterIds[] = $ul->getUserId();
                $this->accUsers[$ul->getUserId()] = $ul->getUser();
            }
        }
    }

}
