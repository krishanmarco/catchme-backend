<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 */

namespace Controllers;

use EMediaType;
use R;
use Slim\Exception\Api400;
use Slim\Exception\Api404;

class ControllerMediaGet {
    const URL_TPL = URL_API . '/media/get/{type}/{id}/{iid}';

    /** @return resource */
    public function getBasedOnType($typeId, $itemId, $imageId) {
        switch ($typeId) {
            case EMediaType::LOCATION_IMAGE:
                return $this->getLocationImage($itemId, $imageId);
        }

        throw new Api400(R::return_error_generic);
    }

    /** @return resource */
    private function getLocationImage($locationId, $imageId) {
        $filePath = strtr(LOCATION_MEDIA_TPL, [
            '{LID}' => $locationId,
            '{IMAGE_ID}' => $imageId
        ]);

        if (!file_exists($filePath))
            throw new Api404(R::return_error_generic);

        return fopen($filePath, 'rb');
    }

}