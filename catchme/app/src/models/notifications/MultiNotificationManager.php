<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 09/10/2017 */

namespace Models\Feed;
use Models\Calculators\LocationModel;
use Models\Calculators\UserModel;

abstract class MultiNotificationManager {

    /**
     * Gets the current users (based on $userModel)
     * friends that can be notified of an event
     * ----------------------------
     * @return int[]
     */
    public static function uidsInterestedInUser($userId) {
        $connectionStrengths = UserModel::fromId($userId)
            ->getUserConnectionManager()
            ->getConnectionStrengths();

        // Development
        // For now (testing) spam all connections, later
        // Get connections that have strengths between [-0.5, +0.5] after
        // having checked if this interval can be considered realistic
        return array_keys($connectionStrengths);
    }

    /**
     * Gets the current users (based on $locationModel)
     * favorites that can be notified of an event
     * ----------------------------
     * @return int[]
     */
    public static function uidsInterestedInLocation($locationId) {
        return LocationModel::fromId($locationId)
            ->getLocationFollowers()
            ->getResultIds();
    }

}