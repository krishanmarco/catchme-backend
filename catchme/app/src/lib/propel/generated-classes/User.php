<?php

use Base\User as BaseUser;
use Models\Calculators\UserModel;
use Propel\Runtime\Connection\ConnectionInterface;
use Slim\Http\UploadedFile;

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

    /**
     * @param User[] $uList
     * @return int[]
     */
    public static function mapToIds(array $uList) {
        return array_map(function (User $user) { return $user->getId(); }, $uList);
    }


    /**
     * When a user is saved/updated we need to automatically
     * save its search query in the UserSearch table
     */
    public function postSave(ConnectionInterface $con = null) {
        parent::postSave($con);
        SearchUser::refresh($this, $con);
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

        } catch (Exception $exception) {
            // Not a critical error, continue
            // the profile avatar will not be updated
        }
    }

}
