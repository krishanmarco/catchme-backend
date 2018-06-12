<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Api\Map;

use Api\Sec\ConnectionPrivacyPolicy;
use Api\User as ApiUser;
use Api\UserConnections as ApiUserConnections;
use Api\UserLocations as ApiUserLocations;
use Models\Calculators\Users\UserAdminLocations;
use Models\Calculators\Users\UserConnections;
use Models\Calculators\Users\UserLocations;
use User as DbUser;

class ModelToApiUser {

    public function __construct(DbUser $dbUser) {
        $this->requestedUser = $dbUser;
        $this->apiUser = clearObject(new ApiUser());
        $this->modelToApiLocations = ModelToApiLocations::multiple();
        $this->modelToApiUsers = ModelToApiUsers::multiple();
        $this->modelToApiUserLocationStatuses = ModelToApiUserLocations::multiple();
        $this->withBasicParameters();
    }

    /** @var DbUser */
    private $requestedUser;

    /** @var ModelToApiLocations */
    private $modelToApiLocations;

    /** @var ModelToApiUsers */
    private $modelToApiUsers;

    /** @var ModelToApiUserLocations */
    private $modelToApiUserLocationStatuses;

    /** @var ApiUser */
    private $apiUser;

    public function get() {
        return $this->apiUser;
    }

    /** @return ModelToApiUser */
    public function applyPrivacyPolicy(DbUser $requestingUser) {
        $policy = new ConnectionPrivacyPolicy($requestingUser, $this->requestedUser);
        $this->apiUser = $policy->applyTo($this->apiUser);
        return $this;
    }

    /** @return ModelToApiUser */
    private function withBasicParameters() {
        $this->apiUser->id = $this->requestedUser->getId();
        $this->apiUser->name = $this->requestedUser->getName();
        $this->apiUser->gender = $this->requestedUser->getGender();
        $this->apiUser->reputation = $this->requestedUser->getReputation();
        $this->apiUser->publicMessage = $this->requestedUser->getPublicMessage();
        $this->apiUser->pictureUrl = $this->requestedUser->getPictureUrl();
        return $this;
    }

    /** @return ModelToApiUser */
    public function withSecureData() {
        $this->apiUser->apiKey = $this->requestedUser->getApiKey();
        $this->apiUser->ban = $this->requestedUser->getBan();
        $this->apiUser->settingPrivacy = $this->requestedUser->getSettingPrivacy();
        $this->apiUser->settingNotifications = $this->requestedUser->getSettingNotifications();
        $this->apiUser->signupTs = $this->requestedUser->getSignupTs();
        return $this;
    }

    /** @return ModelToApiUser */
    public function withPhone() {
        $this->apiUser->phone = $this->requestedUser->getPhone();
        return $this;
    }

    /** @return ModelToApiUser */
    public function withEmail() {
        $this->apiUser->email = $this->requestedUser->getEmail();
        return $this;
    }

    /**
     * @param UserAdminLocations $userAdminLocations
     * @return ModelToApiUser
     */
    public function withAdminLocations(UserAdminLocations $userAdminLocations) {

        $this->apiUser->adminLocations = $this->modelToApiLocations
            ->locations($userAdminLocations->getLocations());

        return $this;
    }

    /**
     * @param UserConnections $userConnections
     * @return ModelToApiUser
     */
    public function withConnections(UserConnections $userConnections) {
        $apiUserConnections = new ApiUserConnections();

        $apiUserConnections->friends = $this->modelToApiUsers
            ->users($userConnections->getUserFriends());

        $apiUserConnections->pending = $this->modelToApiUsers
            ->users($userConnections->getUserPending());

        $apiUserConnections->requests = $this->modelToApiUsers
            ->users($userConnections->getUserRequests());

        $apiUserConnections->blocked = $this->modelToApiUsers
            ->users($userConnections->getUserBlocked());

        $this->apiUser->connections = $apiUserConnections;
        return $this;
    }

    /**
     * @param UserLocations $userLocations
     * @return ModelToApiUser
     */
    public function withLocations(UserLocations $userLocations) {
        $apiUserLocations = new ApiUserLocations();

        $apiUserLocations->favorites = $userLocations->getFavorites();
        $apiUserLocations->top = $userLocations->getTop();

        $apiUserLocations->userLocationStatuses = $this->modelToApiUserLocationStatuses
            ->userLocationStatuses($userLocations->getUserLocations());

        $apiUserLocations->locations = $this->modelToApiLocations
            ->locations($userLocations->getLocations());

        $this->apiUser->locations = $apiUserLocations;

        return $this;
    }

}

