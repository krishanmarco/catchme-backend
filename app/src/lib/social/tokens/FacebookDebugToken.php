<?php

class FacebookDebugToken {
    const _FACEBOOK_CLIENT_ID = FACEBOOK_CLIENT_ID;
    const _FACEBOOK_CLIENT_APPLICATION_NAME = FACEBOOK_CLIENT_APPLICATION_NAME;

    const FACEBOOK_OAUTH_URL = "https://graph.facebook.com/v2.10/debug_token?input_token={0}&access_token={1}";

    // {
    //      "data": {
    //          "app_id": "1544975169114158",
    //          "application": "CatchMe",
    //          "expires_at": 1483012788,
    //          "is_valid": true,
    //          "issued_at": 1477828788,
    //          "scopes": [
    //              "user_birthday",
    //              "user_activities",
    //              "user_about_me",
    //              "email",
    //              "public_profile"
    //          ],
    //      "user_id": "559560534181085"
    //      }
    // }
    // ----------------------------------------------------------------------------------------------
    // {
    //      "error": {
    //          "message": "The access token could not be decrypted",
    //          "type": "OAuthException",
    //          "code": 190,
    //          "fbtrace_id": "BAXTpppqF8f"
    //      }
    // }


    public function __construct($tokenString) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => strtr(
                self::FACEBOOK_OAUTH_URL,
                ['{0}' => $tokenString, '{1}' => $tokenString]
            )
        ));
        $responseArray = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if (key_exists('error', $responseArray))
            $this->errorMessage = $responseArray['error']['message'];
        else $this->parseTokenJson($responseArray);
    }



    private $errorMessage;
    public function hasError() { return !is_null($this->errorMessage); }
    public function getErrorMessage() { return $this->errorMessage; }



    private $app_id;
    public function getAppId() { return $this->app_id; }

    private $application;
    public function getApplication() { return $this->application; }

    private $expires_at;
    public function getExpiresAt() { return $this->expires_at; }

    private $is_valid;
    public function getIsValid() { return $this->is_valid; }

    private $issued_at;
    public function getIssuedAt() { return $this->issued_at; }

    private $scopes;
    public function getScopes() { return $this->scopes; }

    private $user_id;
    public function getUserId() { return $this->user_id; }




    private $tokenAuthentic;

    public function isTokenAuthentic() {
        if (is_null($this->tokenAuthentic))
            $this->tokenAuthentic = $this->isTokenValid();
        return $this->tokenAuthentic;
    }




    private function parseTokenJson($jsonArray) {
        $this->app_id = $jsonArray['data']['app_id'];
        $this->application = $jsonArray['data']['application'];
        $this->expires_at = $jsonArray['data']['expires_at'];
        $this->is_valid = $jsonArray['data']['is_valid'];
        $this->issued_at = $jsonArray['data']['issued_at'];
        $this->scopes = $jsonArray['data']['scopes'];
        $this->user_id = $jsonArray['data']['user_id'];
    }




    private function isTokenValid() {
        if ($this->hasError())
            return false;

        if ($this->getAppId() != self::_FACEBOOK_CLIENT_ID)
            return false;

        if ($this->getApplication() !== self::_FACEBOOK_CLIENT_APPLICATION_NAME)
            return false;

        if (intval($this->getExpiresAt()) < time())
            return false;

        if (!$this->getIsValid())
            return false;

        return true;
    }


}