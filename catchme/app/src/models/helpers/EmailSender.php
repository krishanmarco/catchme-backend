<?php /** Created by Krishan Marco Madan on 12-May-18. */

class EmailSender {

    public static function sendPasswordRecoveryEmail(User $user, $recoveryLink) {
        // todo
        mail(
            $user->getEmail(),
            "CATCHME RECOVERY",
            $recoveryLink
        );
    }

    public static function sendPasswordRecoveredEmail(User $user, $newPassword) {
        // todo
        mail(
            $user->getEmail(),
            "CATCHME PASSWORD",
            $newPassword
        );
    }

}