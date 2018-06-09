<?php

use Base\SearchLocation as BaseSearchLocation;
use Propel\Runtime\Connection\ConnectionInterface;

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

    public static function refresh(Location $location, ConnectionInterface $con = null) {
        $searchLocation = $location->getSearchString($con);

        if (is_null($searchLocation))
            $searchLocation = new SearchLocation();

        $searchLocation
            ->setLocationId($location->getId())
            ->setQuery(strtoupper(strtr(
                "{NAME} {EMAIL} {PHONE} {CITY} {TOWN} {POSTCODE} {ADDRESS}",
                [
                    '{NAME}' => $location->getName(),
                    '{EMAIL}' => $location->getEmail(),
                    '{PHONE}' => $location->getPhone(),
                    '{CITY}' => $location->getAddress()->getCity(),
                    '{TOWN}' => $location->getAddress()->getTown(),
                    '{POSTCODE}' => $location->getAddress()->getPostcode(),
                    '{ADDRESS}' => $location->getAddress()->getAddress(),
                ]
            )))
            ->save($con);
    }

}
