<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 14/09/2017 */

namespace Models\User\Accounts;

use Propel\Runtime\Exception\PropelException;
use User as DbUser;
use UserLocationFavorite as DbUserLocationFavorite;
use UserLocationFavoriteQuery;

class UserManagerLocations {

    public function __construct(DbUser $user) {
        $this->user = $user;
    }


    /** @var DbUser $user */
    private $user;


    public function add($locationId) {
        $userFavoriteLocation = new DbUserLocationFavorite();
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