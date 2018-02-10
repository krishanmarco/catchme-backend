<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use Models\Calculators\UserModel;
use Propel\Runtime\ActiveQuery\Criteria as Criteria;


use UserLocationFavoriteQuery as UserFavoriteLocationQuery;
use UserLocationExpiredQuery as UserLocationExpiredQuery;
use UserLocationExpired as UserLocationExpired;
use UserLocationQuery as UserLocationQuery;
use Location as Location;

class UserLocations {


    public function __construct(UserModel $UserModel) {
        $this->UserModel = $UserModel;
    }


    /** @var UserModel $UserModel */
    private $UserModel;
    public function getUser() { return $this->UserModel->getUser(); }


    /** @var array(LocationId => Location) $accumulatedLocations */
    private $accumulatedLocations;



    public function execute() {
        $favoriteLocations = $this->calcFavoriteLocations();
        $topLocations = $this->calcTopLocations();
        $userLocationStatuses = $this->calcUserLocationStatuses();

        return new UserLocationsResult(
            $favoriteLocations,
            $topLocations,
            $userLocationStatuses,
            array_values($this->accumulatedLocations)
        );
    }




    private function calcFavoriteLocations() {
        $userFavoriteLocations = UserFavoriteLocationQuery::create()
            ->joinWithLocation()
            ->findByUserId($this->getUser()->getId());


        $favoriteLocations = [];
        foreach ($userFavoriteLocations as $loc) {
            $locationId = $loc->getLocationId();

            if (!array_key_exists($locationId, $this->accumulatedLocations))
                $this->accumulatedLocations[$locationId] = $loc->getLocation();

            array_push($favoriteLocations, $locationId);
        }

        return $favoriteLocations;
    }




    private function calcTopLocations($numberOfItems = 5) {
        // Calculate a count for each location from the expired location table
        // SELECT id, location_id, COUNT(*) FROM user_location_expired
        // WHERE user_id = 1 GROUP BY location_id ORDER BY COUNT(*) DESC;
        /** @var UserLocationExpired[] $locations */
        $locations = UserLocationExpiredQuery::create()
            ->filterByUserId($this->getUser()->getId())
            ->groupByLocationId()
            ->withColumn('COUNT(*)', 'Count')
            ->joinWithLocation()
            ->orderBy('Count', Criteria::DESC)
            ->limit($numberOfItems)
            ->find();

        $topLocations = [];
        foreach ($locations as $loc) {
            $locationId = $loc->getLocationId();

            if (!array_key_exists($locationId, $this->accumulatedLocations))
                $this->accumulatedLocations[$locationId] = $loc->getLocation();

            array_push($topLocations, $locationId);
        }


        return $topLocations;
    }

    // todo: this is also duplicated in the UserLocationStatus calculator...
    private function calcUserLocationStatuses() {
        $userLocationStatuses = [];

        $userLocations = UserLocationQuery::create()
            ->orderByFromTs(Criteria::ASC)
            ->joinWithLocation()
            ->findByUserId($this->getUser()->getId());


        foreach ($userLocations as $userLoc) {
            $locationId = $userLoc->getLocationId();

            if (!array_key_exists($locationId, $this->accumulatedLocations))
                $this->accumulatedLocations[$locationId] = $userLoc->getLocation();

            array_push($userLocationStatuses, $userLoc);
        }

        return $userLocationStatuses;
    }



}



class UserLocationsResult {

    public function __construct(array $favorites, array $top, array $userLocationStatuses, array $locations) {
        $this->favorites = $favorites;
        $this->top = $top;
        $this->userLocationStatuses = $userLocationStatuses;
        $this->locations = $locations;
    }

    /* @var integer[] $favorites */
    private $favorites;
    public function getFavorites() { return $this->favorites; }

    /* @var integer[] $top */
    private $top;
    public function getTop() { return $this->top; }

    /* @var UserLocationStatus[] $userLocationStatuses */
    private $userLocationStatuses;
    public function getUserLocationStatuses() { return $this->userLocationStatuses; }

    /** @var Location[] $locations */
    private $locations;
    public function getLocations() { return $this->locations; }

}
