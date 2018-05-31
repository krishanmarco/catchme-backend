<?php

use Base\LocationAddress as BaseLocationAddress;

/**
 * Skeleton subclass for representing a row from the 'location_address' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class LocationAddress extends BaseLocationAddress {


    public function setLatLng(DbLatLng $latLng) {
        parent::setLatLngJson(json_encode($latLng));
    }

    /** @return DbLatLng */
    public function getLatLng() {
        return DbLatLng::fromObject(json_decode($this->getLatLngJson()));
    }

}
