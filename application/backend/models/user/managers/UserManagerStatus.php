<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 14/09/2017 - Fithancer © */

namespace Models\User\Accounts;
use Api\UserLocationStatus;
use Propel\Runtime\Exception\PropelException;
use Slim\Exception\ApiException;
use User;
use UserLocation;
use UserLocationQuery;
use Api\UserLocationStatus as ApiUserLocationStatus;
use R;

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


        try {
            $userLocation->save();

        } catch (PropelException $e) {
            switch ($e->getCode()) {
                default: throw new ApiException(R::return_error_generic, $e);
            }
        }


        return $userLocation;
    }


    /** @return int */
    public function del($tid) {

        $userLocation = UserLocationQuery::create()
            ->filterByUserId($this->user->getId())
            ->findPk($tid);


        if (!is_null($userLocation))
            $userLocation->delete();
    }


}