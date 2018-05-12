<?php

namespace Models\User\Accounts;
use Slim\Exception\Api400;
use R;
use \Propel\Runtime\Exception\PropelException;
use Api\FormChangePassword as ApiFormChangePassword;
use UserQuery;
use User as DbUser;
use SystemTempVar as DbSystemTempVar;
use SystemTempVarQuery;
use ESystemTempVar;
use Security\DataEncrypter;
use EmailSender;

class UserManagerPassword {
    const RECOVERY_LINK_URL_TEMPLATE = SERVER_URL . '/accounts/user/{email}/password/reset/{token}';

    public static function change($uid, ApiFormChangePassword $form) {
        $pm = new UserManagerPassword(UserQuery::create()->findPk($uid));
        return $pm->changePassword($form);
    }

    public static function recover($email) {
        $pm = new UserManagerPassword(UserQuery::create()->findOneByEmail($email));
        return $pm->sendRecoveryEmail();
    }

    public static function reset($uid, $token) {
        $pm = new UserManagerPassword(UserQuery::create()->findPk($uid));
        return $pm->resetPasswordWithToken($token);
    }


    private function __construct(DbUser $user) {
        $this->user = $user;
    }

    /** @var DbUser $user */
    private $user;

    public function getUser() {
        return $this->user;
    }


    public function sendRecoveryEmail() {

        // check if user exists
        if (is_null($this->user))
            return R::return_error_user_not_found;

        // User exists, send recovery email

        $tempVar = new DbSystemTempVar();
        $tempVar->setType(ESystemTempVar::PASSWORD_RECO);
        $tempVar->setExpiryTs(time() + USER_PASSWORD_RECO_TTL);
        $tempVar->setData(RecoveryToken::fromValues(
            $tempVar->getId(),                      // Database temp var id
            getRandomString(15, 15),  // Random recovery key
            $this->user->getEmail()                // User email (Unique)
        ));
        $tempVar->save();

        $recoLink = strtr(
            self::RECOVERY_LINK_URL_TEMPLATE, [
                '{token}' => DataEncrypter::encrypt(json_encode($tempVar->getData())),
                '{email}' => $this->user->getEmail()
            ]
        );

        EmailSender::sendPasswordRecoveryEmail($this->user, $recoLink);

        return R::return_ok;
    }

    public function resetPasswordWithToken($token) {
        $userRecoToken = RecoveryToken::fromToken(DataEncrypter::decrypt($token));

        // Check if request is authentic
        $tempVar = SystemTempVarQuery::create()
            ->findOneById($userRecoToken->systemTempVarId);

        // Check if input system-temp-var-id exists
        if (is_null($tempVar))
            throw new Api400(R::return_error_incorrect_recovery_key);

        /** @var RecoveryToken $sysRecoToken */
        $sysRecoToken = $tempVar->getData();

        if ($userRecoToken->email != $sysRecoToken->email)
            throw new Api400(R::return_error_incorrect_recovery_key);

        if ($userRecoToken->recoveryKey != $sysRecoToken->recoveryKey)
            throw new Api400(R::return_error_incorrect_recovery_key);

        // The token is valid
        $randomPassword = getRandomString(15, 15);
        $this->adminChangePassword($randomPassword);

        // Password changed successfully, delete the temp var
        $tempVar->delete();

        // Send the new password to the user
        EmailSender::sendPasswordRecoveredEmail($this->user, $randomPassword);

        return R::return_ok;
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
        return $this->adminChangePassword($form->passwordNext);
    }

    public function adminChangePassword($newPassword) {
        $this->user = UserAccountUtils::setUserPassword($this->user, $newPassword);

        try {
            $this->user->save();

        } catch (PropelException $exception) {
            // Unknown Exception
            throw new Api400();
        }

        return R::return_ok;
    }

}


class RecoveryToken {

    public static function fromValues($systemTempVarId, $recoveryKey, $email) {
        $recoveryToken = new RecoveryToken();
        $recoveryToken->systemTempVarId = $systemTempVarId;
        $recoveryToken->recoveryKey = $recoveryKey;
        $recoveryToken->email = $email;
        return $recoveryToken;
    }

    public static function fromToken($decryptedToken) {
        $recoveryToken = new RecoveryToken();
        $recoveryToken->systemTempVarId = $decryptedToken['systemTempVarId'];
        $recoveryToken->recoveryKey = $decryptedToken['recoveryKey'];
        $recoveryToken->email = $decryptedToken['email'];
        return $recoveryToken;
    }


    private function __construct() { }

    public $email;
    public $systemTempVarId;
    public $recoveryKey;

}