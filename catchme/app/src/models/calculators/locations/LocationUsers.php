<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Locations;

use Location as DbLocation;
use UserLocation as DbUserLocation;
use UserLocationQuery;

class LocationUsers {

    public function __construct(DbLocation $location) {
        $this->location = $location;
        $this->calculateLocationUsers();
    }

    /** @var DbLocation */
    private $location;

    /** @var DbUserLocation[] */
    private $usersNow;

    /** @var DbUserLocation[] */
    private $usersLater;

    /** @return DbUserLocation[] */
    public function getUsersNow() {
        return $this->usersNow;
    }

    /** @return DbUserLocation[] */
    public function getUsersLater() {
        return $this->usersLater;
    }

    private function calculateLocationUsers() {
        $userLocations = UserLocationQuery::create()
            ->filterByLocationId($this->location->getId())
            ->joinWithUser()
            ->find();

        foreach ($userLocations as $ul) {
            // Check if $ul user is at this
            // location now or in the future

            if ($ul->getFromTs() <= time() && $ul->getUntilTs() >= time())
                array_push($this->usersNow, $ul);

            else if ($ul->getFromTs() >= time())
                array_push($this->usersLater, $ul);
        }
    }

}
