<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 */

namespace Controllers;

use Models\User\Accounts\UserLogin;
use Models\User\Accounts\UserManagerPassword;
use Models\User\Accounts\UserRegistration;
use Api\User as ApiUser;
use Api\FormUserLogin as ApiFormUserLogin;
use Api\FormUserSocialLogin as ApiFormUserSocialLogin;
use Api\FormUserRegister as ApiFormUserRegister;
use Api\FormChangePassword as ApiFormChangePassword;
use R;

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
    public function changePassword($uid, ApiFormChangePassword $apiFormUserRegister) {
        UserManagerPassword::change($uid, $apiFormUserRegister);
        return R::return_ok;
    }

    /** @return int */
    public function recoverPassword($email) {
        UserManagerPassword::recover($email);
        return R::return_ok;
    }

    /** @return int */
    public function resetPassword($uid, $token) {
        UserManagerPassword::reset($uid, $token);
        return R::return_ok;
    }

}