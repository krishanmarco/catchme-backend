<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Locations;

use Location as DbLocation;
use LocationImage as DbLocationImage;
use LocationImageQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class LocationImages {

    public function __construct(DbLocation $location) {
        $this->location = $location;
        $this->calculateLocationImages();
    }

    /** @var DbLocation */
    private $location;

    /** @var bool */
    private $approvedImages = true;

    /** @var int */
    private $imageTTLSeconds = LOCATION_MEDIA_TTL;

    /** @var DbLocationImage[] */
    private $locationImages = [];

    /** @return DbLocationImage[] */
    public function getLocationImages() {
        return $this->locationImages;
    }

    /**
     * Get the location images, where the inserted timestamp is
     * smaller than $this->imageValidityHours (int) hours ago
     * and the approved state is $this->approvedImages (bool)
     */
    private function calculateLocationImages() {

        $locationImageQuery = LocationImageQuery::create()
            ->filterByLocationId($this->location->getId())
            ->filterByApproved($this->approvedImages);

        if (LOCATION_MEDIA_APPLY_TTL) {
            $locationImageQuery->filterByInsertedTs(
                time() - $this->imageTTLSeconds,
                Criteria::GREATER_EQUAL
            );
        }

        $this->locationImages = $locationImageQuery
            ->orderByInsertedTs(Criteria::DESC)
            ->find()
            ->getData();
    }

}
