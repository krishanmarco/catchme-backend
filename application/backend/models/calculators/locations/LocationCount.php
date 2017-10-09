<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Locations;
use Models\Calculators\LocationModel;
use UserQuery as UserQuery;
use EGender;
use Map\UserTableMap as UserTableMap;


class LocationCount {

    public function __construct(LocationModel $LocationModel) {
        $this->LocationModel = $LocationModel;
    }


    /** @var LocationModel $LocationModel */
    private $LocationModel;
    public function getLocation() { return $this->LocationModel->getLocation(); }



    public function execute() {
        $menCount = 0;
        $womenCount = 0;
        $totalCount = 0;

        $genderVector = $this->calcGenderVector();

        foreach ($genderVector as $genderInt) {

            switch ($genderInt) {
                case EGender::FEMALE: $womenCount++; break;
                case EGender::MALE: $menCount++; break;
                default: $totalCount++;
            }
        }
        $totalCount += $menCount + $womenCount;

        return new LocationCountResult($menCount, $womenCount, $totalCount);
    }




    private function calcGenderVector() {

        // Get all Ids from $calcResultLocationUsers->getUserId();
        $idsNow = [];
        $locationUsersResult = $this->LocationModel->getLocationUsersResult();
        foreach ($locationUsersResult->getUsersNow() as $userLocation)
            array_push($idsNow, $userLocation->getUserId());


        // For each id as key, select its gender
        return UserQuery::create()
            ->select([UserTableMap::COL_GENDER])
            ->findPks($idsNow)
            ->getData();

    }


}




class LocationCountResult {

    /**
     * CalcResultLocationCount constructor.
     * @param Integer $men
     * @param Integer $women
     * @param Integer $total
     */
    public function __construct($men, $women, $total) {
        $this->men = $men;
        $this->women = $women;
        $this->total = $total;
    }

    /** @var Integer $men */
    private $men;
    public function getMenCount() { return $this->men; }

    /** @var Integer $women */
    private $women;
    public function getWomenCount() { return $this->women; }

    /** @var Integer $total */
    private $total;
    public function getTotalCount() { return $this->total; }


}