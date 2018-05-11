<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;
use Models\User\Accounts\UserLogin;
use Models\User\Accounts\UserPassword;
use Models\User\Accounts\UserRegistration;
use Api\User as ApiUser;
use Api\FormUserLogin as ApiFormUserLogin;
use Api\FormUserSocialLogin as ApiFormUserSocialLogin;
use Api\FormUserRegister as ApiFormUserRegister;
use Api\FormChangePassword as ApiFormChangePassword;


class ControllerAccounts {

    /** @return ApiUser */
    public function register(ApiFormUserRegister $apiFormUserRegister) {
        $user = UserRegistration::withCatchme($apiFormUserRegister);

        // The authenticated user is requesting his
        // own data, route to ControllerUser
        $controllerUser = new ControllerUser($user);
        return $controllerUser->get();
    }




    /** @return ApiUser */
    public function catchMeLogin(ApiFormUserLogin $formUserLogin) {
        $userLogin = new UserLogin();

        $userLogin->catchmeLogin($formUserLogin->email, $formUserLogin->password);

        // The authenticated user is requesting his
        // own data, route to ControllerUser
        $controllerUser = new ControllerUser($userLogin->getUser());
        return $controllerUser->getProfile();
    }


    /** @return ApiUser */
    public function facebookLogin(ApiFormUserSocialLogin $formUserSocialLogin) {
        $userLogin = new UserLogin();

        $userLogin->facebookLogin(new \FacebookToken($formUserSocialLogin->token));

        // The authenticated user is requesting his
        // own data, route to ControllerUser
        $controllerUser = new ControllerUser($userLogin->getUser());
        return $controllerUser->getProfile();
    }


    /** @return ApiUser */
    public function googleLogin(ApiFormUserSocialLogin $formUserSocialLogin) {
        $userLogin = new UserLogin();

        $userLogin->googleLogin(new \GoogleToken($formUserSocialLogin->token));

        // The authenticated user is requesting his
        // own data, route to ControllerUser
        $controllerUser = new ControllerUser($userLogin->getUser());
        return $controllerUser->getProfile();
    }


    /** @return int */
    public function changePassword(ApiFormChangePassword $apiFormUserRegister) {
        return UserPassword::change($apiFormUserRegister);
    }


}