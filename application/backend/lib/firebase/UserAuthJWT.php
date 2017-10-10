<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 25/09/2017 - Fithancer © */

use \Firebase\JWT\JWT as FirebaseJWT;

abstract class UserAuthJWT {
    const _FIREBASE_ADMIN_SERVICE_EMAIL = FIREBASE_ADMIN_SERVICE_EMAIL;
    const _FIREBASE_ADMIN_PRIVATE_KEY = FIREBASE_ADMIN_PRIVATE_KEY;


    public static function build($userId) {
        return FirebaseJWT::encode([
            'iss' => self::_FIREBASE_ADMIN_SERVICE_EMAIL,
            'sub' => self::_FIREBASE_ADMIN_SERVICE_EMAIL,
            'aud' => 'https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit',
            'iat' => time(),
            'exp' => time() + 50 * 60,
            'uid' => $userId

        ], self::_FIREBASE_ADMIN_PRIVATE_KEY, 'RS256');
    }

}