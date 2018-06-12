<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators;

use Models\Calculators\Users\UserAdminLocations;
use Models\Calculators\Users\UserConnectionManager;
use Models\Calculators\Users\UserConnections;
use Models\Calculators\Users\UserLocations;
use Models\Calculators\Users\UserLocationStatus;
use Models\Calculators\Users\UserSuggestedFriends;
use Models\Calculators\Users\UserSuggestedLocations;
use User as DbUser;
use UserQuery;

class UserModel {

    private static $UserModels = [
        // array of type {uid} => UserModel
    ];

    /** @return UserModel */
    public static function fromId($uid) {
        if (!array_key_exists($uid, self::$UserModels)) {
            $user = UserQuery::create()->findPk(intval($uid));
            self::$UserModels[$uid] = new UserModel($user);
        }

        return self::$UserModels[$uid];
    }

    /** @return UserModel */
    public static function fromUser(DbUser $user) {
        if (!array_key_exists($user->getId(), self::$UserModels)) {
            self::$UserModels[$user->getId()] = new UserModel($user);
        }

        return self::$UserModels[$user->getId()];
    }

    private function __construct(DbUser $user) {
        $this->user = $user;
    }

    /** @var DbUser */
    private $user;

    /** @var UserConnectionManager */
    private $userConnectionManager;

    /** @var UserConnections */
    private $userConnections;

    /** @var UserLocations */
    private $userLocations;

    /** @var UserAdminLocations */
    private $userAdminLocations;

    /** @var UserSuggestedFriends */
    private $userSuggestedFriends;

    /** @var UserSuggestedLocations */
    private $userSuggestedLocations;

    /** @var UserLocationStatus */
    private $userLocationStatus;

    /** @return DbUser */
    public function getUser() {
        return $this->user;
    }

    /** @return UserConnectionManager */
    public function getUserConnectionManager() {
        if (is_null($this->userConnectionManager))
            $this->userConnectionManager = new UserConnectionManager($this->user);
        return $this->userConnectionManager;
    }

    /** @return UserConnections */
    public function getUserConnections() {
        if (is_null($this->userConnections))
            $this->userConnections = new UserConnections($this->user);
        return $this->userConnections;
    }

    /** @return UserLocations */
    public function getUserLocations() {
        if (is_null($this->userLocations))
            $this->userLocations = new UserLocations($this->user);
        return $this->userLocations;
    }

    /** @return UserAdminLocations */
    public function getUserAdminLocations() {
        if (is_null($this->userAdminLocations))
            $this->userAdminLocations = new UserAdminLocations($this->user);
        return $this->userAdminLocations;
    }

    /** @return UserSuggestedFriends */
    public function getUserSuggestedFriends($seed) {
        if (is_null($this->userSuggestedFriends))
            $this->userSuggestedFriends = new UserSuggestedFriends($this->user, $seed);
        return $this->userSuggestedFriends;
    }

    /** @return UserSuggestedLocations */
    public function getUserSuggestedLocations($seed, $userPosition = null) {
        if (is_null($this->userSuggestedLocations))
            $this->userSuggestedLocations = new UserSuggestedLocations($this->user, $seed, $userPosition);
        return $this->userSuggestedLocations;
    }

    /** @return UserLocationStatus */
    public function getUserLocationStatus() {
        if (is_null($this->userLocationStatus))
            $this->userLocationStatus = new UserLocationStatus($this->user);
        return $this->userLocationStatus;
    }

}