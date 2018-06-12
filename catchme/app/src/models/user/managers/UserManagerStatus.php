<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 14/09/2017 */

namespace Models\User\Accounts;

use Api\UserLocationStatus as ApiUserLocationStatus;
use User as DbUser;
use UserLocation as DbUserLocation;
use UserLocationQuery;

class UserManagerStatus {

    public function __construct(DbUser $user) {
        $this->user = $user;
    }

    /** @var DbUser $user */
    private $user;

    /** @return DbUserLocation */
    public function add(ApiUserLocationStatus $apiUserLocationStatus) {
        $userLocation = new DbUserLocation();
        $userLocation->setUserId($this->user->getId());
        $userLocation->setLocationId($apiUserLocationStatus->locationId);
        $userLocation->setFromTs($apiUserLocationStatus->fromTs);
        $userLocation->setUntilTs($apiUserLocationStatus->untilTs);
        $userLocation->save();
        return $userLocation;
    }

    public function del($tid) {
        $userLocation = UserLocationQuery::create()
            ->filterByUserId($this->user->getId())
            ->findPk($tid);

        if (!is_null($userLocation))
            $userLocation->delete();
    }

}