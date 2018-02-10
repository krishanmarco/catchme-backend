<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Api\Map;
use UserLocation as DbUserLocationStatus;
use Api\UserLocationStatus as ApiUserLocationStatus;


class ModelToApiUserLocations {

    /** @return ApiUserLocationStatus */
    public static function single(DbUserLocationStatus $dbUserLocationStatus) {
        $mtaul = new ModelToApiUserLocation($dbUserLocationStatus);
        return $mtaul->get();
    }

    /** @return ApiUserLocationStatus[] */
    public static function multiple(array $dbUserLocation) {
        $mtaul = new ModelToApiUserLocations($dbUserLocation);
        return $mtaul->get();
    }


    private function __construct(array $dbuserLocations) {
        $this->dbUserLocations = $dbuserLocations;
    }


    /** @var DbUserLocationStatus[] $dbUserLocations */
    private $dbUserLocations;


    /** @return ApiUserLocationStatus[] */
    public function get() {
        return array_map([$this, 'userLocationStatus'], $this->dbUserLocations);
    }


    /** @return ApiUserLocationStatus */
    public function userLocationStatus(DbUserLocationStatus $dbUser) {
        return ModelToApiUserLocations::single($dbUser);
    }

}