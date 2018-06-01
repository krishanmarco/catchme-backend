<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use Propel\Runtime\ActiveQuery\Criteria;
use UserLocationFavoriteQuery;
use UserLocationExpiredQuery;
use UserLocationExpired;
use UserLocationQuery;
use User as DbUser;
use Models\UserLocationsResult;

class UserLocations {

    public function __construct(DbUser $user) {
        $this->user = $user;
        $this->calculateUserLocations();
    }

    /** @var DbUser $user */
    private $user;

    /** @var UserLocationsResult $result */
    private $result;

    /** @var array(LocationId => Location) $accumulatedLocations */
    private $accLocations = [];

    /** @return UserLocationsResult */
    public function getResult() {
        return $this->result;
    }

    private function calculateUserLocations() {
        $favoriteLocations = $this->calcFavoriteLocations();
        $topLocations = $this->calcTopLocations();
        $userLocationStatuses = $this->calcUserLocationStatuses();

        $this->result = new UserLocationsResult(
            $favoriteLocations,
            $topLocations,
            $userLocationStatuses,
            array_values($this->accLocations)
        );
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
        /** @var UserLocationExpired[] $finalLocations */
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
