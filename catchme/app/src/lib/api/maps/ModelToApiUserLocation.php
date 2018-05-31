<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Api\Map;
use UserLocation as DbUserLocation;
use Api\UserLocationStatus as ApiUserLocationStatus;


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
    public function get() { return $this->apiUserLocationStatus; }


    /** @return ModelToApiUserLocation */
    private function withBasicParameters() {
        $this->apiUserLocationStatus->id = $this->dbUserLocation->getId();
        $this->apiUserLocationStatus->locationId = $this->dbUserLocation->getLocationId();
        $this->apiUserLocationStatus->fromTs = $this->dbUserLocation->getFromTs();
        $this->apiUserLocationStatus->untilTs = $this->dbUserLocation->getUntilTs();
        return $this;
    }

}