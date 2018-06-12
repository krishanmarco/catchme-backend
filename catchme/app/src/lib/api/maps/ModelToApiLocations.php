<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 15/09/2017 */

namespace Api\Map;

use Location as DbLocation;


class ModelToApiLocations {

    public static function single(DbLocation $dbLocation) {
        return new ModelToApiLocation($dbLocation);
    }

    public static function multiple() {
        return new ModelToApiLocations();
    }


    public function locations(array $dbLocations) {
        return array_map([$this, 'location'], $dbLocations);
    }


    private function location(DbLocation $dbLocation) {
        return self::single($dbLocation)
            ->get();
    }

}