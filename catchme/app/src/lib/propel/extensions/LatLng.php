<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 10/09/2017 - Fithancer © */

class LatLng {

    public static function getDist(array $xy1, array $xy2) {
        return sqrt(($xy1[0] - $xy2[0])^2 + ($xy1[1] - $xy2[1])^2);
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