<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Queries\User;

use UserLocationFavoriteQuery;
use Map\UserLocationFavoriteTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Map\UserConnectionTableMap;
use Propel\Runtime\Propel;
use EConnectionState;

class UserQueriesWrapper {


    /**
     * This method returns all the friends of ${userIds} unique
     *
     * ---- Db testing Query
     * SELECT IF(user_id IN (1), connection_id, user_id) as id, COUNT(*)
     * FROM user_connection
     * WHERE user_id IN (1) OR connection_id IN (1)
     * GROUP BY id
     * ORDER BY COUNT(*) DESC
     * ----
     * @return int[] */
    public static function getUsersFriendIds(array $uids) {
        if (sizeof($uids) <= 0)
            return [];

        // This query is too complicated for the propel api
        // Use custom sql
        $connection = Propel::getReadConnection(UserConnectionTableMap::DATABASE_NAME);
        $statement = $connection->prepare(strtr(
            "SELECT {result_col_name} FROM (" .
            "SELECT IF({user_id} IN ({ids}), {connection_to}, {user_id}) as {result_col_name}, COUNT(*) " .
            "FROM {user_connection} " .
            "WHERE ({user_id} IN ({ids}) OR {connection_to} IN ({ids})) AND {col_state} = {state} " .
            "GROUP BY  {result_col_name} " .
            "ORDER BY COUNT(*) DESC" .
            ") AS x",
            [
                '{user_connection}' => UserConnectionTableMap::TABLE_NAME,
                '{user_id}' => UserConnectionTableMap::COL_USER_ID,
                '{connection_to}' => UserConnectionTableMap::COL_CONNECTION_ID,
                '{col_state}' => UserConnectionTableMap::COL_STATE,
                '{state}' => EConnectionState::CONFIRMED,
                '{result_col_name}' => 'id',
                '{ids}' => implode(',', $uids)
            ]
        ));
        $statement->execute();

        $result = array_map(
            function($row) { return intval($row['id']); },
            $statement->fetchAll(\PDO::FETCH_ASSOC)
        );

        return $result;
    }

    /**
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
        $result = [];       // array(friendId => [friendsFriendId, friendsFriendsId, ...])

        if (sizeof($uids) <= 0)
            return $result;

        // This query is too complicated for the propel api
        // Use custom sql
        $connection = Propel::getReadConnection(UserConnectionTableMap::DATABASE_NAME);
        $statement = $connection->prepare(strtr(
            "SELECT " .
            "IF({user_id} IN ({ids}), {user_id}, {connection_id}) as {result_col_name_1}, " .
            "IF({user_id} IN ({ids}), {connection_id}, {user_id}) as {result_col_name_2} " .
            "FROM {user_connection} " .
            "WHERE {user_id} IN ({ids}) OR {connection_id} IN ({ids})",
            [
                '{user_id}' => UserConnectionTableMap::COL_USER_ID,
                '{connection_id}' => UserConnectionTableMap::COL_CONNECTION_ID,
                '{user_connection}' => UserConnectionTableMap::TABLE_NAME,
                '{result_col_name_1}' => 'id1',
                '{result_col_name_2}' => 'id2',
                '{ids}' => implode(',', $uids)
            ]
        ));
        $statement->execute();

        $fetch = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($fetch as $row) {
            $id1 = intval($row['id1']);
            $id2 = intval($row['id2']);

            if (!key_exists($id1, $result))
                $result[$id1] = [];

            array_push($result[$id1], $id2);
        }

        return $result;
    }

}