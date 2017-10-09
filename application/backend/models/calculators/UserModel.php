<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */


namespace Models\Calculators;
use Models\Calculators\Users\UserAdminLocations;
use Models\Calculators\Users\UserAdminLocationsResult;
use Models\Calculators\Users\UserSuggestedLocations;
use Models\Calculators\Users\UserSuggestedLocationsResult;
use Models\Calculators\Users\UserConnections;
use Models\Calculators\Users\UserConnectionsResult;
use Models\Calculators\Users\UserLocations;
use Models\Calculators\Users\UserLocationsResult;
use Models\Calculators\Users\UserSuggestedFriends;
use Models\Calculators\Users\UserSuggestedFriendsResult;
use User as User;
use Location as Location;
use UserQuery as UserQuery;


class UserModel {

    private static $UserModels = [
        // array of type {user-id} => UserModel
    ];


    /** @return UserModel */
    public static function fromId($userId) {
        return UserModel::fromUser(UserQuery::create()->findPk($userId));
    }


    /** @return UserModel */
    public static function fromUser(User $user) {

        if (array_key_exists($user->getId(), self::$UserModels))
            return self::$UserModels[$user->getId()];

        self::$UserModels[$user->getId()] = new UserModel($user);

        return self::$UserModels[$user->getId()];
    }




    private function __construct(User $user) {
        $this->user = $user;
    }

    /** @var User $user */
    private $user;
    public function getUser() { return $this->user; }



    /** @var UserConnectionsResult $userConnectionsResult */
    private $userConnectionsResult;

    public function getUserConnectionsResult() {
        if (is_null($this->userConnectionsResult)) {
            $userConnections = new UserConnections($this);
            $this->userConnectionsResult = $userConnections->execute();
        }

        return $this->userConnectionsResult;
    }



    /** @var UserLocationsResult $userLocationsResult */
    private $userLocationsResult;

    public function getUserLocationsResult() {
        if (is_null($this->userLocationsResult)) {
            $userLocations = new UserLocations($this);
            $this->userLocationsResult = $userLocations->execute();
        }

        return $this->userLocationsResult;
    }



    /** @var UserAdminLocationsResult $userAdminLocationsResult */
    private $userAdminLocationsResult;

    public function getUserAdminLocationsResult() {
        if (is_null($this->userAdminLocationsResult)) {
            $adminLocations = new UserAdminLocations($this);
            $this->userAdminLocationsResult = $adminLocations->execute();
        }

        return $this->userAdminLocationsResult;
    }




    /** @var UserSuggestedFriendsResult $userSuggestedFriendsResult */
    private $userSuggestedFriendsResult;

    public function getUserSuggestedFriendsResult($seed) {
        if (is_null($this->userSuggestedFriendsResult)) {
            $userSuggested = new UserSuggestedFriends($this, $seed);
            $this->userSuggestedFriendsResult = $userSuggested->execute();
        }

        return $this->userSuggestedFriendsResult;
    }



    /** @var UserSuggestedLocationsResult $userSuggestedLocationsResult */
    private $userSuggestedLocationsResult;

    public function getSuggestedLocationResult($seed) {
        if (is_null($this->userSuggestedLocationsResult)) {
            $locationSuggested = new UserSuggestedLocations($this, $seed);
            $this->userSuggestedLocationsResult = $locationSuggested->execute();
        }

        return $this->userSuggestedLocationsResult;
    }







}