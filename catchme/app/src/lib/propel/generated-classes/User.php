<?php

use Base\User as BaseUser;
use Slim\Exception\Api400;
use \Slim\Http\UploadedFile;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser {


    // When a location is saved/updated we need to automatically
    // save its search query in the LocationSearch table
    public function postInsert(\Propel\Runtime\Connection\ConnectionInterface $con = null) {
        parent::postInsert($con);
        SearchUser::buildForUser($this, new SearchUser())
            ->save($con);
    }



    // When a location is saved/updated we need to automatically
    // save its search query in the LocationSearch table
    public function postUpdate(\Propel\Runtime\Connection\ConnectionInterface $con = null) {
        parent::postUpdate($con);
        SearchUser::buildForUser($this, SearchUserQuery::create()->findPk($this->getId()))
            ->save($con);
    }




    /** @var int[] friendIds */
    private $friendIds;

    public function getFriendIds() {

        if (is_null($this->friendIds)) {

            $friends = \Models\Calculators\UserModel::fromUser($this)
                ->getUserConnections()->getResult()->friends;

            $this->friendIds = [];
            foreach ($friends as $user)
                array_push($this->friendIds, $user->getId());
        }

        return $this->friendIds;
    }


    public function trySetAvatarFromFile(UploadedFile $uploadedFile) {
        try {
            // Create the file path
            $savePath = strtr(USER_AVATAR_DIR_TPL, [
                '{UID}' => $this->getId()
            ]);

            // Try save the file with its hash as name (no duplicates)
            $uniqueName = FileUploader::build($uploadedFile)
                ->saveUploadByHash($savePath);

            // Save success, write to $location object
            $this->setPictureUrl(strtr(USER_AVATAR_URL_TPL, [
                '{UID}' => $this->getId(),
                '{UNIQUE_NAME}' => $uniqueName
            ]));

        } catch (Api400 $exception) {
            // Not a critical error, continue
            // the profile avatar will not be updated
        }
    }

}
