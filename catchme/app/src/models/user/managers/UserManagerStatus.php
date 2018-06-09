<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 14/09/2017 */

namespace Models\User\Accounts;
use Api\UserLocationStatus;
use Propel\Runtime\Exception\PropelException;
use Slim\Exception\Api400;
use User;
use UserLocation;
use UserLocationQuery;
use Api\UserLocationStatus as ApiUserLocationStatus;

class UserManagerStatus {

    public function __construct(User $user) {
        $this->user = $user;
    }

    /** @var User $user */
    private $user;

    /** @return UserLocation */
    public function add(ApiUserLocationStatus $apiUserLocationStatus) {
        $userLocation = new UserLocation();
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