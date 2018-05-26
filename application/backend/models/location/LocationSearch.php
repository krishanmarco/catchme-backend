<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Location\Search;
use SearchLocationQuery;
use SearchLocation;
use Location as DbLocation;

class LocationSearch {

    public function __construct($searchQuery) {
        $this->searchString = strtoupper(trim($searchQuery));
    }

    /** @var String */
    private $searchString;


    /** @var DbLocation[] */
    private $locationResults = [];

    public function getResults() {
        return $this->locationResults;
    }

    public function searchOne() {
        // Use a FullTextSearch to match the search query
        // to the query column on the SearchLocation table
        /** @var SearchLocation[] $indexedLocations */
        $indexLocations = SearchLocationQuery::create()
            ->fullTextSearch($this->searchString)
            ->joinWithLocation()
            ->find()
            ->getData();

        // Select all found ids into the result holder
        return array_map(function(SearchLocation $searchLocation) {
            return $searchLocation->getLocation();
        }, $indexLocations);
    }


}