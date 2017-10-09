<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Locations;

use Models\Calculators\LocationModel;
use UserLocationQuery as UserLocationQuery;
use UserLocation as UserLocation;


class LocationUsers {

    public function __construct(LocationModel $LocationModel) {
        $this->LocationModel = $LocationModel;
    }


    /** @var LocationModel $LocationModel */
    private $LocationModel;
    public function getLocation() { return $this->LocationModel->getLocation(); }




    public function execute() {
        $userLocations = UserLocationQuery::create()
            ->filterByLocationId($this->getLocation()->getId())
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

        return new LocationUsersResult($usersNow, $usersFuture);
    }

}




class LocationUsersResult {

    /**
     * CalcResultLocationUsers constructor.
     * @param UserLocation[] $usersNow
     * @param UserLocation[] $usersFuture
     */
    public function __construct(array $usersNow, array $usersFuture) {
        $this->usersNow = $usersNow;
        $this->usersFuture = $usersFuture;
    }


    /** @var UserLocation[] $usersNow */
    private $usersNow;
    public function getUsersNow() { return $this->usersNow; }

    /** @var UserLocation[] $usersFuture */
    private $usersFuture;
    public function getUsersFuture() { return $this->usersFuture; }

}