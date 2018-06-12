<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Api\Map;

use Api\UserLocationStatus as ApiUserLocationStatus;
use UserLocation as DbUserLocationStatus;


class ModelToApiUserLocations {

    /** @return ModelToApiUserLocation */
    public static function single(DbUserLocationStatus $dbUserLocationStatus) {
        return new ModelToApiUserLocation($dbUserLocationStatus);
    }

    /** @return ModelToApiUserLocations */
    public static function multiple() {
        return new ModelToApiUserLocations();
    }


    /** @return ApiUserLocationStatus[] */
    public function userLocationStatuses(array $userLocationStatuses) {
        return array_map([$this, 'userLocationStatus'], $userLocationStatuses);
    }


    /** @return ApiUserLocationStatus */
    private function userLocationStatus(DbUserLocationStatus $dbUser) {
        return self::single($dbUser)->get();
    }

}