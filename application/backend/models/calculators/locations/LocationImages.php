<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Locations;

use Models\Calculators\LocationModel;
use \Propel\Runtime\ActiveQuery\Criteria as Criteria;
use LocationImage as LocationImage;
use LocationImageQuery as LocationImageQuery;


class LocationImages {
    const _LOCATION_IMAGE_TTL = LOCATION_IMAGE_TTL;



    public function __construct(LocationModel $LocationModel) {
        $this->LocationModel = $LocationModel;
    }


    /** @var LocationModel $LocationModel */
    private $LocationModel;
    public function getLocation() { return $this->LocationModel->getLocation(); }



    private $approvedImages = true;
    public function overrideApprovedImages($approved) {
        $this->approvedImages = $approved;
        return $this;
    }



    private $imageTTLSeconds = self::_LOCATION_IMAGE_TTL;
    public function overrideValiditySeconds($seconds) {
        $this->imageTTLSeconds = $seconds;
        return $this;
    }



    public function execute() {

        // Get LocationImages from the database
        $locationImages = $this->getDbLocationImages();

        $locationImagesResult = [];
        foreach ($locationImages as $li) {
            array_push($locationImagesResult, new LocationImagesResult(
                $li->getId(),
                $li->getLocationId(),
                \EMediaType::LOCATION_IMAGE,
                $li->getUrl()
            ));
        }

        return $locationImagesResult;
    }




    // Get the location images, where the inserted timestamp is
    // smaller than $this->imageValidityHours (int) hours ago
    // and the approved state is $this->approvedImages (bool)
    /** @return LocationImage[] */
    private function getDbLocationImages() {

        return LocationImageQuery::create()
            ->filterByLocationId($this->getLocation()->getId())
            ->filterByApproved($this->approvedImages)
            ->filterByInsertedTs(
                time() - $this->imageTTLSeconds,
                Criteria::GREATER_EQUAL

            )->find()
            ->getData();

    }






}




class LocationImagesResult {

    public function __construct($id, $itemId, $typeId, $url) {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->typeId = $typeId;
        $this->url = $url;
    }

    public $id;
    public $itemId;
    public $typeId;
    public $url;

}