<?php

use Base\Location as BaseLocation;
use \Slim\Exception\Api400;
use \Propel\Runtime\Connection\ConnectionInterface;

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

    /**
     * @param Location[] $locations
     * @return int[]
     */
    public static function mapUsersToIds(array $locations) {
        return array_map(function(Location $location) { $location->getId(); }, $locations);
    }

    /**
     * When a user is inserted/updated we need to automatically
     * save its search query in the UserSearch table
     */
    public function postSave(ConnectionInterface $con = null) {
        parent::postSave($con);
        SearchLocation::refresh($this, $con);
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
