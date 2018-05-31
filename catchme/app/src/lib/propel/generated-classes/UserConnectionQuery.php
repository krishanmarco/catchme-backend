<?php

use Base\UserConnectionQuery as BaseUserConnectionQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'user_connection' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserConnectionQuery extends BaseUserConnectionQuery {


    public function filterByConnectionIds($uid1, $uid2) {

        $whereLeft = strtr('{colLeft} = ?', [
            '{table}' => \Map\UserConnectionTableMap::CLASS_DEFAULT,
            '{colLeft}' => \Map\UserConnectionTableMap::COL_USER_ID
        ]);

        $whereRight = strtr('{colRight} = ?', [
            '{table}' => \Map\UserConnectionTableMap::CLASS_DEFAULT,
            '{colRight}' => \Map\UserConnectionTableMap::COL_CONNECTION_ID
        ]);

        return $this
            ->condition('left1', $whereLeft, $uid1)
            ->condition('right2', $whereRight, $uid2)
            ->combine(['left1', 'right2'], 'and', 'leftFrame')
            ->condition('left2', $whereLeft, $uid2)
            ->condition('right1', $whereRight, $uid1)
            ->combine(['left2', 'right1'], 'and', 'rightFrame')
            ->where(['leftFrame', 'rightFrame'], 'or');
    }

}
