<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer 1.0 © */

namespace Mobile\Auth;
use Security\DataEncrypter;
use User;
use UserQuery;


class Authentication {

    function __construct($encryptedAuthToken) {
        $this->encryptedAuthToken = $encryptedAuthToken;
    }

    /** @var string $encryptedAuthToken */
    private $encryptedAuthToken;


    /** @var UserAuthentication $parsedToken */
    private $parsedToken;

    public function getParsedToken() {
        return $this->parsedToken;
    }


    public function authenticate() {

        // The encryptedHttpAuthentication was encrypted from the client
        // using the public RSA corresponding to self::RSA_PRIVATE_KEY
        $authToken = DataEncrypter::privateDecryptStr($this->encryptedAuthToken);

        // if $encryptedAuthToken is valid,
        // the decryption result will be a valid json
        $authToken = json_decode($authToken, JSON_OBJECT_AS_ARRAY);
        if (is_null($authToken))
            return false;

        // JSON is valid, check if the correct data-structure was used
        if (!(array_key_exists('id', $authToken) && array_key_exists('key', $authToken)))
            return false;

        // Token has been decrypted successfully, wrap it in a class
        $this->parsedToken = new UserAuthentication($authToken['id'], $authToken['key']);

        // Return success
        return true;
    }


}





class MobileUserAuth {

    // Rounds the current time to the closest
    // range of AUTH_TOKEN_MIN_TTL minutes
    public static function getAuthTokenTime() {
        // Get the current time in seconds
        $time = time();

        // Convert the AUTH_TOKEN_MIN_TTL minutes
        // to minutes and get the remainder its
        // the division with $time
        $remainder = $time % AUTH_TOKEN_TIME_TO_LIVE_SECONDS;

        // round $time to AUTH_TOKEN_MIN_TTL
        $time = $time - $remainder;

        return strval($time);
    }

    public static function buildTokenObj($id, $key) {
        return new UserAuthentication($id, md5(self::getAuthTokenTime() . $key));
    }

    public static function buildTokenStr($id, $key) {
        return DataEncrypter::publicEncryptStr(json_encode(self::buildTokenObj($id, $key)));
    }




    public function __construct($encryptedAuthToken) {
        $this->authenticator = new Authentication($encryptedAuthToken);
    }

    /** @var Authentication $authenticator */
    private $authenticator;



    /** @var User $verifiedUser */
    private $verifiedUser;

    public function getVerifiedUser() {
        return $this->verifiedUser;
    }




    public function authenticate() {
        if (!$this->authenticator->authenticate())
            return false;

        // Authentication was successful, get the authentication Token
        $authToken = $this->authenticator->getParsedToken();

        // Check if the $authToken is == -1, the user
        // id has to be > 0 to use this authenticator
        if ($authToken->id <= 0)
            return false;


        // Use the id in the request to fetch the expected user object
        $user = UserQuery::create()->findPk(intval($authToken->id));

        // A user with that id was not found
        if (is_null($user))
            return false;


        // The user was found
        // Recreate the user token using this requests http
        // header date string and the decared users api-key;
        $localMD5 = self::buildTokenObj($authToken->id, $user->getApiKey())->key;


        // Verify users token
        if (strtoupper($localMD5) != strtoupper($authToken->key))
            return false;

        // The request is verified successfully
        // Save the user as verified
        $this->verifiedUser = $user;


        return true;
    }

}









class UserAuthentication {

    public function __construct($id, $key) {
        $this->id = $id;
        $this->key = $key;
    }

    /** @var int $id */
    public $id;
    /** @var string $key */
    public $key;

}