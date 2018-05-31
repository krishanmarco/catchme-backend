<?php

use Base\SearchLocation as BaseSearchLocation;

/**
 * Skeleton subclass for representing a row from the 'search_location' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SearchLocation extends BaseSearchLocation {

    public static function prepareForLocation(Location $location, SearchLocation $searchLocation) {
        $searchLocation->setLocationId($location->getId());

        $locationAddress = $location->getAddress();

        $searchLocation->setQuery(strtoupper(strtr(
            "{NAME} {EMAIL} {PHONE} {CITY} {TOWN} {POSTCODE} {ADDRESS}",
            [
                '{NAME}' => $location->getName(),
                '{EMAIL}' => $location->getEmail(),
                '{PHONE}' => $location->getPhone(),
                '{CITY}' => $locationAddress->getCity(),
                '{TOWN}' => $locationAddress->getTown(),
                '{POSTCODE}' => $locationAddress->getPostcode(),
                '{ADDRESS}' => $location->getAddress(),
            ]
        )));

        return $searchLocation;
    }

}
