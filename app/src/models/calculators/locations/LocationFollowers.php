<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Locations;

use UserLocationFavoriteQuery;
use UserLocationFavorite;
use Location as DbLocation;

class LocationFollowers {

    public function __construct(DbLocation $location) {
        $this->location = $location;
        $this->calculateLocationFollowers();
    }

    /** @var DbLocation $LocationModel */
    private $location;

    /** @var UserLocationFavorite[] */
    private $locationFollowers;

    /** @return UserLocationFavorite[] */
    public function getLocationFollowers() {
        return $this->locationFollowers;
    }

    /** @return int[] */
    public function getResultIds() {
        return array_map(function(UserLocationFavorite $ulf) {
            return $ulf->getUserId();
        }, $this->locationFollowers);
    }

    private function calculateLocationFollowers() {
        $this->locationFollowers = UserLocationFavoriteQuery::create()
            ->filterByLocation($this->location)
            ->find()
            ->getData();
    }

}
