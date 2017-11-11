<?php

class GoogleToken {
    const _GOOGLE_CLIENT_ID = GOOGLE_CLIENT_ID;
    const _GOOGLE_CLIENT_MOBILE_ID = GOOGLE_CLIENT_MOBILE_ID;

    const GOOGLE_OAUTH_USER_URL = "https://www.googleapis.com/oauth2/v3/userinfo?access_token={0}";
    const GOOGLE_OAUTH_TOKEN_URL = "https://www.googleapis.com/oauth2/v3/tokenInfo?access_token={0}";

    const GOOGLE_API_URLS = ['accounts.google.com', 'https://accounts.google.com'];
    // {
    //      "iss": "https://accounts.google.com",
    //      "iat": "1477834563",
    //      "exp": "1477838163",
    //      "aud": "515840590570-t2erd31vfstrff36u9ujg5orvimbdon1.apps.googleusercontent.com",
    //      "sub": "110773368710093854228",
    //      "email_verified": "true",
    //      "azp": "515840590570-ar4mt7732mdds1q50d938mglr3soe4g9.apps.googleusercontent.com",
    //      "email": "krishanmm93@gmail.com",
    //      "name": "Krishan Madan",
    //      "picture": "https://lh6/.../.../.../photo.jpg",
    //      "given_name": "Krishan",
    //      "family_name": "Madan",
    //      "locale": "en",
    //      "alg": "RS256",
    //      "kid": "f3ac035dfb99d1c6f12015c014555242317159df"
    // }
    // ----------------------------------------------------------------------------------------------
    // {
    //      "error_description": "Invalid Value"
    // }


    public function __construct($tokenString) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => strtr(
                self::GOOGLE_OAUTH_USER_URL,
                ['{0}' => $tokenString]
            )
        ));
        $data = curl_exec($curl);
        $responseArray = json_decode($data, true);
        curl_close($curl);

        if (key_exists('error_description', $responseArray))
            $this->error = $responseArray['error_description'];
        else $this->parseTokenJson($responseArray);
    }



    private $error;
    public function hasError() { return !is_null($this->error); }
    public function getError() { return $this->error; }


    private $iss;
    public function getIss() { return $this->iss; }

    private $iat;
    public function getIat() { return $this->iat; }

    private $exp;
    public function getExp() { return $this->exp; }

    private $aud;
    public function getAud() { return $this->aud; }

    private $sub;
    public function getSub() { return $this->sub; }

    private $email_verified;
    public function getEmailVerified() { return $this->email_verified; }

    private $azp;
    public function getAzp() { return $this->azp; }

    private $email;
    public function getEmail() { return $this->email; }

    private $name;
    public function getName() { return $this->name; }

    private $picture;
    public function getPicture() { return $this->picture; }

    private $given_name;
    public function getGivenName() { return $this->given_name; }

    private $family_name;
    public function getFamilyName() { return $this->family_name; }

    private $locale;
    public function getLocale() { return $this->locale; }

    private $alg;
    public function getAlg() { return $this->alg; }

    private $kid;
    public function getKid() { return $this->kid; }




    private $tokenAuthentic;

    public function isTokenAuthentic() {
        if (is_null($this->tokenAuthentic))
            $this->tokenAuthentic = $this->isTokenValid();
        return $this->tokenAuthentic;
    }




    private function parseTokenJson($jsonArray) {
        $this->iss = $jsonArray['iss'];
        $this->iat = $jsonArray['iat'];
        $this->exp = $jsonArray['exp'];
        $this->aud = $jsonArray['aud'];
        $this->sub = $jsonArray['sub'];
        $this->email_verified = $jsonArray['email_verified'];
        $this->azp = $jsonArray['azp'];
        $this->email = $jsonArray['email'];
        $this->name = $jsonArray['name'];
        $this->picture = $jsonArray['picture'];
        $this->given_name = $jsonArray['given_name'];
        $this->family_name = $jsonArray['family_name'];
        $this->locale = $jsonArray['locale'];
        $this->alg = $jsonArray['alg'];
        $this->kid = $jsonArray['kid'];
    }




    private function isTokenValid() {
        if ($this->hasError()) return false;


        // Verify token according to google guidelines
        //
        // 1) The ID token is properly signed by Google:    Use Google's public keys
        //      (available in JWK or PEM format) to verify the token's signature.
        // 2) The email of aud in the ID token is equal to one of your app's client IDs.
        //      This check is necessary to prevent ID tokens issued to a malicious app
        //      being used to access data about the same user on your app's backend server.
        // 3) The email of iss in the ID token is equal to accounts.google.com or https://accounts.google.com.
        // 4) The expiry time (exp) of the ID token has not passed.
        // 5) If your authentication request specified a hosted domain,
        //      the ID token has a hd claim that matches your Google Apps hosted domain.

        // Step 1 verification
        // if (!in_array($this->getKid(), self::GOOGLE_API_KEYS))
        //      return false;

        // Step 2 verification
        /*if ($this->getAud() != self::_GOOGLE_CLIENT_ID)
            return false;

        if ($this->getAzp() != self::_GOOGLE_CLIENT_MOBILE_ID)
            return false;

        // Step 3 verification
        if (!in_array($this->getIss(), self::GOOGLE_API_URLS))
            return false;

        // Step 4 verification
        if (intval($this->getExp()) < time())
            return false;
        */

        return true;
    }

}