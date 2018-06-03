<?php /** Created by Krishan Marco Madan on 03-Jun-18. */

namespace Models\Calculators\Helpers;

use KMeans\Cluster;
use KMeans\Clusterizer;
use KMeans\ClusterPoint;
use Map\LocationAddressTableMap;
use Map\LocationTableMap;
use Map\UserLocationFavoriteTableMap;
use Models\LocIdCoord;
use Models\Queries\User\UserQueriesWrapper;
use SFCriteria;
use Models\UserSuggestedLocationsResult;
use Models\UserSuggestedLocationsResWrapper;
use User as DbUser;
use LocationQuery;
use LocationAddressQuery;
use UserLocationFavoriteQuery;
use LatLng;
use WeightedCalculator\WeightedGroupCalculator;
use WeightedCalculator\IWeightCalculator;
use WeightedCalculator\WeightedUnit;
use WeightedCalculator\WeightCalculator;
use Closure;

class UserSuggestedLocationsCalc {

    public function __construct(Closure $getLocations) {
        $this->getLocations = $getLocations;
    }

    /** @var Closure */
    private $getLocations;

    /** @var Closure */
    private $sd;

}