<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Locations;

use EGender;
use Location as DbLocation;
use Map\UserTableMap as UserTableMap;
use Models\Calculators\LocationModel;
use UserLocation as DbUserLocation;
use UserQuery;

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

        // Get all Ids from $calcResultLocationUsers->getUserId();
        $usersNow = LocationModel::fromLocation($this->location)
            ->getLocationUsers()
            ->getUsersNow();

        // Map UserLocation[] to user id int[]
        $idsNow = DbUserLocation::mapToUserIds($usersNow);

        // For each id as key, select its gender
        return UserQuery::create()
            ->select([UserTableMap::COL_GENDER])
            ->findPks($idsNow)
            ->getData();
    }


}
