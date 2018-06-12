<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 */

namespace Models\Location;

use Location as DbLocation;
use LocationImage as DbLocationImage;
use LocationQuery as LocationQuery;

class LocationImagesModel {

    /** @return LocationImagesModel */
    public static function fromId($locationId) {
        return self::fromLocation(LocationQuery::create()->findPk(intval($locationId)));
    }


    /** @return LocationImagesModel */
    public static function fromLocation(DbLocation $location) {
        return new LocationImagesModel($location);
    }


    private function __construct(DbLocation $location) {
        $this->location = $location;
    }


    /** @var DbLocation $location */
    private $location;

    public function getLocation() { return $this->location; }


    /** @return DbLocationImage */
    public function add($inserterUid) {
        $locationImage = new DbLocationImage();
        $locationImage->setLocationId($this->location->getId());
        $locationImage->setInserterId($inserterUid);
        $locationImage->setInsertedTs(time());
        $locationImage->setApproved(LOCATION_DEFAULT_IMAGE_APPROVED);
        $locationImage->save();
        return $locationImage;
    }


    public function delete(DbLocationImage $locationImage) {
        $locationImage->delete();
    }


}