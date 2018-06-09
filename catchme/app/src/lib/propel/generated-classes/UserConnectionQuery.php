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
            "SELECT IF({col_user_id} IN ({val_user_id}), {col_connection_id}, {col_user_id}) as {col_res}, COUNT(*) " .
            "FROM {tbl_name} " .
            "WHERE ({col_user_id} IN ({val_user_id}) OR {col_connection_id} IN ({val_user_id}) AND {col_state} = {val_state}) " .
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
                '{val_user_id}' => implode(',', $uids)
            ]
        ));
        $statement->execute();

        return array_map(
            function($row) { return intval($row['id']); },
            $statement->fetchAll(\PDO::FETCH_ASSOC)
        );
    }
    /**
     * This query is too complicated for the propel API
     * This method returns the friends of all the ids in the {$userIds} field
     * in a way that if both {x} and {y} are in {userIds} and {x friends y} then
     * either {x} will be indicated in {y}s friends or {y} will be indicated in {x}s friends
     * but not both.
     *
     * ---- Db testing Query
     * SELECT
     *  IF(user_id IN (2, 3, 4), user_id, connection_id) AS id1,
     *  IF(user_id IN (2, 3, 4), connection_id, user_id) AS id2
     * FROM user_connection
     * WHERE (user_id IN (2, 3, 4) OR connection_id IN (2, 3, 4))
     * ORDER BY id1 ASC, id2 ASC
     * ----
     * @return array(friendId => [friendsFriendId, friendsFriendsId, ...]) */
    public static function getUsersFriendsIdsGroupedByUserIdUnique(array $uids) {
        if (sizeof($uids) <= 0)
            return [];

        $connection = Propel::getReadConnection(UserConnectionTableMap::DATABASE_NAME);
        $statement = $connection->prepare(strtr(
            "SELECT " .
            "IF({col_user_id} IN ({val_id}), {col_user_id}, {col_connection_id}) as {col_res_1}, " .
            "IF({col_user_id} IN ({val_id}), {col_connection_id}, {col_user_id}) as {col_res_2} " .
            "FROM {tbl_name} " .
            "WHERE {col_user_id} IN ({val_id}) OR {col_connection_id} IN ({val_id})",
            [
                '{tbl_name}' => UserConnectionTableMap::TABLE_NAME,
                '{col_user_id}' => UserConnectionTableMap::COL_USER_ID,
                '{col_connection_id}' => UserConnectionTableMap::COL_CONNECTION_ID,
                '{col_res_1}' => 'id1',
                '{col_res_2}' => 'id2',
                '{val_id}' => implode(',', $uids)
            ]
        ));
        $statement->execute();
        $fetch = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($fetch as $row) {
            $id1 = intval($row['id1']);
            $id2 = intval($row['id2']);

            if (!key_exists($id1, $result))
                $result[$id1] = [];

            array_push($result[$id1], $id2);
        }

        return $result;
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
