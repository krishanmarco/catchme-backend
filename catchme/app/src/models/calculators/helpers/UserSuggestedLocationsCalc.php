<?php /** Created by Krishan Marco Madan on 03-Jun-18. */

namespace Models\Calculators\Helpers;

use KMeans\Clusterizer;
use KMeans\IClusterPoint;
use KMeans\ClusterData;
use LatLng;
use WeightedCalculator\WeightedUnit;
use WeightedCalculator\WeightCalculator;
use Closure;

class UserSuggestedLocationsCalc {

    public function __construct(Closure $getLocations, Closure $mapLocIdCoordToWeightedUnit) {

        // Get the locations
        $this->locations = $getLocations();

        // Build the clusterizer
        $this->clusterizer = new Clusterizer($this->locations);

        // Build the weightUnits
        $this->weightedUnits = array_map($mapLocIdCoordToWeightedUnit, $this->locations);
    }

    /** @var LocIdCoord[] */
    private $locations = [];

    /** @var Clusterizer */
    private $clusterizer;

    /** @var WeightedUnit[] */
    private $weightedUnits = [];

    /** @var float */
    private $itemWeight = 1;

    public function setWeight($weight) {
        $this->itemWeight = $weight;
    }

    public function distFromCenterOfBiggestCluster(LatLng $position) {
        return LatLng::dist([$position->lat, $position->lng], $this->clusterizer->getCenterOfBiggestCluster());
    }

    /** @return WeightCalculator */
    public function getWeightCalculator() {
        return new WeightCalculator($this->itemWeight, function() {
            return $this->weightedUnits;
        });
    }

}


class LocIdCoord implements IClusterPoint {

    public function __construct($lid, $lat, $lng) {
        $this->lid = $lid;
        $this->lat = $lat;
        $this->lng = $lng;
    }

    /** @var int */
    public $lid;

    /** @var double */
    public $lat;

    /** @var double */
    public $lng;

    /** @var null|ClusterData */
    public $clusterData;

    public function getCoordinates() {
        return [$this->lat, $this->lng];
    }

    public function setClusterData(ClusterData $clusterData) {
        $this->clusterData = $clusterData;
    }

}