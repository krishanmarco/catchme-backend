<?php

use Base\SearchLocationQuery as BaseSearchLocationQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'search_location' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SearchLocationQuery extends BaseSearchLocationQuery {

    public function fullTextSearch($searchString) {
        $matchQuery = strtr('MATCH({col_name}) AGAINST(? IN BOOLEAN MODE)', [
            '{col_name}' => \Map\SearchLocationTableMap::COL_QUERY
        ]);

        return $this
            ->where($matchQuery, $searchString)
            ->groupBy(\Map\SearchLocationTableMap::COL_LOCATION_ID);
    }

}
