<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Api\Map;

use Api\ApiMetadata;
use Api\ApiResponseWithMetadata;
use Api\Sec\ConnectionPrivacyPolicy;
use Api\User as ApiUser;
use Models\Calculators\Users\UserAdminLocations;
use Models\Calculators\Users\UserConnections;
use Models\Calculators\Users\UserLocations;
use User as DbUser;

class ModelToApiUser extends ApiResponseWithMetadata {

    public function __construct(DbUser $dbUser) {
        parent::__construct();
        $this->requestedUser = $dbUser;
        $this->apiUser = clearObject(new ApiUser());
        $this->apiUser->metadata = new ApiMetadata();
        $this->modelToApiLocations = ModelToApiLocations::multiple();
        $this->modelToApiUsers = ModelToApiUsers::multiple();
        $this->modelToApiUserLocations = ModelToApiUserLocations::multiple();
        $this->withBasicParameters();
    }

    /** @var DbUser */
    private $requestedUser;

    /** @var ModelToApiLocations */
    private $modelToApiLocations;

    /** @var ModelToApiUsers */
    private $modelToApiUsers;

    /** @var ModelToApiUserLocations */
    private $modelToApiUserLocations;

    /** @var ApiUser */
    private $apiUser;

    public function get() {
        $this->apiUser = $this->setMetadataAndGet($this->apiUser);
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
        $this->apiUser->locationsAdminIds = $userAdminLocations->getLocationIds();
        $this->metadataAddLocations($userAdminLocations->getAccDbLocations());
        return $this;
    }

    /**
     * @param UserConnections $userConnections
     * @return ModelToApiUser
     */
    public function withConnections(UserConnections $userConnections) {
        $this->apiUser->connectionsFriendIds = $userConnections->getUserFriendIds();
        $this->apiUser->connectionsPendingIds = $userConnections->getUserPendingIds();
        $this->apiUser->connectionsRequestIds = $userConnections->getUserRequestIds();
        $this->apiUser->connectionsBlockedIds = $userConnections->getUserBlockedIds();
        $this->metadataAddUsers($userConnections->getAccDbUsers());
        return $this;
    }

    /**
     * @param UserLocations $userLocations
     * @return ModelToApiUser
     */
    public function withLocations(UserLocations $userLocations) {
        $this->apiUser->locationsFavoriteIds = $userLocations->getFavoriteIds();
        $this->apiUser->locationsTopIds = $userLocations->getTopIds();
        $this->apiUser->locationsUserLocationIds = $userLocations->getUserLocationIds();
        $this->metadataAddLocations($userLocations->getAccDbLocations());
        $this->metadataAddUserLocations($userLocations->getAccDbUserLocations());
        return $this;
    }

}

