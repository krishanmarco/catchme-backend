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

    /** @var DbUser */
    private $user;

    /** @var DbLocation[] (int => Location) */
    private $accLocations = [];

    /* @var DbUserLocation[] (int => UserLocation) */
    public $accUserLocations = [];

    /* @var int[] */
    public $favoriteIds = [];

    /* @var int[] */
    public $topIds = [];

    /** @var int[] */
    public $userLocationIds = [];

    /** @return int[] */
    public function getFavoriteIds() {
        return $this->favoriteIds;
    }

    /** @return int[] */
    public function getTopIds() {
        return $this->topIds;
    }

    /** @return int[] */
    public function getUserLocationIds() {
        return $this->userLocationIds;
    }

    /** @return DbLocation[] (int => DbLocation) */
    public function getAccDbLocations() {
        return $this->accLocations;
    }

    /** @return DbUserLocation[] (int => DbUserLocation) */
    public function getAccDbUserLocations() {
        return $this->accUserLocations;
    }

    private function calculateUserLocations() {
        $this->calcFavoriteLocations();
        $this->calcTopLocations();
        $this->calcUserLocationStatuses();
    }

    private function calcFavoriteLocations() {
        $userFavoriteLocations = UserLocationFavoriteQuery::create()
            ->joinWithLocation()
            ->findByUserId($this->user->getId());

        foreach ($userFavoriteLocations as $ulf) {
            $this->favoriteIds[] = $ulf->getLocationId();
            $this->accLocations[$ulf->getLocationId()] = $ulf->getLocation();
        }
    }

    private function calcTopLocations($numberOfItems = USER_LOCATIONS_MAX_TOP_ITEMS) {
        // Calculate a count for each location from the expired location table
        // SELECT id, location_id, COUNT(*) FROM user_location_expired
        // WHERE user_id = 1 GROUP BY location_id ORDER BY COUNT(*) DESC;
        /** @var DbUserLocationExpired[] $finalLocations */
        $finalLocations = UserLocationExpiredQuery::create()
            ->joinWithLocation()
            ->filterByUserId($this->user->getId())
            ->groupByLocationId()
            ->withColumn('COUNT(*)', 'Count')
            ->orderBy('Count', Criteria::DESC)
            ->limit($numberOfItems)
            ->find();

        if (sizeof($finalLocations) < USER_LOCATIONS_MAX_TOP_ITEMS) {
            // Run the same query with UserLocation
            $locations = UserLocationQuery::create()
                ->joinWithLocation()
                ->filterByUserId($this->user->getId())
                ->groupByLocationId()
                ->withColumn('COUNT(*)', 'Count')
                ->orderBy('Count', Criteria::DESC)
                ->limit($numberOfItems - sizeof($numberOfItems))
                ->find();

            $finalLocations = array_merge($finalLocations, $locations);
        }

        foreach ($finalLocations as $ul) {
            $this->topIds[] = $ul->getLocationId();
            $this->accLocations[$ul->getLocationId()] = $ul->getLocation();
        }
    }

    private function calcUserLocationStatuses() {
        $userLocations = UserLocationQuery::create()
            ->joinWithLocation()
            ->orderByFromTs(Criteria::ASC)
            ->findByUserId($this->user->getId());

        foreach ($userLocations as $ul) {
            $this->userLocationIds[] = $ul->getId();
            $this->accUserLocations[$ul->getId()] = $ul;
            $this->accLocations[$ul->getLocationId()] = $ul->getLocation();
        }
    }

}
