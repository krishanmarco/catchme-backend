<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Api\Map;
use Api\LocationConnections;
use Api\LocationPeople;
use Location as DbLocation;
use User as DbUser;
use Api\Location as ApiLocation;
use Models\LocationFriendsResult;
use Models\LocationCountResult;
use Models\LocationImagesResult;


class ModelToApiLocation {

    public function __construct(DbLocation $dbLocation) {
        $this->dbLocation = $dbLocation;
        $this->apiLocation = clearObject(new ApiLocation());
        $this->modelToApiUsers = ModelToApiUsers::multiple();
        $this->withBasicParameters();
    }


    /** @var DbLocation $dbLocation */
    private $dbLocation;

    /** @var ModelToApiUsers $modelToApiUsers */
    private $modelToApiUsers;



    /** @var ApiLocation */
    private $apiLocation;
    public function get() { return $this->apiLocation; }




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
     * @param LocationImagesResult[] $locationImages
     * @return ModelToApiLocation
     */
    public function withImages(array $locationImages) {
        $urls = [];

        foreach ($locationImages as $li)
            array_push($urls, $li->url);

        $this->apiLocation->imageUrls = $urls;
        return $this;
    }


    /**
     * @param LocationCountResult $locationCount
     * @return ModelToApiLocation
     */
    public function withPeople(LocationCountResult $locationCount) {
        $apiLocationPeople = new LocationPeople();
        $apiLocationPeople->men = $locationCount->men;
        $apiLocationPeople->women = $locationCount->women;
        $apiLocationPeople->total = $locationCount->total;
        $this->apiLocation->people = $apiLocationPeople;
        return $this;
    }


    /**
     * @param LocationFriendsResult $locationFriends
     * @return ModelToApiLocation
     */
    public function withConnections(LocationFriendsResult $locationFriends) {
        $apiLocationConnections = new LocationConnections();

        $apiLocationConnections->past = [];

        $apiLocationConnections->future = $this->modelToApiUsers
            ->users($locationFriends->future);

        $apiLocationConnections->now = $this->modelToApiUsers
            ->users($locationFriends->now);

        $this->apiLocation->connections = $apiLocationConnections;
        return $this;
    }



}