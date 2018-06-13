<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Locations;

use EGender;
use Location as DbLocation;
use Models\Calculators\LocationModel;

class LocationCount {

    public function __construct(DbLocation $location) {
        $this->location = $location;
        $this->calculateLocationCount();
    }

    /** @var DbLocation */
    private $location;

    /** @var int */
    private $menCount = 0;

    /** @var int */
    private $womenCount = 0;

    /** @var int */
    private $totalCount = 0;

    /** @return int */
    public function getMenCount() {
        return $this->menCount;
    }

    /** @return int */
    public function getWomenCount() {
        return $this->womenCount;
    }

    /** @return int */
    public function getTotalCount() {
        return $this->totalCount;
    }

    private function calculateLocationCount() {

        // Array of genders [1, 0, 2, 1, 1, 1, 1, 0, ...]
        $genderVector = $this->calcGenderVector();
        foreach ($genderVector as $genderInt) {

            switch ($genderInt) {
                case EGender::FEMALE:
                    $this->womenCount++;
                    break;
                case EGender::MALE:
                    $this->menCount++;
                    break;
                default:
                    $this->totalCount++;
            }
        }

        $this->totalCount += $this->menCount + $this->womenCount;
    }

    /** @return int[] */
    private function calcGenderVector() {

        // Get the LocationUsers calculator
        $locationUsers = LocationModel::fromLocation($this->location)
            ->getLocationUsers();

        // Get all Ids from $calcResultLocationUsers->getUserId();
        $usersNowIds = $locationUsers->getUsersNowIds();

        // Get all the users associated with $userNowIds
        $accUsers = $locationUsers->getAccDbUsers();

        // Create the gender array
        $genderArray = [];

        foreach ($usersNowIds as $usersNowId)
            $genderArray[] = intval($accUsers[$usersNowId]->getGender());

        return $genderArray;
    }


}
