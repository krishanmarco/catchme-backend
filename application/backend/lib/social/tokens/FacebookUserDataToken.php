<?php

class FacebookUserDataToken {
    const FACEBOOK_OAUTH_URL = "https://graph.facebook.com/me?access_token={0}" .
        "&fields=id,birthday,email,first_name,gender,last_name,link,locale,name,timezone,updated_time,verified";

    // {
    //      "id": "559560534181085",
    //      "birthday": "07/30/1993",
    //      "email": "krishanmarco\u0040outlook.com",
    //      "first_name": "Krishan Marco",
    //      "gender": "male",
    //      "last_name": "Madan",
    //      "link": "https://www.facebook.com/app_scoped_user_id/559560534181085/",
    //      "locale": "en_US",
    //      "name": "Krishan Marco Madan",
    //      "timezone": 1,
    //      "updated_time": "2016-10-15T10:30:05+0000",
    //      "verified": true
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
                ['{0}' => $tokenString]
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



    private $id;
    public function getUserId() { return $this->id; }

    private $birthday;
    public function getBirthday() { return $this->birthday; }

    private $email;
    public function getEmail() { return $this->email; }

    private $first_name;
    public function getFirstName() { return $this->first_name; }

    private $gender;
    public function getGender() { return $this->gender; }

    private $last_name;
    public function getLastName() { return $this->last_name; }

    private $link;
    public function getLink() { return $this->link; }

    private $locale;
    public function getLocale() { return $this->locale; }

    private $name;
    public function getName() { return $this->name; }

    private $timezone;
    public function getTimezone() { return $this->timezone; }

    private $updated_time;
    public function getUpdatedTime() { return $this->updated_time; }

    private $verified;
    public function getVerified(){ return $this->verified; }

    public function getProfilePicture() {
        return strtr(
            "http://graph.facebook.com/{0}/picture?type=square&height=1024",
            ["{0}" => $this->getUserId()]
        );
    }

    private $tokenAuthentic;

    public function isTokenAuthentic() {
        if (is_null($this->tokenAuthentic))
            $this->tokenAuthentic = $this->isTokenValid();
        return $this->tokenAuthentic;
    }


    private function parseTokenJson($jsonArray) {
        $this->id = $jsonArray['id'];
        $this->birthday = $jsonArray['birthday'];
        $this->email = $jsonArray['email'];
        $this->first_name = $jsonArray['first_name'];
        $this->gender = $jsonArray['gender'];
        $this->last_name = $jsonArray['last_name'];
        $this->link = $jsonArray['link'];
        $this->locale = $jsonArray['locale'];
        $this->name = $jsonArray['name'];
        $this->timezone = $jsonArray['timezone'];
        $this->updated_time = $jsonArray['updated_time'];
        $this->verified = $jsonArray['verified'];
    }


    private function isTokenValid() {
        return !$this->hasError();
    }

}