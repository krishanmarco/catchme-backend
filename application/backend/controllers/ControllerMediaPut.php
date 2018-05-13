<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;

use Models\Calculators\UserModel;
use Models\Feed\MultiNotificationManager;
use Models\Location\LocationImagesModel;
use Models\User\Accounts\UserEditProfile;
use Firebase\FeedManager;
use Firebase\FeedItems\FeedItemFriendAddedImage;
use FileUploader;
use Slim\Exception\Api400;
use Slim\Http\UploadedFile;
use User as DbUser;
use EMediaType;
use R;

class ControllerMediaPut {

    public function __construct(DbUser $authenticatedUser) {
        $this->authenticatedUser = $authenticatedUser;
    }

    /** @var DbUser $authenticatedUser */
    private $authenticatedUser;


    public function addBasedOnType($typeId, $itemId, UploadedFile $uploadedFile) {
        switch ($typeId) {
            case EMediaType::LOCATION_IMAGE:
                return $this->putLocationImage($itemId, $uploadedFile);
        }

        throw new Api400(R::return_error_generic);
    }




    public function putLocationImage($locationId, UploadedFile $uploadedFile) {
        $locationImagesModel = LocationImagesModel::fromId($locationId);

        $locationImage = $locationImagesModel->add($this->authenticatedUser->getId());

        try {

            $savePath = strtr(LOCATION_MEDIA_DIR_TPL, [
                '{LID}' => $locationImage->getLocationId()
            ]);

            FileUploader::build($uploadedFile)
                ->saveUpload($savePath, $locationImage->getId());

        } catch (Api400 $apiException) {
            // Delete the database entry because the file upload failed
            $locationImage->delete();
            throw $apiException;
        }

        $mfm = new MultiNotificationManager($this->authenticatedUser);

        // Add the notification item to firebase
        FeedManager::build($this->authenticatedUser)
            ->postMultipleFeeds(new FeedItemFriendAddedImage(
                $this->authenticatedUser,
                $locationImagesModel->getLocation(),
                $locationImage
            ), $mfm->getNotifiableFriendIds());

        return $locationImage->getUrl();
    }

}