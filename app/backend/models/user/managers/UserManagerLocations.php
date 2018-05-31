<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 14/09/2017 - Fithancer © */

namespace Models\User\Accounts;
use Propel\Runtime\Exception\PropelException;
use Slim\Exception\Api400;
use UserLocationFavoriteQuery;
use UserLocationFavorite;
use User;
use R;

class UserManagerLocations {

    public function __construct(User $user) {
        $this->user = $user;
    }


    /** @var User $user */
    private $user;



    public function add($locationId) {
        $userFavoriteLocation = new UserLocationFavorite();
        $userFavoriteLocation->setUserId($this->user->getId());
        $userFavoriteLocation->setLocationId($locationId);

        try {
            $userFavoriteLocation->save();

        } catch (PropelException $exception) {
            // Don't throw an exception
            // the wanted status is already set
        }
    }



    public function del($locationId) {

        $userFavoriteLocation = UserLocationFavoriteQuery::create()
            ->filterByUserId($this->user->getId())
            ->filterByLocationId($locationId)
            ->findOne();

        // If $userFavoriteLocation is not set
        // Don't throw an exception
        // the wanted status is already set
        if (!is_null($userFavoriteLocation))
            $userFavoriteLocation->delete();
    }


}