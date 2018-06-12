<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 */

namespace Controllers;

use EMediaType;
use FileUploader;
use Firebase\FeedItems\FeedItemFriendAddedImage;
use Firebase\FeedManager;
use Models\Feed\MultiNotificationManager;
use Models\Location\LocationImagesModel;
use R;
use Slim\Exception\Api400;
use Slim\Http\UploadedFile;
use User as DbUser;

class ControllerMediaPut {

    public function __construct(DbUser $authenticatedUser) {
        $this->authenticatedUser = $authenticatedUser;
    }

    /** @var DbUser $authenticatedUser */
    private $authenticatedUser;

    /** @return string */
    public function addBasedOnType($typeId, $itemId, UploadedFile $uploadedFile) {
        switch ($typeId) {
            case EMediaType::LOCATION_IMAGE:
                return $this->putLocationImage($itemId, $uploadedFile);
        }

        throw new Api400(R::return_error_generic);
    }

    /** @return string */
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

        // Add the notification item to firebase
        FeedManager::build($this->authenticatedUser)
            ->postMultipleFeeds(new FeedItemFriendAddedImage(
                $this->authenticatedUser,
                $locationImagesModel->getLocation(),
                $locationImage
            ), MultiNotificationManager::uidsInterestedInUser($this->authenticatedUser->getId()));

        return $locationImage->asUrl();
    }

}