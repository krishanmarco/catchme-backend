<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators;
use Location as Location;
use LocationQuery as LocationQuery;
use Models\Calculators\Locations\LocationCount;
use Models\Calculators\Locations\LocationCountResult;
use Models\Calculators\Locations\LocationImages;
use Models\Calculators\Locations\LocationImagesResult;
use Models\Calculators\Locations\LocationUsers;
use Models\Calculators\Locations\LocationUsersResult;


class LocationModel {

    private static $LocationModel = [
        // array of type {location-id} => LocationModel
    ];


    /** @return LocationModel */
    public static function fromId($locationId) {
        return self::fromLocation(LocationQuery::create()->findPk(intval($locationId)));
    }


    /** @return LocationModel */
    public static function fromLocation(Location $location) {

        if (array_key_exists($location->getId(), self::$LocationModel))
            return self::$LocationModel[$location->getId()];

        self::$LocationModel[$location->getId()] = new LocationModel($location);

        return self::$LocationModel[$location->getId()];
    }




    private function __construct(Location $location) {
        $this->location = $location;
    }


    /** @var Location $location */
    private $location;
    public function getLocation() { return $this->location; }



    /** @var LocationCountResult $locationCountResult */
    private $locationCountResult;

    public function getLocationCountResult() {
        if (is_null($this->locationCountResult)) {
            $locationCount = new LocationCount($this);
            $this->locationCountResult = $locationCount->execute();
        }

        return $this->locationCountResult;
    }




    /** @var LocationImagesResult[] $locationImagesResult */
    private $locationImagesResult;

    public function getLocationImagesResult() {
        if (is_null($this->locationImagesResult)) {
            $locationImages = new LocationImages($this);
            $this->locationImagesResult = $locationImages->execute();
        }

        return $this->locationImagesResult;
    }



    /** @var LocationUsersResult $locationUsersResult */
    private $locationUsersResult;

    public function getLocationUsersResult() {
        if (is_null($this->locationUsersResult)) {
            $locationUsers = new LocationUsers($this);
            $this->locationUsersResult = $locationUsers->execute();
        }

        return $this->locationUsersResult;
    }


}