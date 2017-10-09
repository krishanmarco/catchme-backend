<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\User\Accounts;


abstract class UserAccountUtils {

    public static function hashPassword($inPassword, $inSalt) {
        $res = $inSalt . $inPassword;
        for($i = 0; $i < strlen($inSalt); $i++)
            $res = hash('sha256', $res);
        return $res;
    }

}