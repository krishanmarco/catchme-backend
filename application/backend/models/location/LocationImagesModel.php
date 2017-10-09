<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 - Fithancer © */

namespace Models\Location;
use Location;
use LocationImage;
use LocationQuery as LocationQuery;
use Propel\Runtime\Exception\PropelException;
use Slim\Exception\ApiException;
use R;

class LocationImagesModel {

    /** @return LocationImagesModel */
    public static function fromId($locationId) {
        return self::fromLocation(LocationQuery::create()->findPk(intval($locationId)));
    }


    /** @return LocationImagesModel */
    public static function fromLocation(Location $location) {
        return new LocationImagesModel($location);
    }




    private function __construct(Location $location) {
        $this->location = $location;
    }


    /** @var Location $location */
    private $location;
    public function getLocation() { return $this->location; }


    /** @return LocationImage */
    public function add($inserterUid) {

        $locationImage = new LocationImage();
        $locationImage->setLocationId($this->location->getId());
        $locationImage->setInserterId($inserterUid);
        $locationImage->setInsertedTs(time());
        $locationImage->setApproved(true);


        try {
            $locationImage->save();

        } catch (PropelException $exception) {
            switch ($exception->getCode()) {
                default: throw new ApiException(R::return_error_generic);
            }
        }

        return $locationImage;
    }


    public function delete(LocationImage $locationImage) {
        $locationImage->delete();
    }


}