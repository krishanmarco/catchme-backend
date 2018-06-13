<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Api\Map;

use Api\ApiMetadata;
use Api\ApiResponseWithMetadata;
use Api\Location as ApiLocation;
use Location as DbLocation;
use LocationImage as DbLocationImage;
use Models\Calculators\JoinedLocationUser\LocationFriends;
use Models\Calculators\Locations\LocationCount;
use Models\Calculators\Locations\LocationImages;
use User as DbUser;

class ModelToApiLocation extends ApiResponseWithMetadata {

    public function __construct(DbLocation $dbLocation) {
        parent::__construct();
        $this->dbLocation = $dbLocation;
        $this->apiLocation = clearObject(new ApiLocation());
        $this->apiLocation->metadata = new ApiMetadata();
        $this->modelToApiUsers = ModelToApiUsers::multiple();
        $this->withBasicParameters();
    }

    /** @var DbLocation $dbLocation */
    private $dbLocation;

    /** @var ModelToApiUsers $modelToApiUsers */
    private $modelToApiUsers;

    /** @var ApiLocation */
    private $apiLocation;

    /** @return ApiLocation */
    public function get() {
        $this->apiLocation = $this->setMetadataAndGet($this->apiLocation);
        return $this->apiLocation;
    }

    /** @return ModelToApiLocation */
    public function applyPrivacyPolicy(DbUser $requestingUser) {
        $this->modelToApiUsers->applyPrivacyPolicy($requestingUser);
        return $this;
    }

    /** @return ModelToApiLocation */
    private function withBasicParameters() {
        $this->apiLocation->id = $this->dbLocation->getId();
        $this->apiLocation->adminId = $this->dbLocation->getAdminId();
        $this->apiLocation->signupTs = $this->dbLocation->getSignupTs();
        $this->apiLocation->verified = $this->dbLocation->getVerified();
        $this->apiLocation->name = $this->dbLocation->getName();
        $this->apiLocation->description = $this->dbLocation->getDescription();
        $this->apiLocation->capacity = $this->dbLocation->getCapacity();
        $this->apiLocation->pictureUrl = $this->dbLocation->getPictureUrl();
        $this->apiLocation->reputation = $this->dbLocation->getReputation();
        $this->apiLocation->email = $this->dbLocation->getEmail();
        $this->apiLocation->phone = $this->dbLocation->getPhone();
        $this->apiLocation->timings = $this->dbLocation->getTimings();
        return $this;
    }

    /** @return ModelToApiLocation */
    public function withAddress() {
        $this->apiLocation->country = $this->dbLocation->getAddress()->getCountry();
        $this->apiLocation->state = $this->dbLocation->getAddress()->getState();
        $this->apiLocation->city = $this->dbLocation->getAddress()->getCity();
        $this->apiLocation->town = $this->dbLocation->getAddress()->getTown();
        $this->apiLocation->postcode = $this->dbLocation->getAddress()->getPostcode();
        $this->apiLocation->address = $this->dbLocation->getAddress()->getAddress();
        $this->apiLocation->latLng = $this->dbLocation->getAddress()->getLatLng();
        $this->apiLocation->googlePlaceId = $this->dbLocation->getAddress()->getGooglePlaceId();
        return $this;
    }

    /** @return ModelToApiLocation */
    public function withTimings() {
        $this->apiLocation->timings = $this->dbLocation->getTimings();;
        return $this;
    }

    /**
     * @param LocationImages $locationImages
     * @return ModelToApiLocation
     */
    public function withImages(LocationImages $locationImages) {
        $this->apiLocation->imageUrls = DbLocationImage::mapToUrls($locationImages->getLocationImages());
        return $this;
    }

    /**
     * @param LocationCount $locationCount
     * @return ModelToApiLocation
     */
    public function withPeople(LocationCount $locationCount) {
        $this->apiLocation->peopleMenCount = $locationCount->getMenCount();
        $this->apiLocation->peopleWomenCount = $locationCount->getWomenCount();
        $this->apiLocation->peopleTotalCount = $locationCount->getTotalCount();
        return $this;
    }

    /**
     * @param LocationFriends $locationFriends
     * @return ModelToApiLocation
     */
    public function withConnections(LocationFriends $locationFriends) {
        $this->apiLocation->connectionsPastIds = [];
        $this->apiLocation->connectionsNowIds = $locationFriends->getFriendsNowIds();
        $this->apiLocation->connectionsFutureIds = $locationFriends->getFriendsLaterIds();
        $this->metadataAddUsers($locationFriends->getAccDbUsers());
        return $this;
    }


}