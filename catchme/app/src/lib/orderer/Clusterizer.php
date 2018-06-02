<?php /** Created by Krishan Marco Madan on 01-Jun-18. */

namespace KMeans;
use Closure;

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
            $distP1 = sqrt(($distFrom[0] - $p1[0])^2 + ($distFrom[1] - $p1[1])^2);
            $distP2 = sqrt(($distFrom[0] - $p2[0])^2 + ($distFrom[1] - $p2[1])^2);
           return $distP1 < $distP2 ? -1 : 1;
        });

        return $this->points;
    }

    /**
     * Calculates the clusters with the popularity criteria
     * this means that the clusters are ordered by number of points in them
     */
    public function clusterizeByPopularity() {
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
        foreach ($clusters as $cluster) {

            /** @var Point $point */
            foreach ($cluster as $point) {
                $clusterPoints[] = new ClusterPoint($point->getCoordinates(), $this->space[$point]);
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

    public function __construct(array $coordinates, $data = null) {
        $this->coordinates = $coordinates;
        $this->data = $data;
    }

    /** @var array */
    public $coordinates;

    /** @var mixed */
    public $data;
}