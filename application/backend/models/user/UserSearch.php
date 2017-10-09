<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Location\Search;
use Propel\Extensions\QueryHelper;
use Map\SearchUserTableMap;
use SearchUserQuery;
use SearchUser;
use UserQuery;
use User;


class UserSearch {

    public function __construct($searchQuery) {
        $this->searchQuery = strtoupper(trim($searchQuery));
    }


    /** @var String $searchQuery */
    private $searchQuery;



    /** @var array $userResults : [User-Id => User] */
    private $userResults = [];

    public function getResults() { return array_values($this->userResults); }

    private function addLocationToResult(User $user) {
        $this->userResults[$user->getId()] = $user;
    }




    public function search() {

        // Use a FullTextSearch to match the search query
        // to the query column on the SearchUser table
        /** @var SearchUser[] $indexedUsers */
        $indexedUsers = QueryHelper::fullTextSearch(
            SearchUserQuery::create(),
            SearchUserTableMap::COL_QUERY,
            $this->searchQuery
        );

        // Get all user ids from the SearchUser
        // rows that matched the search query
        $userIds = [];
        foreach ($indexedUsers as $il)
            $userIds[] = $il->getUserId();


        // Select all found ids into the result holder
        $this->userResults = UserQuery::create()
            ->findPks($userIds)
            ->getData();

    }


}

