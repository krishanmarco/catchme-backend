<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;

use Slim\Exception\ApiException;
use EMediaType;
use R;

class ControllerMediaGet {
    const _LOCATION_IMAGE_PATH_TEMPLATE = LOCATION_IMAGE_PATH_TEMPLATE;

    public function getBasedOnType($typeId, $itemId, $imageId) {
        switch ($typeId) {
            case EMediaType::LOCATION_IMAGE:
                return $this->getLocationImage($itemId, $imageId);
        }

        throw new ApiException(R::return_error_generic);
    }



    /**
     * @param $locationId
     * @param $imageId
     * @return resource
     * @throws ApiException
     */
    private function getLocationImage($locationId, $imageId) {

        // There is no need to query the database
        $filePath = strtr(self::_LOCATION_IMAGE_PATH_TEMPLATE, [
            '{LID}' => $locationId,
            '{IMAGE_ID}' => $imageId
        ]);

        if (!file_exists($filePath))
            throw new ApiException(R::return_error_file_not_found);

        // LOCATION_IMAGE_PATH_TEMPLATE
        return fopen($filePath, 'rb');
    }



}