<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 */

namespace Controllers;

use Api\FormLocationRegister as ApiFormLocationRegister;
use Api\Location as ApiLocation;
use Api\Map\ModelToApiUserLocations;
use Api\Map\ModelToApiUsers;
use Api\User as ApiUser;
use Api\UserLocationStatus as ApiUserLocationStatus;
use Context\Context;
use Firebase\FeedItems\FeedItemUserAttendanceRequest;
use Firebase\FeedManager;
use Firebase\FirebaseHelper;
use Models\Calculators\UserModel;
use Models\Feed\MultiNotificationManager;
use Models\Location\Accounts\LocationEditProfile;
use Models\Location\Accounts\LocationRegistration;
use Models\User\Accounts\UserEditProfile;
use Models\User\Accounts\UserManagerConnections;
use Models\User\Accounts\UserManagerLocations;
use Models\User\Accounts\UserManagerStatus;
use R;
use User as DbUser;

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

    /** @return int */
    public function editProfileFirebase($firebaseToken) {
        $userEditProfile = new UserEditProfile($this->authUser);
        $userEditProfile->editFirebaseToken($firebaseToken);
        return R::return_ok;
    }

    /** @return ApiUser */
    public function getProfile() {
        $userModel = UserModel::fromUser($this->authUser);
        return ModelToApiUsers::single($this->authUser)
            ->withSecureData()
            ->withPhone()
            ->withEmail()
            ->withAdminLocations($userModel->getUserAdminLocations())
            ->withLocations($userModel->getUserLocations())
            ->withConnections($userModel->getUserConnections())
            ->get();
    }

    /** @return ApiUser */
    public function editProfile(ApiUser $newProfile, $uploadedFile = null) {
        $userEditProfile = new UserEditProfile($this->authUser);
        $userEditProfile->userEdit($newProfile, $uploadedFile, Context::getRequestLocale())->save();
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

    /** @return int */
    public function connectionsAddUid($uid) {
        $manager = new UserManagerConnections($this->authUser, $uid);
        $manager->add();
        return R::return_ok;
    }

    /** @return int */
    public function connectionsRemoveUid($uid) {
        $manager = new UserManagerConnections($this->authUser, $uid);
        $manager->del();
        return R::return_ok;
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

    /** @return ApiUserLocationStatus[] */
    public function status() {
        $userLocationStatusList = UserModel::fromUser($this->authUser)
            ->getUserLocationStatus()
            ->getUserLocations();

        return ModelToApiUserLocations::multiple()
            ->userLocations($userLocationStatusList);
    }

    /**
     * @param ApiUserLocationStatus $apiUserLocationStatus
     * @return ApiUserLocationStatus
     */
    public function statusAdd(ApiUserLocationStatus $apiUserLocationStatus) {
        $manager = new UserManagerStatus($this->authUser);

        $userLocationStatus = $manager->addOrEdit($apiUserLocationStatus);

        // Add the notification item to firebase
        FeedManager::build($this->authUser)
            ->postMultipleFeeds(new FeedItemUserAttendanceRequest(
                $this->authUser,
                $userLocationStatus->getLocation()
            ), MultiNotificationManager::uidsInterestedInUser($this->authUser->getId()));

        return ModelToApiUserLocations::single($userLocationStatus)
            ->get();
    }

    /** @return int */
    public function statusDel($tid) {
        $manager = new UserManagerStatus($this->authUser);
        $manager->del($tid);
        return R::return_ok;
    }

}