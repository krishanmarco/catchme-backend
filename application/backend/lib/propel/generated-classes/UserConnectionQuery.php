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

        $whereClause = strtr('{table}.{colLeft} = ? AND {table}.{colRight} = ?', [
            '{table}' => \Map\UserConnectionTableMap::CLASS_DEFAULT,
            '{colLeft}' => \Map\UserConnectionTableMap::COL_USER_ID,
            '{colRight}' => \Map\UserConnectionTableMap::COL_CONNECTION_ID
        ]);

        $this
            ->where($whereClause, $uid1, $uid2)
            ->_or()
            ->where($whereClause, $uid2, $uid1);

        return $this;
    }

}
