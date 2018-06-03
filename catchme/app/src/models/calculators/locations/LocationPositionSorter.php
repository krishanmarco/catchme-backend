<?php /** Created by Krishan Marco Madan on 01-Jun-18. */

namespace Models\Calculators\Locations;
use Location as DbLocation;
use KMeans\Clusterizer;
use KMeans\ClusterPoint;

/**
 * This class close positions based on an array [[$lat, $lng], ...] parameter
 * https://stackoverflow.com/questions/4349160/how-to-group-latitude-longitude-points-that-are-close-to-each-other
 * Class LocationPosition
 * @package Models\Calculators\Locations
 */
class LocationPositionSorter {

    /**
     * LocationPosition constructor.
     * @param DbLocation[] $locations
     */
    public function __construct(array $locations) {
        $this->locations = $locations;

        $this->clusterizer = new Clusterizer(
            array_map([$this, 'mapLocationToClusterPoint'], $this->locations)
        );
    }

    /**
     * Array used as a data set to form the clusters
     * @var DbLocation[]
     */
    private $locations;

    /** @var Clusterizer */
    private $clusterizer;

    /**
     * Calculates the clusters with the distance-from-point criteria
     * This means that the clusters are ordered by the distance they
     * have from bindToPoint
     * @param array (lat, lng) $bindToPoint
     */
    public function getSortedByDistanceFromPoint(array $point) {
        $clusterPoints = $this->clusterizer->getSortedByDistanceFromPoint($point);
        return array_map([$this, 'mapClusterPointToLocation'], $clusterPoints);
    }

    /**
     * Calculates the clusters with the popularity criteria
     * this means that the clusters are ordered by number of points in them
     */
    public function clusterizeByPopularity() {
        $clusterPoints = $this->clusterizer->clusterizeOrderedBySize();
        return array_map([$this, 'mapClusterPointToLocation'], $clusterPoints);
    }

    private function mapLocationToClusterPoint(DbLocation $location) {
        $latLng = $location->getAddress()->getLatLng();
        return new ClusterPoint([$latLng->lat, $latLng->lng], $location);
    }

    private function mapClusterPointToLocation(ClusterPoint $clusterPoint) {
        return $clusterPoint->data;
    }

}