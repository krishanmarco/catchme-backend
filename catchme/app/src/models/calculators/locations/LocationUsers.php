<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Locations;

use UserLocationQuery;
use Models\LocationUsersResult;
use Location as DbLocation;

class LocationUsers {

    public function __construct(DbLocation $location) {
        $this->location = $location;
        $this->calculateLocationUsers();
    }

    /** @var DbLocation */
    private $location;

    /** @var LocationUsersResult */
    private $result;

    /** @return LocationUsersResult */
    public function getResult() {
        return $this->result;
    }

    private function calculateLocationUsers() {
        $userLocations = UserLocationQuery::create()
            ->filterByLocationId($this->location->getId())
            ->joinWithUser()
            ->find();

        // Initialize result fields
        $usersNow = [];
        $usersFuture = [];

        foreach ($userLocations as $ul) {
            // Check if $ul user is at this
            // location now or in the future

            if ($ul->getFromTs() <= time() && $ul->getUntilTs() >= time())
                array_push($usersNow, $ul);

            else if ($ul->getFromTs() >= time())
                array_push($usersFuture, $ul);
        }

        $this->result = new LocationUsersResult($usersNow, $usersFuture);
    }

}
