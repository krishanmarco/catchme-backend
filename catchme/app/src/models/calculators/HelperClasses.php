<?php /** Created by Krishan Marco Madan on 17-May-18. */

namespace Models;

class RecoveryToken {

    public static function fromValues($recoveryKey, $email) {
        $recoveryToken = new RecoveryToken();
        $recoveryToken->recoveryKey = $recoveryKey;
        $recoveryToken->email = $email;
        return $recoveryToken;
    }

    public static function fromTokenStr($decryptedToken) {
        $decryptedToken = json_decode($decryptedToken, true);
        $recoveryToken = new RecoveryToken();
        $recoveryToken->email = $decryptedToken['email'];
        $recoveryToken->systemTempVarId = $decryptedToken['systemTempVarId'];
        $recoveryToken->recoveryKey = $decryptedToken['recoveryKey'];
        return $recoveryToken;
    }


    private function __construct() {
        // Only static construction
    }

    public $email;
    public $systemTempVarId;
    public $recoveryKey;

}


class LatLng {

    public static function dist(array $xy1, array $xy2) {
        return sqrt(pow($xy1[0] - $xy2[0], 2) + pow($xy1[1] - $xy2[1], 2));
    }

    public static function distToWeight1(array $xy1, array $xy2) {
        $dist = self::dist($xy1, $xy2);

        if ($dist == 0)
            return 1;

        return 1 / $dist;
    }

    // eg: '{lat: 0.51, lng: 0.55}'
    public static function fromHttpHeader($geolocationHeader) {
        return self::fromObject(json_decode($geolocationHeader));
    }

    public static function fromObject($data) {
        return new LatLng($data->lat, $data->lng);
    }


    public function __construct($lat, $lng, $isPrecise = true) {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->isPrecise = $isPrecise;
    }

    public $lat;
    public $lng;
    public $isPrecise;

}