<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 10/09/2017 - Fithancer © */

class DbLatLng {

    public static function fromObject($data) {
        return new DbLatLng($data->lat, $data->lng);
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