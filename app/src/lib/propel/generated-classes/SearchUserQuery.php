<?php

use Base\SearchUserQuery as BaseSearchUserQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'search_user' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SearchUserQuery extends BaseSearchUserQuery {

    public function fullTextSearch($searchString) {
        $matchQuery = strtr('MATCH({col_name}) AGAINST(? IN BOOLEAN MODE)', [
            '{col_name}' => \Map\SearchUserTableMap::COL_QUERY
        ]);

        return $this
            ->where($matchQuery, $searchString)
            ->groupBy(\Map\SearchUserTableMap::COL_USER_ID);
    }

}
