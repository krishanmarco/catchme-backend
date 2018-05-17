<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;
use Firebase\FeedItems\FeedItemUserAttendanceRequest;
use Firebase\FirebaseHelper;
use Api\Map\ModelToApiUserLocations;
use Firebase\FeedManager;
use Firebase\FeedItems\FeedItemFriendshipRequest;
use Firebase\FeaturedAdItems\FeaturedAdItemAttendanceRequest;
use Models\Calculators\UserModel;
use Models\Feed\MultiNotificationManager;
use Models\Location\Accounts\LocationEditProfile;
use Models\Location\Accounts\LocationRegistration;
use Models\User\Accounts\UserManagerConnections;
use Models\User\Accounts\UserEditProfile;
use Models\User\Accounts\UserManagerLocations;
use Models\User\Accounts\UserManagerStatus;
use Slim\Exception\Api500;
use User as DbUser;
use Api\Map\ModelToApiUsers;
use Api\User as ApiUser;
use Api\Location as ApiLocation;
use Api\FormLocationRegister as ApiFormLocationRegister;
use Api\UserLocationStatus as ApiUserLocationStatus;
use R;

class ControllerUser {
    
    public function __construct(DbUser $authUser) {
        $this->authUser = $authUser;
    }


    /** @var DbUser $authUser */
    private $authUser;



    /** @return ApiUser */
    public function get() {
        return ModelToApiUsers::single($this->authUser)
            ->withSecureData()
            ->get();
    }


    /** @return String */
    public function getJwt() {
        return FirebaseHelper::getUserFirebaseJWT($this->authUser->getId());
    }

    
    /** @return ApiUser */
    public function getProfile() {
        $userModel = UserModel::fromUser($this->authUser);
        return ModelToApiUsers::single($this->authUser)
            ->withSecureData()
            ->withPhone()
            ->withEmail()
            ->withAdminLocations($userModel->getUserAdminLocations()->getResult())
            ->withLocations($userModel->getUserLocations()->getResult())
            ->withConnections($userModel->getUserConnections()->getResult())
            ->get();
    }


    /** @return int */
    public function editProfileFirebase($firebaseToken) {
        $userEditProfile = new UserEditProfile($this->authUser);
        return $userEditProfile->editFirebaseToken($firebaseToken);
    }


    /** @return ApiUser */
    public function editProfile(ApiUser $newProfile, $uploadedFile = null) {
        $userEditProfile = new UserEditProfile($this->authUser);
        $userEditProfile->userEdit($newProfile, $uploadedFile)->save();
        return ModelToApiUsers::single($this->authUser)
            ->withSecureData()
            ->withPhone()
            ->withEmail()
            ->get();
    }


    /** @return ApiLocation */
    public function locationsAdministratingRegister(ApiFormLocationRegister $form, $uploadedFile = null) {
        $locationRegistration = new LocationRegistration($this->authUser);

        $locationRegistration->register($form, $uploadedFile);

        $locationController = new ControllerLocations(
            $this->authUser,
            $locationRegistration->getLocation()->getId()
        );

        return $locationController->get();
    }


    /** @return ApiLocation */
    public function locationsAdministratingEditLid(ApiLocation $apiLocation, $locationId, $uploadedFile = null) {
        $locationEditProfile = new LocationEditProfile($this->authUser, $locationId);

        $locationEditProfile->userEdit($apiLocation, $uploadedFile)->save();

        $locationController = new ControllerLocations(
            $this->authUser,
            $locationEditProfile->getLocation()->getId()
        );

        return $locationController->get();
    }




    public function connectionsAddUid($uid) {
        $manager = new UserManagerConnections($this->authUser);
        $manager->add($uid);

        // Add the notification item to firebase
        FeedManager::build($this->authUser)
            ->postSingleFeed(new FeedItemFriendshipRequest($this->authUser), $uid);
    }

    public function connectionsAcceptUid($uid) {
        $manager = new UserManagerConnections($this->authUser);
        $manager->accept($uid);
    }

    public function connectionsBlockUid($uid) {
        $manager = new UserManagerConnections($this->authUser);
        $manager->block($uid);
    }




    /** @return ApiUserLocationStatus[] */
    public function status() {
        $userLocationStatusList = UserModel::fromUser($this->authUser)
            ->getUserLocationStatus()->getResult()
            ->userLocationStatus;

        return ModelToApiUserLocations::multiple($userLocationStatusList);
    }

    /**
     * @param ApiUserLocationStatus $apiUserLocationStatus
     * @return ApiUserLocationStatus
     */
    public function statusAdd(ApiUserLocationStatus $apiUserLocationStatus) {
        $manager = new UserManagerStatus($this->authUser);

        $userLocationStatus = $manager->add($apiUserLocationStatus);


        $mfm = new MultiNotificationManager();

        // Add the notification item to firebase
        FeedManager::build($this->authUser)
            ->postMultipleFeeds(new FeedItemUserAttendanceRequest(
                $this->authUser,
                $userLocationStatus->getLocation()
            ), $mfm->getUidsInterestedInUser($this->authUser->getId()));

        return ModelToApiUserLocations::single($userLocationStatus)
            ->get();
    }

    /** @return int */
    public function statusDel($tid) {
        $manager = new UserManagerStatus($this->authUser);
        return $manager->del($tid);
    }




    /** @return int */
    public function locationsFavoritesAdd($lid) {
        $manager = new UserManagerLocations($this->authUser);
        $manager->add($lid);
        return R::return_ok;
    }

    /** @return int */
    public function locationsFavoritesDel($lid) {
        $manager = new UserManagerLocations($this->authUser);
        $manager->del($lid);
        return R::return_ok;
    }



}