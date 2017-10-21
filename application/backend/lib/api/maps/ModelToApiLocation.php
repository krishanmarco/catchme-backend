<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Api\Map;
use Api\LocationAddress;
use Api\LocationConnections;
use Api\LocationPeople;
use Location as DbLocation;
use User as DbUser;
use Api\Location as ApiLocation;
use Models\Calculators\JoinedLocationUser\LocationFriendsResult;
use Models\Calculators\Locations\LocationCountResult;
use Models\Calculators\Locations\LocationImagesResult;


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
        $apiAddress = new LocationAddress();
        $apiAddress->country = $this->dbLocation->getAddress()->getCountry();
        $apiAddress->state = $this->dbLocation->getAddress()->getState();
        $apiAddress->city = $this->dbLocation->getAddress()->getCity();
        $apiAddress->town = $this->dbLocation->getAddress()->getTown();
        $apiAddress->postcode = $this->dbLocation->getAddress()->getPostcode();
        $apiAddress->address = $this->dbLocation->getAddress()->getAddress();
        $apiAddress->latLng = $this->dbLocation->getAddress()->getLatLng();
        $apiAddress->googlePlaceId = $this->dbLocation->getAddress()->getGooglePlaceId();
        $this->apiLocation->address = $apiAddress;
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
        $apiLocationPeople->men = $locationCount->getMenCount();
        $apiLocationPeople->women = $locationCount->getWomenCount();
        $apiLocationPeople->total = $locationCount->getTotalCount();
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
            ->users($locationFriends->getFuture());

        $apiLocationConnections->now = $this->modelToApiUsers
            ->users($locationFriends->getNow());

        $this->apiLocation->connections = $apiLocationConnections;
        return $this;
    }



}