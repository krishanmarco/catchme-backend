<?php

use Base\UserLocation as BaseUserLocation;

/**
 * Skeleton subclass for representing a row from the 'user_location' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserLocation extends BaseUserLocation {

    /**
     * @param UserLocation[] $ulList
     * @return int[]
     */
    public static function mapToUserIds(array $ulList) {
        return array_map(function (UserLocation $userLocation) { return $userLocation->getUserId(); }, $ulList);
    }

}
