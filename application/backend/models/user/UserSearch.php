<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Location\Search;

use Propel\Extensions\QueryHelper;
use Map\SearchUserTableMap;
use SearchUserQuery;
use SearchUser;
use UserQuery;
use User as DbUser;

class UserSearch {

    public function __construct(array $searchQuerys) {
        $this->searchQuerys = array_map('trim', $searchQuerys);
        $this->searchQuerys = array_map('strtoupper', $this->searchQuerys);
    }

    /** @var String[] */
    private $searchQuerys;

    /** @var array */
    private $userResults = [];

    public function getResults() {
        return array_values($this->userResults);
    }

    public function search() {
        $this->userResults = $this->searchOne(implode(' ', $this->searchQuerys));
        return $this->userResults;
    }

    /**
     * @param string $searchString
     * @return DbUser[]
     */
    private function searchOne($searchString) { // todo can be optimized (i.e. join with user...)

        // Use a FullTextSearch to match the search query
        // to the query column on the SearchUser table
        /** @var SearchUser[] $indexedUsers */
        $indexedUsers = QueryHelper::fullTextSearch(
            SearchUserQuery::create(),
            SearchUserTableMap::COL_QUERY,
            $searchString,
            SearchUserTableMap::COL_USER_ID
        );

        // Get all user ids from the SearchUser
        // rows that matched the search query
        $userIds = [];
        foreach ($indexedUsers as $iu)
            $userIds[] = $iu->getUserId();


        // Select all found ids into the result holder
        return UserQuery::create()
            ->findPks($userIds)
            ->getData();
    }

}

