<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Api\Map;

use Api\UserLocationStatus as ApiUserLocationStatus;
use UserLocation as DbUserLocation;


class ModelToApiUserLocation {

    public function __construct(DbUserLocation $dbUserLocation) {
        $this->dbUserLocation = $dbUserLocation;
        $this->apiUserLocationStatus = clearObject(new ApiUserLocationStatus());
        $this->withBasicParameters();
    }

    /** @var DbUserLocation $dbUserLocation */
    private $dbUserLocation;

    /** @var ApiUserLocationStatus */
    private $apiUserLocationStatus;

    public function get() {
        return $this->apiUserLocationStatus;
    }

    /** @return ModelToApiUserLocation */
    private function withBasicParameters() {
        $this->apiUserLocationStatus->id = $this->dbUserLocation->getId();
        $this->apiUserLocationStatus->locationId = $this->dbUserLocation->getLocationId();
        $this->apiUserLocationStatus->fromTs = $this->dbUserLocation->getFromTs();
        $this->apiUserLocationStatus->untilTs = $this->dbUserLocation->getUntilTs();
        return $this;
    }

}