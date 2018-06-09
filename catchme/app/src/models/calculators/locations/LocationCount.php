<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Locations;

use Location as DbLocation;
use Models\Calculators\LocationModel;
use UserQuery;
use EGender;
use Map\UserTableMap as UserTableMap;
use Models\LocationCountResult;

class LocationCount {

    public function __construct(DbLocation $location) {
        $this->location = $location;
        $this->calculateLocationCount();
    }

    /** @var DbLocation */
    private $location;

    /** @var LocationCountResult */
    private $result;

    /** @return LocationCountResult */
    public function getResult() {
        return $this->result;
    }

    private function calculateLocationCount() {
        $menCount = 0;
        $womenCount = 0;
        $totalCount = 0;

        $genderVector = $this->calcGenderVector();

        foreach ($genderVector as $genderInt) {

            switch ($genderInt) {
                case EGender::FEMALE:
                    $womenCount++;
                    break;
                case EGender::MALE:
                    $menCount++;
                    break;
                default:
                    $totalCount++;
            }
        }

        $totalCount += $menCount + $womenCount;

        $this->result = new LocationCountResult($menCount, $womenCount, $totalCount);
    }

    /** @return int[] */
    private function calcGenderVector() {
        // Get all Ids from $calcResultLocationUsers->getUserId();
        $idsNow = [];

        $locationModel = LocationModel::fromLocation($this->location);
        $locationUsersResult = $locationModel->getLocationUsers()->getResult();
        foreach ($locationUsersResult->usersNow as $userLocation)
            array_push($idsNow, $userLocation->getUserId());

        // For each id as key, select its gender
        return UserQuery::create()
            ->select([UserTableMap::COL_GENDER])
            ->findPks($idsNow)
            ->getData();
    }


}
