<?php

namespace Models\User\Accounts;

use Models\Email\EmailPasswordRecovered;
use Models\RecoveryToken;
use Slim\Exception\Api400;
use R;
use Api\FormChangePassword as ApiFormChangePassword;
use UserQuery;
use User as DbUser;
use SystemTempVar as DbSystemTempVar;
use SystemTempVarQuery;
use ESystemTempVar;
use Security\DataEncrypter;
use Models\Email\EmailPasswordRecovery;

class UserManagerPassword {
    const RECOVERY_LINK_URL_TEMPLATE = URL_API . '/accounts/user/{uid}/password/reset?token={token}';

    public static function change($uid, ApiFormChangePassword $form) {
        $pm = new UserManagerPassword(UserQuery::create()->findPk($uid));
        $pm->changePassword($form);
    }

    public static function recover($email) {
        $pm = new UserManagerPassword(UserQuery::create()->findOneByEmail($email));
        $pm->sendRecoveryEmail();
    }

    public static function reset($uid, $token) {
        $pm = new UserManagerPassword(UserQuery::create()->findPk($uid));
        $pm->resetPasswordWithToken($token);
    }


    private function __construct(DbUser $user) {
        $this->user = $user;
    }

    /** @var DbUser $user */
    private $user;

    public function sendRecoveryEmail() {

        // check if user exists
        if (is_null($this->user))
            throw new Api400(R::return_error_user_not_found);

        // User exists, send recovery email
        $recoTkn = RecoveryToken::fromValues(getRandomString(15, 15), $this->user->getEmail());
        $recoTkn = DbSystemTempVar::saveRecoTkn($recoTkn);

        $recoLink = strtr(self::RECOVERY_LINK_URL_TEMPLATE, [
            '{token}' => urlencode(DataEncrypter::publicEncryptStr(json_encode($recoTkn))),
            '{uid}' => $this->user->getId()
        ]);

        EmailPasswordRecovery::sendEmail($this->user, $recoLink);
    }

    public function resetPasswordWithToken($token) {
        $tokenStr = DataEncrypter::privateDecryptStr($token);
        $userRecoToken = RecoveryToken::fromTokenStr($tokenStr);

        // Check if request is authentic
        $tempVar = SystemTempVarQuery::create()
            ->findOneById($userRecoToken->systemTempVarId);

        // Check if input system-temp-var-id exists
        if (is_null($tempVar))
            throw new Api400(R::return_error_incorrect_recovery_key);

        /** @var RecoveryToken $recoTkn */
        $recoTkn = $tempVar->getData();

        if ($userRecoToken->email != $recoTkn->email)
            throw new Api400(R::return_error_incorrect_recovery_key);

        if ($userRecoToken->recoveryKey != $recoTkn->recoveryKey)
            throw new Api400(R::return_error_incorrect_recovery_key);

        // The token is valid
        $randomPassword = getRandomString(15, 15);
        $this->changeAndSave($randomPassword);

        // Password changed successfully, delete the temp var
        $tempVar->delete();

        // Send the new password to the user
        EmailPasswordRecovered::sendEmail($this->user, $randomPassword);
    }

    public function changePassword(ApiFormChangePassword $form) {
        // Check if the old password is correct
        $ulv = new UserLoginValidations($this->user->getEmail());
        $ulv->checkExistsAndPassword($form->passwordPrevious);

        // The old-password is correct
        // Check if new password is == new password confirm
        if ($form->passwordNext != $form->passwordConfirmNext)
            throw new Api400(R::return_error_passwords_not_equal);

        // The new password is == new password confirm
        $this->changeAndSave($form->passwordNext);
    }

    private function changeAndSave($newPassword) {
        $this->user = UserAccountUtils::setUserPassword($this->user, $newPassword);
        $this->user->save();
    }

}
