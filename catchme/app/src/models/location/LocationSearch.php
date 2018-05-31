<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Location\Search;
use SearchLocationQuery;
use SearchLocation;
use Location as DbLocation;

class LocationSearch {

    public function __construct(array $searchQueries) {
        $this->searchQueries = array_map('trim', $searchQueries);
        $this->searchQueries = array_map('strtoupper', $this->searchQueries);
    }

    /** @var String[] */
    private $searchQueries;

    /** @var DbLocation[] */
    private $locationResults = [];

    public function getResults() {
        return $this->locationResults;
    }
    
    public function search() {
        $this->locationResults = $this->searchOne(implode(' ', $this->searchQueries));
    }

    private function searchOne($searchString) {
        // Use a FullTextSearch to match the search query
        // to the query column on the SearchLocation table
        /** @var SearchLocation[] $indexedLocations */
        $indexLocations = SearchLocationQuery::create()
            ->fullTextSearch($searchString)
            ->joinWithLocation()
            ->find()
            ->getData();

        // Select all found ids into the result holder
        return array_map(function(SearchLocation $searchLocation) {
            return $searchLocation->getLocation();
        }, $indexLocations);
    }


}