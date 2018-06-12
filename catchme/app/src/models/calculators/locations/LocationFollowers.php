<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Locations;

use Location as DbLocation;
use UserLocationFavorite as DbUserLocationFavorite;
use UserLocationFavoriteQuery;

class LocationFollowers {

    public function __construct(DbLocation $location) {
        $this->location = $location;
        $this->calculateLocationFollowers();
    }

    /** @var DbLocation $LocationModel */
    private $location;

    /** @var DbUserLocationFavorite[] */
    private $locationFollowers;

    /** @return DbUserLocationFavorite[] */
    public function getLocationFollowers() {
        return $this->locationFollowers;
    }

    private function calculateLocationFollowers() {
        $this->locationFollowers = UserLocationFavoriteQuery::create()
            ->filterByLocation($this->location)
            ->find()
            ->getData();
    }

}
