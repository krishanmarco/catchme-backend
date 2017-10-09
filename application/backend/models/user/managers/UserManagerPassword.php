<?php

class PasswordManager {

    const RECOVERY_LINK_MIN_TTL = 30;
    const RECOVERY_LINK_URL_TEMPLATE = SERVER_URL . '/app/reset-password/{token}';
    const DB_KEY_EMAIL = 'email';
    const DB_KEY_RECOVERY_KEY = 'recoveryKey';


    public function sendRecoveryEmail($email) {
        $user = UserQuery::create()->findOneByEmail($email);

        // check if user exists
        if (is_null($user))
            return R::return_error_email_not_found;

        // User exists, send recovery email

        $tempVar = new SystemTempVar();

        $tempVar->setType(ESystemTempVar::passwordRecovery);
        $tempVar->setExpiryTs(time() + (self::RECOVERY_LINK_MIN_TTL * 60));


        $recoveryKey = getRandomString(15, 15);
        $tempVar->setData(DbPasswordRecovery::construct($user->getEmail(), $recoveryKey));

        $tempVar->save();

        $recoveryToken = RecoveryToken::fromValues(
            $tempVar->getId(),          // Database temp var id
            $recoveryKey,               // Random recovery key
            $user->getEmail(),          // User email (Unique)
            $user->getName(),           // User name (Needed for UI)
            $user->getSurname()         // User surname (Needed for UI)
        );

        $recoveryLink = strtr(
            self::RECOVERY_LINK_URL_TEMPLATE,
            ['{token}' => TokenBuilder::dataToToken($recoveryToken)]
        );


        $emailSender = new EmailSender($user->getEmail());
        $emailSender->sendPasswordRecoveryEmail(
            $user->getFullName(),
            $recoveryLink
        );


        return R::return_ok;
    }


    public function resetPasswordWithToken($email, $token, $newPassword) {

        $recoveryToken = RecoveryToken::fromToken(TokenBuilder::tokenToData($token));

        // todo validity check

        // Check if request is authentic
        $systemTempVar = SystemTempVarQuery::create()
            ->findOneById($recoveryToken->systemTempVarId);

        // Check if input system-temp-var-id exists
        if (is_null($systemTempVar))
            return R::return_error_incorrect_recovery_key;

        /** @var DbPasswordRecovery $dbRecoveryData**/
        $dbRecoveryData = $systemTempVar->getData();

        // Verify that the recovery key is correct
        if ($recoveryToken->recoveryKey != $dbRecoveryData->recoveryKey)
            return R::return_error_incorrect_recovery_key;
        if ($recoveryToken->email != $email)
            return R::return_error_incorrect_recovery_key;


        $systemTempVar->delete();

        // Recovery key is authentic, select the user
        // and set a new user password
        $user = UserQuery::create()->findOneByEmail($dbRecoveryData->email);

        $newPassword = UserAccountUtils::hashPassword($newPassword, $user->getPassSalt());
        $user->setPassSha256($newPassword);

        $user->save();

        return R::return_ok;
    }


    public function changePassword($uid, $oldPassword, $newPassword) {


        // Recovery key is authentic, select the user
        // and set a new user password
        $user = UserQuery::create()->findOneById($uid);

        if (UserAccountUtils::hashPassword($oldPassword, $user->getPassSalt()) != $user->getPassSha256())
            return R::return_error_incorrect_password;


        $newPassword = UserAccountUtils::hashPassword($newPassword, $user->getPassSalt());
        $user->setPassSha256($newPassword);

        $user->save();

        return R::return_ok;
    }

}


class RecoveryToken {

    public static function fromValues($systemTempVarId, $recoveryKey, $email, $name, $surname) {
        $recoveryToken = new RecoveryToken();
        $recoveryToken->systemTempVarId = $systemTempVarId;
        $recoveryToken->recoveryKey = $recoveryKey;
        $recoveryToken->email = $email;
        $recoveryToken->name = $name;
        $recoveryToken->surname = $surname;
        return $recoveryToken;
    }

    public static function fromToken($decryptedToken) {
        $recoveryToken = new RecoveryToken();
        $recoveryToken->systemTempVarId = $decryptedToken['systemTempVarId'];
        $recoveryToken->recoveryKey = $decryptedToken['recoveryKey'];
        $recoveryToken->email = $decryptedToken['email'];
        $recoveryToken->name = $decryptedToken['name'];
        $recoveryToken->surname = $decryptedToken['surname'];
        return $recoveryToken;
    }


    private function __construct() {
    }

    public $email;
    public $name;
    public $surname;
    public $systemTempVarId;
    public $recoveryKey;

}