<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 25/09/2017 - Fithancer © */

use \Firebase\JWT\JWT as FirebaseJWT;

abstract class UserAuthJWT {
    const _FIREBASE_LOGIN_SERVICE_CLIENT_EMAIL = FIREBASE_LOGIN_SERVICE_CLIENT_EMAIL;
    const _FIREBASE_LOGIN_SERVICE_CLIENT_PRIVATE_KEY = FIREBASE_LOGIN_SERVICE_PRIVATE_KEY;


    public static function build($userId) {
        return FirebaseJWT::encode([
            'iss' => self::_FIREBASE_LOGIN_SERVICE_CLIENT_EMAIL,
            'sub' => self::_FIREBASE_LOGIN_SERVICE_CLIENT_EMAIL,
            'aud' => 'https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit',
            'iat' => time(),
            'exp' => time() + 50 * 60,
            'uid' => $userId

        ], self::_FIREBASE_LOGIN_SERVICE_CLIENT_PRIVATE_KEY, 'RS256');
    }

}