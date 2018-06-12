<?php

use Base\UserLocationFavorite as BaseUserLocationFavorite;

/**
 * Skeleton subclass for representing a row from the 'user_location_favorite' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserLocationFavorite extends BaseUserLocationFavorite {

    /**
     * @param UserLocationFavorite[] $ulfList
     * @return int[]
     */
    public static function mapToUserIds(array $ulfList) {
        return array_map(function (UserLocationFavorite $ulf) { return $ulf->getUserId(); }, $ulfList);
    }

}
