<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;

use Models\Calculators\UserModel;
use Models\Feed\MultiFeedManager;
use Models\Location\LocationImagesModel;
use Models\User\Accounts\UserEditProfile;
use Firebase\FeedManager;
use Firebase\FeedItems\FeedItemFriendAddedImage;
use Firebase\FeedItems\FeedItemUserAttendanceRequest;
use FileUploader;
use Slim\Exception\Api400;
use Slim\Http\UploadedFile;
use Symfony\Component\Config\Definition\Exception\Exception;
use User as DbUser;
use EMediaType;
use R;

class ControllerMediaPut {
    const _LOCATION_IMAGE_DIR_PATH_TEMPLATE = LOCATION_IMAGE_DIR_PATH_TEMPLATE;

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

            $savePath = strtr(self::_LOCATION_IMAGE_DIR_PATH_TEMPLATE, [
                '{LID}' => $locationImage->getLocationId()
            ]);

            FileUploader::build($uploadedFile)
                ->allowImages()
                ->saveUpload($savePath, $locationImage->getId());

        } catch (Api400 $apiException) {
            // Delete the database entry because the file upload failed
            $locationImage->delete();
            throw $apiException;
        }

        $mfm = new MultiFeedManager($this->authenticatedUser);

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