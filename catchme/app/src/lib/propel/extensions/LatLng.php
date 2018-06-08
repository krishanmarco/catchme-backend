<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 10/09/2017 - Fithancer © */

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