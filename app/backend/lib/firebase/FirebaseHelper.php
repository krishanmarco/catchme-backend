<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 25/09/2017 - Fithancer © */

namespace Firebase;
use \Firebase\JWT\JWT as FirebaseJWT;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Kreait\Firebase;

abstract class FirebaseHelper {

    public static function getUserFirebaseJWT($userId) {
        return FirebaseJWT::encode([
            'iss' => FIREBASE_LOGIN_SERVICE_CLIENT_EMAIL,
            'sub' => FIREBASE_LOGIN_SERVICE_CLIENT_EMAIL,
            'aud' => 'https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit',
            'iat' => time(),
            'exp' => time() + 50 * 60,
            'uid' => $userId

        ], FIREBASE_LOGIN_SERVICE_PRIVATE_KEY, 'RS256');
    }

    /** @return Firebase */
    public static function getFirebaseConnection() {
        $serviceAccount = ServiceAccount::fromArray([
            'project_id' => FIREBASE_PROJECT_ID,
            'client_id' => FIREBASE_ADMIN_SERVICE_CLIENT_ID,
            'client_email' => FIREBASE_ADMIN_SERVICE_CLIENT_EMAIL,
            'private_key' => FIREBASE_ADMIN_SERVICE_PRIVATE_KEY
        ]);

        return (new Factory())
            ->withServiceAccountAndApiKey($serviceAccount, FIREBASE_API_KEY)
            ->withDatabaseUri(FIREBASE_DATABASE_URI)
            ->create();
    }

}