<?php

use Base\UserConnectionQuery as BaseUserConnectionQuery;
use Map\UserConnectionTableMap;
use Propel\Runtime\Propel;
use EConnectionState;

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


    /**
     * This query is too complicated for the propel API
     * This method returns all the friends of ${userIds} unique
     *
     * ---- Db testing Query
     * SELECT IF(user_id IN (1), connection_id, user_id) as id, COUNT(*)
     * FROM user_connection
     * WHERE user_id IN (1) OR connection_id IN (1) AND state = 1
     * GROUP BY id
     * ORDER BY COUNT(*) DESC
     * ----
     * @return int[] */
    public static function getUsersFriendIds(array $uids) {
        if (sizeof($uids) <= 0)
            return [];

        $connection = Propel::getReadConnection(UserConnectionTableMap::DATABASE_NAME);
        $statement = $connection->prepare(strtr(
            "SELECT {col_res} FROM (" .
            "SELECT IF({val_user_id} IN ({col_id}), {col_connection_id}, {col_user_id}) as {col_res}, COUNT(*) " .
            "FROM {tbl_name} " .
            "WHERE ({val_user_id} IN ({col_id_val}) OR {col_connection_id} IN ({col_id_val})) AND {col_state} = {col_state} " .
            "GROUP BY  {col_res} " .
            "ORDER BY COUNT(*) DESC" .
            ") AS x",
            [
                '{tbl_name}' => UserConnectionTableMap::TABLE_NAME,
                '{col_user_id}' => UserConnectionTableMap::COL_USER_ID,
                '{col_connection_id}' => UserConnectionTableMap::COL_CONNECTION_ID,
                '{col_state}' => UserConnectionTableMap::COL_STATE,
                '{col_res}' => 'id',
                '{val_state}' => EConnectionState::CONFIRMED,
                '{val_id}' => implode(',', $uids)
            ]
        ));
        $statement->execute();

        return array_map(
            function($row) { return intval($row['id']); },
            $statement->fetchAll(\PDO::FETCH_ASSOC)
        );
    }


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
