<?php /** Created by Krishan Marco Madan on 12-Jun-18. */

namespace Api;

use Api\Map\ModelToApiLocations;
use Api\Map\ModelToApiUserLocations;
use Api\Map\ModelToApiUsers;
use Location as DbLocation;
use User as DbUser;
use UserLocation as DbUserLocation;

abstract class ApiResponseWithMetadata {

    public function __construct() {
        $this->modelToApiLocations = ModelToApiLocations::multiple();
        $this->modelToApiUsers = ModelToApiUsers::multiple();
        $this->modelToApiUserLocations = ModelToApiUserLocations::multiple();
    }

    /** @var ModelToApiLocations */
    private $modelToApiLocations;

    /** @var ModelToApiUsers */
    private $modelToApiUsers;

    /** @var ModelToApiUserLocations */
    private $modelToApiUserLocations;

    /** @var DbLocation[] (int => DbLocation) */
    private $dbLocations = [];

    /** @var DbUser[] (int => DbUser) */
    private $dbUsers = [];

    /** @var DbUserLocation[] (int => DbUserLocation) */
    private $dbUserLocations = [];

    protected function setMetadataAndGet($object) {
        $object->metadata = new ApiMetadata();

        $object->metadata->locations = $this->modelToApiLocations
            ->locations(array_values($this->dbLocations));

        $object->metadata->users = $this->modelToApiUsers
            ->users(array_values($this->dbUsers));

        $object->metadata->userLocations = $this->modelToApiUserLocations
            ->userLocations(array_values($this->dbUserLocations));

        return $object;
    }

    /** @param DbLocation[] (int => DbLocation) $dbLocations */
    protected function metadataAddLocations(array $dbLocations) {
        $this->dbLocations = array_merge($this->dbLocations, $dbLocations);
    }

    /** @param DbUser[] (int => DbUser) $dbUsers */
    protected function metadataAddUsers(array $dbUsers) {
        $this->dbUsers = array_merge($this->dbUsers, $dbUsers);
    }

    /** @param DbUserLocation[] (int => DbUserLocation) $dbUserLocations */
    protected function metadataAddUserLocations(array $dbUserLocations) {
        $this->dbUserLocations = array_merge($this->dbUserLocations, $dbUserLocations);
    }
}