<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators;

use Location as DbLocation;
use LocationQuery as LocationQuery;
use Models\Calculators\Locations\LocationCount;
use Models\Calculators\Locations\LocationFollowers;
use Models\Calculators\Locations\LocationImages;
use Models\Calculators\Locations\LocationUsers;

class LocationModel {

    private static $LocationModel = [
        // array of type {lid} => LocationModel
    ];

    /** @return LocationModel */
    public static function fromId($lid) {
        if (!array_key_exists($lid, self::$LocationModel)) {
            $location = LocationQuery::create()->findPk(intval($lid));
            self::$LocationModel[$lid] = new LocationModel($location);
        }

        return self::$LocationModel[$lid];
    }

    /** @return LocationModel */
    public static function fromLocation(DbLocation $location) {
        if (!array_key_exists($location->getId(), self::$LocationModel)) {
            self::$LocationModel[$location->getId()] = new LocationModel($location);
        }

        return self::$LocationModel[$location->getId()];
    }

    private function __construct(DbLocation $location) {
        $this->location = $location;
    }

    /** @var DbLocation */
    private $location;

    /** @var LocationCount */
    private $locationCount;

    /** @var LocationImages */
    private $locationImages;

    /** @var LocationUsers */
    private $locationUsers;

    /** @var LocationFollowers */
    private $locationFollowers;

    /** @return DbLocation */
    public function getLocation() {
        return $this->location;
    }

    /** @return LocationCount */
    public function getLocationCount() {
        if (is_null($this->locationCount))
            $this->locationCount = new LocationCount($this->location);
        return $this->locationCount;
    }

    /** @return LocationImages */
    public function getLocationImages() {
        if (is_null($this->locationImages))
            $this->locationImages = new LocationImages($this->location);
        return $this->locationImages;
    }

    /** @return LocationUsers */
    public function getLocationUsers() {
        if (is_null($this->locationUsers))
            $this->locationUsers = new LocationUsers($this->location);
        return $this->locationUsers;
    }

    /** @return LocationFollowers */
    public function getLocationFollowers() {
        if (is_null($this->locationUsers))
            $this->locationFollowers = new LocationFollowers($this->getLocation());
        return $this->locationFollowers;
    }


}