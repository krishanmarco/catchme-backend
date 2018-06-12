<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Users;

use Location as DbLocation;
use Propel\Runtime\ActiveQuery\Criteria;
use User as DbUser;
use UserLocation as DbUserLocation;
use UserLocationExpired as DbUserLocationExpired;
use UserLocationExpiredQuery;
use UserLocationFavoriteQuery;
use UserLocationQuery;

class UserLocations {

    public function __construct(DbUser $user) {
        $this->user = $user;
        $this->calculateUserLocations();
    }

    /** @var DbUser $user */
    private $user;

    /** @var array(LocationId => Location) $accumulatedLocations */
    private $accLocations = [];

    /* @var int[] */
    public $favorites = [];

    /* @var int[] */
    public $top = [];

    /* @var DbUserLocation[] */
    public $userLocationStatuses = [];

    /** @var DbLocation[] */
    public $locations = [];

    /** @return int[] */
    public function getFavorites() {
        return $this->favorites;
    }

    /** @return int[] */
    public function getTop() {
        return $this->top;
    }

    /** @return DbUserLocation[] */
    public function getUserLocations() {
        return $this->userLocationStatuses;
    }

    /** @return DbLocation[] */
    public function getLocations() {
        return $this->locations;
    }

    private function calculateUserLocations() {
        $this->favorites = $this->calcFavoriteLocations();
        $this->top = $this->calcTopLocations();
        $this->userLocationStatuses = $this->calcUserLocationStatuses();
        $this->locations = array_values($this->accLocations);
    }

    private function calcFavoriteLocations() {
        $userFavoriteLocations = UserLocationFavoriteQuery::create()
            ->joinWithLocation()
            ->findByUserId($this->user->getId());


        $favoriteLocations = [];
        foreach ($userFavoriteLocations as $loc) {
            $locationId = $loc->getLocationId();

            if (!array_key_exists($locationId, $this->accLocations))
                $this->accLocations[$locationId] = $loc->getLocation();

            array_push($favoriteLocations, $locationId);
        }

        return $favoriteLocations;
    }

    private function calcTopLocations($numberOfItems = USER_LOCATIONS_MAX_TOP_ITEMS) {
        // Calculate a count for each location from the expired location table
        // SELECT id, location_id, COUNT(*) FROM user_location_expired
        // WHERE user_id = 1 GROUP BY location_id ORDER BY COUNT(*) DESC;
        /** @var DbUserLocationExpired[] $finalLocations */
        $finalLocations = UserLocationExpiredQuery::create()
            ->filterByUserId($this->user->getId())
            ->groupByLocationId()
            ->withColumn('COUNT(*)', 'Count')
            ->joinWithLocation()
            ->orderBy('Count', Criteria::DESC)
            ->limit($numberOfItems)
            ->find();

        if (sizeof($finalLocations) < USER_LOCATIONS_MAX_TOP_ITEMS) {
            // Run the same query with UserLocation
            $locations = UserLocationQuery::create()
                ->filterByUserId($this->user->getId())
                ->groupByLocationId()
                ->withColumn('COUNT(*)', 'Count')
                ->joinWithLocation()
                ->orderBy('Count', Criteria::DESC)
                ->limit($numberOfItems - sizeof($numberOfItems))
                ->find();

            $finalLocations = array_merge($finalLocations, $locations);
        }

        $topLocations = [];
        foreach ($finalLocations as $loc) {
            $locationId = $loc->getLocationId();

            if (!array_key_exists($locationId, $this->accLocations))
                $this->accLocations[$locationId] = $loc->getLocation();

            array_push($topLocations, $locationId);
        }

        return $topLocations;
    }

    private function calcUserLocationStatuses() {
        $userLocationStatuses = [];

        $userLocations = UserLocationQuery::create()
            ->orderByFromTs(Criteria::ASC)
            ->joinWithLocation()
            ->findByUserId($this->user->getId());


        foreach ($userLocations as $userLoc) {
            $locationId = $userLoc->getLocationId();

            if (!array_key_exists($locationId, $this->accLocations))
                $this->accLocations[$locationId] = $userLoc->getLocation();

            array_push($userLocationStatuses, $userLoc);
        }

        return $userLocationStatuses;
    }

}
