<?php

use Base\LocationImage as BaseLocationImage;

/**
 * Skeleton subclass for representing a row from the 'location_image' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class LocationImage extends BaseLocationImage {

    /** @return String */
    public function getUrl() {
        return API_URL . '/media/get/' . EMediaType::LOCATION_IMAGE . "/{$this->getLocationId()}/{$this->getId()}";
    }

}
