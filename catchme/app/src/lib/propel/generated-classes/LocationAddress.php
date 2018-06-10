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

    public function setLatLng(LatLng $latLng) {
        parent::setLat($latLng->lat);
        parent::setLng($latLng->lng);
    }

    /** @return LatLng */
    public function getLatLng() {
        return new LatLng($this->getLat(), $this->getLng());
    }

}
