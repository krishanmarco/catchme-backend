<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators;

use Location as DbLocation;
use User as DbUser;
use Models\Calculators\JoinedLocationUser\LocationFriends;

class JoinedLocationUserModel {

    private static $JoinedLocationUserModel = [
        // array of type "{lid}-{uid}" => JoinedLocationUserModel
    ];

    /** @return JoinedLocationUserModel */
    public static function fromIds($lid, $uid) {
        $id = "$lid-$uid";

        if (array_key_exists($id, self::$JoinedLocationUserModel))
            return self::$JoinedLocationUserModel[$id];

        $location = LocationModel::fromId($lid)->getLocation();
        $user = UserModel::fromId($uid)->getUser();
        self::$JoinedLocationUserModel[$id] = new JoinedLocationUserModel($location, $user);

        return self::$JoinedLocationUserModel[$lid];
    }

    public function __construct(DbLocation $location, DbUser $user) {
        $this->location = $location;
        $this->user = $user;
    }

    /** @var DbLocation */
    private $location;

    /** @var DbUser */
    private $user;

    /** @var LocationFriends $locationFriends */
    private $locationFriends;


    /** @return DbLocation */
    public function getLocation() {
        return $this->location;
    }

    /** @return DbUser */
    public function getUser() {
        return $this->user;
    }

    /** @return LocationFriends */
    public function getLocationFriends() {
        if (is_null($this->locationFriends))
            $this->locationFriends = new LocationFriends($this->location, $this->user);
        return $this->locationFriends;
    }


}