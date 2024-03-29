<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Location\Search;

use Propel\Extensions\QueryHelper;
use Map\SearchUserTableMap;
use SearchUserQuery;
use SearchUser;
use UserQuery;
use User as DbUser;

class UserSearch {

    public function __construct(array $searchQueries) {
        $this->searchQueries = array_map('trim', $searchQueries);
        $this->searchQueries = array_map('strtoupper', $this->searchQueries);
    }

    /** @var String[] */
    private $searchQueries;

    /** @var DbUser[] */
    private $userResults = [];

    public function getResults() {
        return $this->userResults;
    }

    public function search() {
        $this->userResults = $this->searchOne(implode(' ', $this->searchQueries));
        return $this->userResults;
    }

    /**
     * @param string $searchString
     * @return DbUser[]
     */
    private function searchOne($searchString) {
        // Use a FullTextSearch to match the search query
        // to the query column on the SearchUser table
        /** @var SearchUser[] $indexedUsers */
        $indexedUsers = SearchUserQuery::create()
            ->fullTextSearch($searchString)
            ->joinWithUser()
            ->find()
            ->getData();

        // Select all found ids into the result holder
        return array_map(function(SearchUser $searchUser) {
            return $searchUser->getUser();
        }, $indexedUsers);
    }

}

