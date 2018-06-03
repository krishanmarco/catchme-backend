<?php /** Created by Krishan Marco Madan on 01-Jun-18. */

namespace KMeans;
use Closure;
use WeightedCalculator\WeightedUnit;

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
        $this->flatClusterPoints = $clusterPoints;
        $this->clusterize();
    }

    /**
     * Array used as a data set to form the clusters
     * @var ClusterPoint[]
     */
    private $flatClusterPoints;

    /** @var Cluster[] */
    private $clusters;

    /** @var ClusterPoint[] */
    public function getFlatPoints() {
        return $this->flatClusterPoints;
    }

    /** @return array */
    public function getCenterOfBiggestCluster() {
        $biggestCluster = $this->clusters[0];

        for ($i = 1; $i < sizeof($this->clusters); $i++)
            if (count($this->clusters[$i]) > count($biggestCluster))
                $biggestCluster = $this->clusters[$i];

        return [$biggestCluster[0], $biggestCluster[1]];
    }

    /**
     * @param array $clusterData
     * @param Closure $clusterCritieria
     */
    private function clusterize() {
        $space = new Space(2);

        // Fill this Clusterizer space
        foreach ($this->flatClusterPoints as $clusterPoint)
            $space->addPoint($clusterPoint->coordinates, $clusterPoint);

        // Calculate clusters
        $this->clusters = $space->solve(self::nClusters, Space::SEED_DASV);

        // Flatten the elements
        $newClusterPoints = [];

        /** @var Cluster $cluster */
        /** @var Point $clusterPoint */
        /** @var ClusterPoint $pointData */
        for ($i = 0; $i < sizeof($this->clusters); $i++) {
            $cluster = $this->clusters[$i];
            $clusterIndex = $i;
            $clusterSize = count($cluster);

            foreach ($cluster as $clusterPoint) {
                $pointData = $space[$clusterPoint];
                $pointData->inClusterWithIndex = $clusterIndex;
                $pointData->inClusterWithSize = $clusterSize;
                array_push($newClusterPoints, $pointData);
            }
        }

        $this->flatClusterPoints = $newClusterPoints;
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
    public $inClusterWithIndex;

    /** @var int */
    public $inClusterWithSize;
}
