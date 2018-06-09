<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\User\Accounts;
use User as DbUser;


abstract class UserAccountUtils {

    public static function hashPassword($inPassword, $inSalt) {
        $res = $inSalt . $inPassword;
        for($i = 0; $i < strlen($inSalt); $i++)
            $res = hash('sha256', $res);
        return $res;
    }

    /** @return DbUser */
    public static function setUserPassword(DbUser $user, $password) {
        $userPassSalt = getRandomString(7, 15);
        $userHash = UserAccountUtils::hashPassword($password, $userPassSalt);
        $user->setPassSalt($userPassSalt);
        $user->setPassSha256($userHash);
        return $user;
    }

}