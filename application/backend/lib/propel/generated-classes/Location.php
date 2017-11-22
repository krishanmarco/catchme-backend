<?php

use Base\Location as BaseLocation;

/**
 * Skeleton subclass for representing a row from the 'location' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Location extends BaseLocation {


    // When a location is saved/updated we need to automatically
    // save its search query in the LocationSearch table
    public function postInsert(\Propel\Runtime\Connection\ConnectionInterface $con = null) {
        parent::postInsert($con);
        SearchLocation::prepareForLocation($this, new SearchLocation())
            ->save($con);
    }



    // When a location is saved/updated we need to automatically
    // save its search query in the LocationSearch table
    public function postUpdate(\Propel\Runtime\Connection\ConnectionInterface $con = null) {
        parent::postUpdate($con);
        SearchLocation::prepareForLocation($this, SearchLocationQuery::create()->findPk($this->getId()))
            ->save($con);
    }

}
