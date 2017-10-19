<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Location\Search;

use Propel\Extensions\QueryHelper;
use Map\SearchUserTableMap;
use SearchUserQuery;
use SearchUser;
use UserQuery;
use User;


class UserSearch {

    public function __construct(array $searchQuerys) {
        $this->searchQuerys = array_map('trim', $searchQuerys);
        $this->searchQuerys = array_map('strtoupper', $this->searchQuerys);
    }


    /** @var String[] $searchQuerys */
    private $searchQuerys;



    /** @var array $userResults : [User-Id => User] */
    private $userResults = [];

    public function getResults() {
        return array_values($this->userResults);
    }


    public function search() {
        foreach ($this->searchQuerys as $searchQuery) {
            $result = $this->searchOne($searchQuery);
            $this->userResults = array_merge($this->userResults, $result);
        }
        return $this->userResults;
    }

    private function searchOne($searchString) {

        // Use a FullTextSearch to match the search query
        // to the query column on the SearchUser table
        /** @var SearchUser[] $indexedUsers */
        $indexedUsers = QueryHelper::fullTextSearch(
            SearchUserQuery::create(),
            SearchUserTableMap::COL_QUERY,
            $searchString
        );

        // Get all user ids from the SearchUser
        // rows that matched the search query
        $userIds = [];
        foreach ($indexedUsers as $il)
            $userIds[] = $il->getUserId();


        // Select all found ids into the result holder
        return UserQuery::create()
            ->findPks($userIds)
            ->getData();
    }


}

