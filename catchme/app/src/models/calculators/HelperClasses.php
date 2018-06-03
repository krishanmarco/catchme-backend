<?php /** Created by Krishan Marco Madan on 17-May-18. */

namespace Models;
use KMeans\Clusterizer;
use User as DbUser;
use Location as DbLocation;
use UserLocation as DbUserLocation;
use WeightedCalculator\WeightedUnit;

class LocationFriendsResult {

    /**
     * LocationFriendsResult constructor.
     * @param DbUser[] $now
     * @param DbUser[] $future
     */
    public function __construct(array $now, array $future) {
        $this->now = $now;
        $this->future = $future;
    }

    /* @var DbUser[] */
    public $now;
    /* @var DbUser[] */
    public $future;
}

class LocationCountResult {

    /**
     * LocationCountResult constructor.
     * @param int $men
     * @param int $women
     * @param int $total
     */
    public function __construct($men, $women, $total) {
        $this->men = $men;
        $this->women = $women;
        $this->total = $total;
    }

    /** @var int */
    public $men;
    /** @var int */
    public $women;
    /** @var int */
    public $total;
}

class LocationImagesResult {

    /**
     * LocationImagesResult constructor.
     * @param int $id
     * @param int $itemId
     * @param int<EMediaType> $typeId
     * @param string $url
     */
    public function __construct($id, $itemId, $typeId, $url) {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->typeId = $typeId;
        $this->url = $url;
    }

    /** @var int */
    public $id;
    /** @var int */
    public $itemId;
    /** @var int<EMediaType> */
    public $typeId;
    /** @var string */
    public $url;
}

class LocationUsersResult {

    /**
     * LocationUsersResult constructor.
     * @param DbUserLocation[] $usersNow
     * @param DbUserLocation[] $usersFuture
     */
    public function __construct(array $usersNow, array $usersFuture) {
        $this->usersNow = $usersNow;
        $this->usersFuture = $usersFuture;
    }

    /** @var DbUserLocation[] */
    public $usersNow;
    /** @var DbUserLocation[] */
    public $usersFuture;
}

class UserAdminLocationsResult {

    /**
     * UserAdminLocationsResult constructor.
     * @param DbLocation[] $adminOfLocations
     */
    public function __construct(array $adminOfLocations) {
        $this->adminOfLocations = $adminOfLocations;
    }

    /** @var DbLocation[] */
    public $adminOfLocations;
}

class UserLocationsResult {

    /**
     * UserLocationsResult constructor.
     * @param int[] $favorites
     * @param int[] $top
     * @param DbUserLocation[] $userLocationStatuses
     * @param DbLocation[] $locations
     */
    public function __construct(array $favorites, array $top, array $userLocationStatuses, array $locations) {
        $this->favorites = $favorites;
        $this->top = $top;
        $this->userLocationStatuses = $userLocationStatuses;
        $this->locations = $locations;
    }

    /* @var int[] */
    public $favorites;
    /* @var int[] */
    public $top;
    /* @var DbUserLocation[] */
    public $userLocationStatuses;
    /** @var DbLocation[] */
    public $locations;
}

class UserLocationStatusResult {

    /**
     * UserLocationStatusResult constructor.
     * @param DbUserLocation[] $userLocationStatus
     */
    public function __construct(array $userLocationStatus) {
        $this->userLocationStatus = $userLocationStatus;
    }
    
    /** @var DbUserLocation[] */
    public $userLocationStatus;
}


class UserSuggestedFriendsResult {

    /**
     * UserSuggestedFriendsResult constructor.
     * @param DbUser[] $suggestedFriends
     */
    public function __construct(array $suggestedFriends) {
        $this->suggestedFriends = $suggestedFriends;
    }

    /** @var DbUser[] */
    public $suggestedFriends;
}

class UserSuggestedLocationsResult {

    /**
     * UserSuggestedLocationsResult constructor.
     * @param DbLocation[] $suggestedLocations
     */
    public function __construct(array $suggestedLocations) {
        $this->suggestedLocations = $suggestedLocations;
    }
    
    /** @var DbLocation[] */
    public $suggestedLocations;
}


class UserConnectionsResult {

    /**
     * UserConnectionsResult constructor.
     * @param DbUser[] $friends
     * @param DbUser[] $requests
     * @param DbUser[] $blocked
     */
    public function __construct(array $friends, array $pending, array $requests, array $blocked) {
        $this->friends = $friends;
        $this->pending = $pending;
        $this->requests = $requests;
        $this->blocked = $blocked;
    }

    /** @var DbUser[] */
    public $friends;
    /** @var DbUser[] */
    public $pending;
    /** @var DbUser[] */
    public $requests;
    /** @var DbUser[] */
    public $blocked;
}

class LocIdCoord {

    public function __construct($lid, $lat, $lng) {
        $this->lid = $lid;
        $this->lat = $lat;
        $this->lng = $lng;
    }

    /** @var int */
    public $lid;
    /** @var double */
    public $lat;
    /** @var double */
    public $lng;
}

class UserSuggestedLocationsResWrapper {
    /** @var Clusterizer */
    public $clusterizer;
    /** @var WeightedUnit[] */
    public $weightedUnits;
}
