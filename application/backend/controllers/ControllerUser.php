<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;
use Api\Map\ModelToApiUserLocation;
use Api\Map\ModelToApiUserLocations;
use Firebase\FeedManager;
use Firebase\FeedItems\FeedItemFriendshipRequest;
use Firebase\FeedItems\FeedItemUserAttendanceRequest;
use Models\Calculators\UserModel;
use Models\Feed\MultiFeedManager;
use Models\Location\Accounts\LocationEditProfile;
use Models\Location\Accounts\LocationRegistration;
use Models\User\Accounts\UserManagerConnections;
use Models\User\Accounts\UserEditProfile;
use Models\User\Accounts\UserManagerLocations;
use Models\User\Accounts\UserManagerStatus;
use Slim\Exception\ApiException;
use User as DbUser;
use UserAuthJWT;
use Api\Map\ModelToApiUsers;
use Api\User as ApiUser;
use Api\Location as ApiLocation;
use Api\FormLocationRegister as ApiFormLocationRegister;
use Api\UserLocationStatus as ApiUserLocationStatus;
use R;

class ControllerUser {
    
    public function __construct(DbUser $authenticatedUser) {
        $this->authenticatedUser = $authenticatedUser;
        $this->userModel = UserModel::fromUser($this->authenticatedUser);
    }


    /** @var DbUser $authenticatedUser */
    private $authenticatedUser;

    /** @var UserModel $userModel */
    private $userModel;




    /** @return ApiUser */
    public function get() {
        return ModelToApiUsers::single($this->authenticatedUser)
            ->withSecureData()
            ->get();
    }


    /** @return String */
    public function getJwt() {
        return UserAuthJWT::build($this->userModel->getUser()->getId());
    }

    
    /** @return ApiUser */
    public function getProfile() {
        return ModelToApiUsers::single($this->authenticatedUser)
            ->withSecureData()
            ->withPhone()
            ->withEmail()
            ->withAdminLocations($this->userModel->getUserAdminLocationsResult())
            ->withLocations($this->userModel->getUserLocationsResult())
            ->withConnections($this->userModel->getUserConnectionsResult())
            ->get();
    }


    /** @return int */
    public function editProfileFirebase($firebaseToken) {
        $userEditProfile = new UserEditProfile($this->authenticatedUser);
        return $userEditProfile->editFirebaseToken($firebaseToken);
    }


    /** @return ApiUser */
    public function editProfile(ApiUser $newProfile) {
        $userEditProfile = new UserEditProfile($this->authenticatedUser);
        $userEditProfile->userEdit($newProfile)->save();
        return ModelToApiUsers::single($this->authenticatedUser)
            ->withSecureData()
            ->withPhone()
            ->withEmail()
            ->get();
    }


    /** @return ApiLocation */
    public function locationsAdministratingRegister(ApiFormLocationRegister $form) {
        $locationRegistration = new LocationRegistration($this->authenticatedUser);

        $locationRegistration->register($form);

        $locationController = new ControllerLocations(
            $this->authenticatedUser,
            $locationRegistration->getLocation()->getId()
        );

        return $locationController->get();
    }


    /** @return ApiLocation */
    public function locationsAdministratingEditLid(ApiLocation $apiLocation, $locationId) {
        $locationEditProfile = new LocationEditProfile($this->authenticatedUser, $locationId);
        $locationEditProfile->userEdit($apiLocation)->save();

        $locationController = new ControllerLocations(
            $this->authenticatedUser,
            $locationEditProfile->getLocation()
        );

        return $locationController->get();
    }




    public function connectionsAddUid($uid) {
        $manager = new UserManagerConnections($this->authenticatedUser);
        $manager->add($uid);

        // Add the notification item to firebase
        FeedManager::build($this->authenticatedUser)
            ->postSingleFeed(new FeedItemFriendshipRequest($this->authenticatedUser), $uid);
    }

    public function connectionsAcceptUid($uid) {
        $manager = new UserManagerConnections($this->authenticatedUser);
        $manager->accept($uid);
    }

    public function connectionsBlockUid($uid) {
        $manager = new UserManagerConnections($this->authenticatedUser);
        $manager->block($uid);
    }




    /** @return ApiUserLocationStatus[] */
    public function status() {
        $userLocationStatusList = $this->userModel
            ->getUserLocationStatusResult()
            ->getUserLocationStatusList();

        return ModelToApiUserLocations::multiple($userLocationStatusList);
    }

    /**
     * @param ApiUserLocationStatus $apiUserLocationStatus
     * @return ApiUserLocationStatus
     * @throws ApiException
     */
    public function statusAdd(ApiUserLocationStatus $apiUserLocationStatus) {
        $manager = new UserManagerStatus($this->authenticatedUser);

        $userLocationStatus = $manager->add($apiUserLocationStatus);


        $mfm = new MultiFeedManager($this->authenticatedUser);

        // Add the notification item to firebase
        FeedManager::build($this->authenticatedUser)
            ->postMultipleFeeds(new FeedItemUserAttendanceRequest(
                $this->authenticatedUser,
                $userLocationStatus->getLocation()
            ), $mfm->getNotifiableFriendIds());

        return ModelToApiUserLocations::single($userLocationStatus)
            ->get();
    }

    /** @return int */
    public function statusDel($timingId) {
        $manager = new UserManagerStatus($this->authenticatedUser);
        return $manager->del($timingId);
    }




    /** @return int */
    public function locationsFavoritesAdd($lid) {
        $manager = new UserManagerLocations($this->authenticatedUser);
        $manager->add($lid);
        return R::return_ok;
    }

    /** @return int */
    public function locationsFavoritesDel($lid) {
        $manager = new UserManagerLocations($this->authenticatedUser);
        $manager->del($lid);
        return R::return_ok;
    }



}