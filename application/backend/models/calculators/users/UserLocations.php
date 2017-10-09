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




    public function execute() {
        $favoriteLocations = $this->calcFavoriteLocations();
        $topLocations = $this->calcTopLocations();
        $pastNowFutureLocations = $this->calcPastNowFutureLocations();

        return new UserLocationsResult(
            $favoriteLocations,
            $topLocations,
            $pastNowFutureLocations[0],
            $pastNowFutureLocations[1],
            $pastNowFutureLocations[2]
        );
    }




    private function calcFavoriteLocations() {
        $userFavoriteLocations = UserFavoriteLocationQuery::create()
            ->joinWithLocation()
            ->findByUserId($this->getUser()->getId());


        $favoriteLocations = [];
        foreach ($userFavoriteLocations as $favoriteLocation)
            array_push($favoriteLocations, $favoriteLocation->getLocation());

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
        foreach ($locations as $loc)
            array_push($topLocations, $loc->getLocation());

        return $topLocations;
    }


    private function calcPastNowFutureLocations() {
        $pastLocation = [];
        $nowLocation = [];
        $futureLocation = [];


        $userLocations = UserLocationQuery::create()
            ->orderByFromTs(Criteria::ASC)
            ->joinWithLocation()
            ->findByUserId($this->getUser()->getId());



        foreach ($userLocations as $userLocation) {

            if (time() < $userLocation->getFromTs())
                $futureLocation[] = $userLocation->getLocation();

            else if (time() >= $userLocation->getFromTs() && time() <= $userLocation->getUntilTs())
                $nowLocation[] = $userLocation->getLocation();

            else if (time() > $userLocation->getUntilTs())
                $pastLocation[] = $userLocation->getLocation();

        }


        return [$pastLocation, $nowLocation, $futureLocation];

    }



}



class UserLocationsResult {

    public function __construct(array $favorites, array $top, array $past, array $now, array $future) {
        $this->favorites = $favorites;
        $this->top = $top;
        $this->past = $past;
        $this->now = $now;
        $this->future = $future;
    }




    /* @var Location[] $favorites */
    private $favorites;
    public function getFavorites() { return $this->favorites; }

    /* @var Location[] $top */
    private $top;
    public function getTop() { return $this->top; }

    /* @var Location[] $past */
    private $past;
    public function getPast() { return $this->past; }

    /* @var Location[] $now */
    private $now;
    public function getNow() { return $this->now; }

    /* @var Location[] $future */
    private $future;
    public function getFuture() { return $this->future; }



}
