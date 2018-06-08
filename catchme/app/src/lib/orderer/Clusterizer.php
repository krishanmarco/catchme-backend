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
     * @param IClusterPoint[] $clusterPoints
     */
    public function __construct(array $clusterPoints) {
        $this->flatClusterPoints = $clusterPoints;
        $this->clusterize();
    }

    /**
     * Array used as a data set to form the clusters
     * @var IClusterPoint[]
     */
    private $flatClusterPoints = [];

    /** @var Cluster[] */
    private $clusters;

    /** @var IClusterPoint[] */
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
        if (sizeof($this->flatClusterPoints) <= 0) {
            $this->flatClusterPoints = [];
            return;
        }

        $space = new Space(2);

        // Fill this Clusterizer space
        foreach ($this->flatClusterPoints as $clusterPoint)
            $space->addPoint($clusterPoint->getCoordinates(), $clusterPoint);

        // Calculate clusters
        $this->clusters = $space->solve(self::nClusters, Space::SEED_DASV);

        // Flatten the elements
        $newClusterPoints = [];

        /** @var Cluster $cluster */
        /** @var Point $clusterPoint */
        /** @var IClusterPoint $pointData */
        for ($i = 0; $i < sizeof($this->clusters); $i++) {
            $cluster = $this->clusters[$i];
            $clusterIndex = $i;
            $clusterSize = count($cluster);

            foreach ($cluster as $clusterPoint) {
                $pointData = $space[$clusterPoint];
                $pointData->setClusterData(new ClusterData($clusterIndex, $clusterSize));
                array_push($newClusterPoints, $pointData);
            }
        }

        $this->flatClusterPoints = $newClusterPoints;
    }

}

interface IClusterPoint {
    public function getCoordinates();
    public function setClusterData(ClusterData $clusterData);
}

class ClusterData {

    public function __construct($index, $size) {
        $this->index = $index;
        $this->size = $size;
    }

    /** @var int */
    public $index;

    /** @var int */
    public $size;
}
