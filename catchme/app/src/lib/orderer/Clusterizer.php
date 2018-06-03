<?php /** Created by Krishan Marco Madan on 01-Jun-18. */

namespace KMeans;
use Closure;
use DbLatLng;

/**
 * Wrapper around the KMeans library
 */
class Clusterizer {
    const nClusters = 10;

    /**
     * Clusterizer constructor.
     * @param ClusterPoint[] $clusterPoints
     */
    public function __construct(array $clusterPoints) {
        $this->points = $clusterPoints;
        $this->space = new Space(2);
    }

    /**
     * Array used as a data set to form the clusters
     * @var array ([lat, lng], ...)
     */
    private $points;

    /** @var Space */
    private $space;

    /**
     * Calculates the clusters with the distance-from-point criteria
     * This means that the clusters are ordered by the distance they
     * have from bindToPoint
     * @param array (lat, lng) $bindToPoint
     */
    public function getSortedByDistanceFromPoint(array $distFrom) {

        usort($this->points, function($p1, $p2) use ($distFrom) {
            return DbLatLng::getDist($distFrom, $p1) < DbLatLng::getDist($distFrom, $p2)
                ? -1    // Do not swap
                : 1;    // Swap
        });

        return $this->points;
    }

    /**
     * Calculates the clusters with the popularity criteria
     * this means that the clusters are ordered by number of points in them
     * @return ClusterPoint[]
     */
    public function clusterizeOrderedBySize() {// todo no need to sort
        return $this->calculateFlatClusterizedPoints($this->points, function(Cluster $c1, Cluster $c2) {
            // Given 2 clusters, the one with the higher number of points
            // is considered more compatible
            return $c1->count() > $c2->count() ? -1 : 1;
        });
    }

    private function calculateFlatClusterizedPoints(array $clusterData, Closure $clusterCritieria) {
        $clusterPoints = [];

        $clusters = $this->calculateClusters($clusterData, $clusterCritieria);

        // The clusters are already ordered by clusterCriteria (not internally)
        // Flatten the elements
        for ($i = 0; $i < sizeof($clusters); $i++) {

            /** @var Point $point */
            foreach ($clusters[$i] as $point) {
                $clusterPoints[] = new ClusterPoint(
                    $point->getCoordinates(),
                    $this->space[$point],
                    $i
                );
            }
        }

        return $clusterPoints;
    }

    /**
     * @param array $clusterData
     * @param Closure $clusterCritieria Closure that orders two clusters (usort)
     * @return Cluster[]
     */
    private function calculateClusters(array $clusterData, Closure $clusterCritieria) {
        // Fill this Clusterizer space

        foreach ($clusterData as $point)
            $this->space->addPoint($point);

        // Resolve as nClusters
        $clusters = $this->space->solve(self::nClusters, Space::SEED_DASV);

        // Order the clusters based on the input criteria
        usort($clusters, $clusterCritieria);

        return $clusters;
    }

}

class ClusterPoint {

    public function __construct(array $coordinates, $data = null, $clusterIndex = -1) {
        $this->coordinates = $coordinates;
        $this->data = $data;
    }

    /** @var array */
    public $coordinates;

    /** @var mixed */
    public $data;

    /** @var int */
    public $clusterIndex;
}