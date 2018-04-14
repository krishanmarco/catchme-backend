<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;

use Slim\Exception\Api400;
use EMediaType;
use R;

class ControllerMediaGet {

    public function getBasedOnType($typeId, $itemId, $imageId) {
        switch ($typeId) {
            case EMediaType::LOCATION_IMAGE:
                return $this->getLocationImage($itemId, $imageId);
        }

        throw new Api400(R::return_error_generic);
    }



    /**
     * @param $locationId
     * @param $imageId
     * @return resource
     * @throws Api400
     */
    private function getLocationImage($locationId, $imageId) {

        // There is no need to query the database
        $filePath = strtr(LOCATION_MEDIA_TPL, [
            '{LID}' => $locationId,
            '{IMAGE_ID}' => $imageId
        ]);

        if (!file_exists($filePath)) {
            // Development
            return fopen(LOCATION_MEDIA_IMAGE_404, 'r');
            //throw new Api400(R::return_error_file_not_found);
        }

        // LOCATION_IMAGE_PATH_TEMPLATE
        return fopen($filePath, 'rb');
    }



}