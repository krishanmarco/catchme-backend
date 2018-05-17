<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 09/10/2017 - Fithancer © */

namespace Models\Feed;
use Models\Calculators\LocationModel;
use Models\Calculators\UserModel;

class MultiNotificationManager {

    /**
     * Gets the current users (based on $userModel)
     * friends that can be notified of an event
     * ----------------------------
     * @return int[]
     */
    public function getUidsInterestedInUser($userId) {
        $connectionStrengths = UserModel::fromId($userId)
            ->getUserConnectionManager()
            ->getConnectionStrengths();

        // Development
        // For now (testing) spam all connections, later
        // Get connections that have strengths between [-0.5, +0.5] after
        // having checked if this interval can be considered realistic
        return array_keys($connectionStrengths);
    }


    public function getUidsInterestedInLocation($locationId) {
        return LocationModel::fromId($locationId)
            ->getLocationFollowers()
            ->getResultIds();
    }



}