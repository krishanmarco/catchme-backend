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


class ControllerSearch {
    
    public function __construct(DbUser $authenticatedUser) {
        $this->authenticatedUser = $authenticatedUser;
    }
    
    /** @var DbUser $authenticatedUser */
    private $authenticatedUser;




    /** @return ApiLocation[] */
    public function locationsSearch($query) {
        $searchLocations = new LocationSearch($query);

        $searchLocations->search();

        return ModelToApiLocations::multiple()
            ->locations($searchLocations->getResults());
    }




    public function locationsSuggested($seed) {
        $userModel = UserModel::fromUser($this->authenticatedUser);

        $suggestedLocations = $userModel->getSuggestedLocationResult($seed);

        return ModelToApiLocations::multiple()
            ->locations($suggestedLocations->getSuggestedLocations());
    }




    /** @return ApiUser[] */
    public function usersSearch($query) {
        $userSearch = new UserSearch($query);

        $userSearch->search();

        return ModelToApiUsers::multiple()
            ->users($userSearch->getResults());
    }



    public function usersSuggested($seed) {
        $userModel = UserModel::fromUser($this->authenticatedUser);

        $userSuggested = $userModel->getUserSuggestedFriendsResult($seed);

        return ModelToApiUsers::multiple()
            ->users($userSuggested->getSuggestedFriends());
    }

}