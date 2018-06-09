<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Locations;

use Propel\Runtime\ActiveQuery\Criteria;
use LocationImage;
use LocationImageQuery;
use Location as DbLocation;
use Models\LocationImagesResult;
use EMediaType;

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

    /** @var LocationImagesResult[] */
    private $result;

    /** @return LocationImagesResult[] */
    public function getResult() {
        return $this->result;
    }

    private function calculateLocationImages() {
        // Get LocationImages from the database
        $locationImages = $this->getDbLocationImages();

        $locationImagesResult = [];
        foreach ($locationImages as $li) {
            array_push($locationImagesResult, new LocationImagesResult(
                $li->getId(),
                $li->getLocationId(),
                EMediaType::LOCATION_IMAGE,
                $li->getUrl()
            ));
        }

        $this->result = $locationImagesResult;
    }

    /**
     * Get the location images, where the inserted timestamp is
     * smaller than $this->imageValidityHours (int) hours ago
     * and the approved state is $this->approvedImages (bool)
     *
     * @return LocationImage[]
     */
    private function getDbLocationImages() {

        $locationImageQuery = LocationImageQuery::create()
            ->filterByLocationId($this->location->getId())
            ->filterByApproved($this->approvedImages);

        if (LOCATION_MEDIA_APPLY_TTL) {
            $locationImageQuery->filterByInsertedTs(
                time() - $this->imageTTLSeconds,
                Criteria::GREATER_EQUAL
            );
        }

        return $locationImageQuery
            ->orderByInsertedTs(Criteria::DESC)
            ->find()
            ->getData();
    }

}
