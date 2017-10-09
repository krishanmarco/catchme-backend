<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Location\Search;
use Propel\Extensions\QueryHelper;
use Map\SearchLocationTableMap;
use SearchLocationQuery;
use SearchLocation;
use LocationQuery;
use Location;

class LocationSearch {

    public function __construct($searchQuery) {
        $this->searchQuery = strtoupper(trim($searchQuery));
    }


    /** @var String $searchQuery */
    private $searchQuery;



    /** @var array $locationsResult : [Location-Id => Location] */
    private $locationResults = [];

    public function getResults() { return array_values($this->locationResults); }

    private function addLocationToResult(Location $location) {
        $this->locationResults[$location->getId()] = $location;
    }




    public function search() {

        // Use a FullTextSearch to match the search query
        // to the query column on the SearchLocation table
        /** @var SearchLocation[] $indexedLocations */
        $indexedLocations = QueryHelper::fullTextSearch(
            SearchLocationQuery::create(),
            SearchLocationTableMap::COL_QUERY,
            $this->searchQuery
        );

        // Get all location ids from the SearchLocation
        // rows that matched the search query
        $locationIds = [];
        foreach ($indexedLocations as $il)
            $locationIds[] = $il->getLocationId();


        // Select all found ids into the result holder
        $this->locationResults = LocationQuery::create()
            ->findPks($locationIds)
            ->getData();

    }


}