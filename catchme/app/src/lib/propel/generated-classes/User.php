<?php

use Base\User as BaseUser;
use Slim\Exception\Api400;
use \Slim\Http\UploadedFile;
use \Propel\Runtime\Connection\ConnectionInterface;
use \Models\Calculators\UserModel;

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
     * @param User[] $users
     * @return int[]
     */
    public static function mapUsersToIds(array $users) {
        return array_map(function(User $user) { $user->getId(); }, $users);
    }

    /** @var int[] friendIds */
    private $friendIds;


    /**
     * When a user is saved/updated we need to automatically
     * save its search query in the UserSearch table
     */
    public function postSave(ConnectionInterface $con = null) {
        parent::postSave($con);
        SearchUser::refresh($this, $con);
    }

    /** @return int[] */
    public function getFriendIds() {
        if (is_null($this->friendIds)) {

            $friends = UserModel::fromUser($this)
                ->getUserConnections()->getResult()->friends;

            $this->friendIds = User::mapUsersToIds($friends);
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

        } catch (Exception $exception) {
            // Not a critical error, continue
            // the profile avatar will not be updated
        }
    }

}
