<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Api\Map;
use Api\Sec\ConnectionPrivacyPolicy;
use Api\UserConnections;
use Api\UserLocations;
use Models\Calculators\Users\UserAdminLocationsResult;
use Models\Calculators\Users\UserConnectionsResult;
use Models\Calculators\Users\UserLocationsResult;
use Location as DbLocation;
use Models\Calculators\Users\UserLocationStatusResult;
use User as DbUser;
use Api\User as ApiUser;


class ModelToApiUser {


    public function __construct(DbUser $dbUser) {
        $this->requestedUser = $dbUser;
        $this->apiUser = clearObject(new ApiUser());
        $this->modelToApiLocations = ModelToApiLocations::multiple();
        $this->modelToApiUsers = ModelToApiUsers::multiple();
        $this->modelToApiUserLocationStatuses = ModelToApiUserLocations::multiple();
        $this->withBasicParameters();
    }


    /** @var DbUser $requestedUser */
    private $requestedUser;

    /** @var ModelToApiLocations $modelToApiLocations */
    private $modelToApiLocations;

    /** @var ModelToApiUsers $modelToApiUsers */
    private $modelToApiUsers;

    /** @var ModelToApiUserLocations $modelToApiUserLocationStatuses */
    private $modelToApiUserLocationStatuses;


    /** @var ApiUser */
    private $apiUser;
    public function get() { return $this->apiUser; }




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
     * @param UserAdminLocationsResult $userAdminLocationsResult
     * @return ModelToApiUser
     */
    public function withAdminLocations(UserAdminLocationsResult $userAdminLocationsResult) {

        $this->apiUser->adminLocations = $this->modelToApiLocations
            ->locations($userAdminLocationsResult->getAdminOfLocations());

        return $this;
    }


    /**
     * @param UserConnectionsResult $userConnections
     * @return ModelToApiUser
     */
    public function withConnections(UserConnectionsResult $userConnections) {
        $apiUserConnections = new UserConnections();

        $apiUserConnections->friends = $this->modelToApiUsers
            ->users($userConnections->getFriends());

        $apiUserConnections->requests = $this->modelToApiUsers
            ->users($userConnections->getRequests());

        $apiUserConnections->blocked = $this->modelToApiUsers
            ->users($userConnections->getBlocked());

        $this->apiUser->connections = $apiUserConnections;
        return $this;
    }


    /**
     * @param UserLocationsResult $userLocations
     * @return ModelToApiUser
     */
    public function withLocations(UserLocationsResult $userLocations) {
        $apiUserLocations = new UserLocations();

        $apiUserLocations->favorites = $userLocations->getFavorites();
        $apiUserLocations->top = $userLocations->getTop();

        $apiUserLocations->userLocationStatuses = $this->modelToApiUserLocationStatuses
            ->userLocationStatuses($userLocations->getUserLocationStatuses());

        $apiUserLocations->locations = $this->modelToApiLocations
            ->locations($userLocations->getLocations());

        $this->apiUser->locations = $apiUserLocations;

        return $this;
    }




}

