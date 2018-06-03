<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;

use Models\Calculators\UserModel;
use Models\Location\Search\LocationSearch;
use Models\Location\Search\UserSearch;
use User as DbUser;
use Api\Map\ModelToApiLocations;
use Api\Map\ModelToApiUsers;
use Api\Location as ApiLocation;
use Api\User as ApiUser;
use Api\SearchStrings as ApiSearchStrings;

class ControllerSearch {

    public function __construct(DbUser $authUser) {
        $this->authUser = $authUser;
    }

    /** @var DbUser $authUser */
    private $authUser;


    /** @return ApiLocation[] */
    public function locationsSearch($query) {
        $searchLocations = new LocationSearch([$query]);
        $searchLocations->search();
        return ModelToApiLocations::multiple()->locations($searchLocations->getResults());
    }

    /** @return ApiLocation[] */
    public function locationsSuggested($seed) {
        $userModel = UserModel::fromUser($this->authUser);
        $locations = $userModel->getUserSuggestedLocations($seed, null)->getResult()->suggestedLocations;
        return ModelToApiLocations::multiple()->locations($locations);
    }

    /** @return ApiUser[] */
    public function usersSearch($query) {
        $userSearch = new UserSearch([$query]);
        $userSearch->search();
        return ModelToApiUsers::multiple()->users($userSearch->getResults());
    }

    /** @return ApiUser[] */
    public function usersSearchMultiple(ApiSearchStrings $apiSearchStrings) {
        $userSearch = new UserSearch($apiSearchStrings->queries);
        $userSearch->search();
        return ModelToApiUsers::multiple()->users($userSearch->getResults());
    }

    /** @return ApiUser[] */
    public function usersSuggested($seed) {
        $userModel = UserModel::fromUser($this->authUser);
        $suggested = $userModel->getUserSuggestedFriends($seed)->getResult()->suggestedFriends;
        return ModelToApiUsers::multiple()->users($suggested);
    }
}