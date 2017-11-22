<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\User\Accounts;
use \Propel\Runtime\Exception\PropelException;
use Slim\Exception\ApiException;
use User as DbUser;
use R;
use FacebookToken;
use GoogleToken;
use Api\FormUserRegister as ApiFormUserRegister;
use FacebookTokenValidator;
use GoogleTokenValidator;

class UserRegistration {

    /** @return DbUser */
    public static function withCatchme(ApiFormUserRegister $form) {
        $ur = new UserRegistration();
        $ur->catchmeRegister($form);
        return $ur->getUser();
    }

    /** @return DbUser */
    public static function withFacebook(FacebookToken $facebookToken) {
        $ur = new UserRegistration();
        $ur->facebookRegister($facebookToken);
        return $ur->getUser();
    }

    /** @return DbUser */
    public static function withGoogle(GoogleToken $googleToken) {
        $ur = new UserRegistration();
        $ur->googleRegister($googleToken);
        return $ur->getUser();
    }




    private function __construct() {
        $this->user = new DbUser();
    }


    /** @var DbUser $user */
    private $user;

    public function getUser() {
        return $this->user;
    }




    public function catchmeRegister(ApiFormUserRegister $form) {
        $this->userRegister($form->name, $form->email, $form->password);
    }



    public function googleRegister(GoogleToken $googleToken) {

        $facebookTokenValidator = new GoogleTokenValidator($googleToken);
        $facebookTokenValidator->validate();

        $this->userRegister(
            $googleToken->getName(),
            $googleToken->getEmail(),
            getRandomString(15, 15)
        );
    }



    public function facebookRegister(FacebookToken $facebookToken) {

        $facebookTokenValidator = new FacebookTokenValidator($facebookToken);
        $facebookTokenValidator->validate();

        $this->userRegister(
            $facebookToken->facebookUserDataToken->getName(),
            $facebookToken->facebookUserDataToken->getEmail(),
            getRandomString(15, 15)
        );
    }


    private function userRegister($name, $email, $password) {
        $this->user->setName($name);
        $this->user->setEmail($email);
        $this->user->setSettingPrivacy('222');
        $this->user->setSettingNotifications('11111');
        $this->user->setApiKey(getRandomString(32, 32));

        $userPassSalt = getRandomString(7, 15);
        $userHash = UserAccountUtils::hashPassword($password, $userPassSalt);
        $this->user->setPassSalt($userPassSalt);
        $this->user->setPassSha256($userHash);


        try {
            $this->user->save();

        } catch (PropelException $exception) {
            switch ($exception->getCode()) {
                // duplicate entry, email already exists
                default: throw new ApiException(R::return_error_email_taken);
            }
        }
    }

}