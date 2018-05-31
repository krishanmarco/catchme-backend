<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 14/09/2017 - Fithancer © */

namespace Models\User\Accounts;
use \Propel\Runtime\Exception\PropelException;
use Slim\Exception\Api400;
use Slim\Http\UploadedFile;
use User as DbUser;
use Api\User as ApiUser;
use R;

class UserEditProfile {

    public function __construct(DbUser $dbUser) {
        $this->user = $dbUser;
    }


    /** @var DbUser $user */
    private $user;
    public function getUser() { return $this->user; }



    public function editFirebaseToken($firebaseToken) {

        try {
            $this->user->getSocial()->setFirebase($firebaseToken)->save();

        } catch (PropelException $exception) {
            switch ($exception->getCode()) {
                default: throw new Api400(R::return_error_generic);
            }
        }
    }


    /** @return UserEditProfile */
    public function userEdit(ApiUser $apiUser, $uploadedFile = null) {

        if (isset($apiUser->settingPrivacy))
            $this->user->setSettingPrivacy($apiUser->settingPrivacy);

        if (isset($apiUser->settingNotifications))
            $this->user->setSettingNotifications($apiUser->settingNotifications);

        if (isset($apiUser->phone))
            $this->user->setPhone($apiUser->phone);

        if (isset($apiUser->publicMessage))
            $this->user->setPublicMessage($apiUser->publicMessage);

        if ($uploadedFile instanceof UploadedFile) {
            $this->user->trySetAvatarFromFile($uploadedFile);
        }

        return $this;
    }


    /** @return UserEditProfile */
    public function superUserEdit(ApiUser $apiUser, $uploadedFile = null) {
        $this->userEdit($apiUser, $uploadedFile);

        if (isset($apiUser->name))
            $this->user->setName($apiUser->name);

        if (isset($apiUser->ban))
            $this->user->setBan($apiUser->ban);

        if (isset($apiUser->gender))
            $this->user->setGender($apiUser->gender);

        if (isset($apiUser->reputation))
            $this->user->setReputation($apiUser->reputation);


        return $this;
    }


    /** @return UserEditProfile */
    public function save() {
        $this->user->save();
        return $this;
    }

}