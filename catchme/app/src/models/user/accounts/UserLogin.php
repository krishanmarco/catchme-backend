<?php

namespace Models\User\Accounts;

use FacebookToken;
use FacebookTokenValidator;
use GoogleToken;
use GoogleTokenValidator;
use R;
use Slim\Exception\Api400;
use User as DbUser;

class UserLogin {

    /** @var DbUser $user */
    private $user;

    public function getUser() { return $this->user; }


    public function catchmeLogin($email, $password) {
        $uv = new UserLoginValidations($email);
        $uv->checkExistsBannedAndPassword($password);
        $this->user = $uv->getUser();
    }


    public function googleLogin(GoogleToken $googleToken) {

        $googleTokenValidator = new GoogleTokenValidator($googleToken);
        $googleTokenValidator->validate();


        // The token is valid, get the users email
        $uv = new UserLoginValidations($googleTokenValidator->getToken()->getEmail());

        try {
            $uv->checkExistsAndBanned();

        } catch (Api400 $e) {

            if ($e->getCode() == R::return_error_user_not_found) {
                // This user doesn't exist, use the token to sign up
                $this->user = UserRegistration::withGoogle($googleToken);
                return;
            }

            throw $e;
        }

        // The user exists and has not been banned
        $this->user = $uv->getUser();
    }


    public function facebookLogin(FacebookToken $facebookToken) {

        $facebookTokenValidator = new FacebookTokenValidator($facebookToken);
        $facebookTokenValidator->validate();


        // The token is valid, get the users email
        $uv = new UserLoginValidations($facebookTokenValidator->getUserDataToken()->getEmail());

        try {
            $uv->checkExistsAndBanned();

        } catch (Api400 $e) {

            if ($e->getCode() == R::return_error_user_not_found) {
                // This user doesn't exist, use the token to sign up
                $this->user = UserRegistration::withFacebook($facebookToken);
                return;
            }

            throw $e;
        }

        // The user exists and has not been banned
        $this->user = $uv->getUser();
    }


}