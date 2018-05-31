<?php

use Base\Location as BaseLocation;
use \Slim\Exception\Api400;

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


    public function trySetAvatarFromFile(\Slim\Http\UploadedFile $uploadedFile) {
        try {
            // Create the file path
            $savePath = strtr(LOCATION_AVATAR_DIR_TPL, [
                '{LID}' => $this->getId()
            ]);

            // Try save the file with its hash as name (no duplicates)
            $uniqueName = FileUploader::build($uploadedFile)
                ->saveUploadByHash($savePath);

            // Save success, write to $location object
            $this->setPictureUrl(strtr(LOCATION_AVATAR_URL_TPL, [
                '{LID}' => $this->getId(),
                '{UNIQUE_NAME}' => $uniqueName
            ]));

        } catch (Api400 $exception) {
            // Not a critical error, continue
            // the profile avatar will not be updated
        }
    }

}
