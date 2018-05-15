<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Queries\User;

use UserLocationFavoriteQuery;
use UserConnection;
use UserConnectionQuery;
use Map\UserLocationFavoriteTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Map\UserConnectionTableMap;
use Propel\Runtime\Propel;

class UserQueriesWrapper {




    /** Returns an [optionally ordered] int array of all location
     * ids that the input array of user ids are subscribed to
     *
     * @param int[] $userIds        Array of user ids
     * @return int[]                Location ids that $userIds are subscribed to
     */
    public static function getUsersLocationIds(array $userIds) {

        $userFavoriteLocationQuery = UserLocationFavoriteQuery::create()
            ->select([UserLocationFavoriteTableMap::COL_LOCATION_ID])
            ->add(UserLocationFavoriteTableMap::COL_USER_ID, $userIds, Criteria::IN)
            ->groupByLocationId()
            ->withColumn('COUNT(*)', 'Count')
            ->orderBy('Count', Criteria::DESC);;

        $locationIds = $userFavoriteLocationQuery->find()->getData();

        // Note: Using select in the Propel query makes it return
        // a string array as location ids
        // Get all location ids mapping them from strings to ints
        $locationIds = array_map(
            function($ufl) { return intval($ufl[UserLocationFavoriteTableMap::COL_LOCATION_ID]); },
            $locationIds
        );

        return $locationIds;
    }



    /** @return int[] */
    public static function getUsersFriendIds(array $userIds) {
        if (sizeof($userIds) <= 0)
            return [];

        // This query is too complicated for the propel api
        // Use custom sql
        $connection = Propel::getReadConnection(UserConnectionTableMap::DATABASE_NAME);
        $statement = $connection->prepare(strtr(
            "SELECT {result_col_name} FROM (" .
            "SELECT IF({user_id} IN ({ids}), {connection_to}, {user_id}) as {result_col_name}, COUNT(*) " .
            "FROM {user_connection} " .
            "WHERE ({user_id} IN ({ids}) OR {connection_to} IN ({ids})) " .
            "GROUP BY  {result_col_name} " .
            "ORDER BY COUNT(*) DESC" .
            ") AS x",
            [
                '{user_id}' => UserConnectionTableMap::COL_USER_ID,
                '{connection_to}' => UserConnectionTableMap::COL_CONNECTION_ID,
                '{user_connection}' => UserConnectionTableMap::TABLE_NAME,
                '{result_col_name}' => 'id',
                '{ids}' => implode(',', $userIds)
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
     SELECT user_id, id FROM (
        SELECT user_id, IF(user_id IN (1, 2, 3), connection_id, user_id) AS id, COUNT(*)
        FROM user_connection
        WHERE (user_id IN (1, 2, 3) OR connection_id IN (1, 2, 3))
        GROUP BY id ORDER BY COUNT(*) DESC
     ) AS x ORDER BY user_id ASC , id ASC
    */
    /** @return array(friendId => [friendsFriendId, friendsFriendsId, ...]) */
    public static function getUsersFriendsIdsGroupedByUserId(array $userIds) {
        $result = [];       // array(friendId => [friendsFriendId, friendsFriendsId, ...])

        if (sizeof($userIds) <= 0)
            return $result;

        // This query is too complicated for the propel api
        // Use custom sql
        $connection = Propel::getReadConnection(UserConnectionTableMap::DATABASE_NAME);
        $statement = $connection->prepare(strtr(
            "SELECT {user_id}, {result_col_name} FROM (" .
            "SELECT IF({user_id} IN ({ids}), {connection_to}, {user_id}) as {result_col_name}, COUNT(*) " .
            "FROM {user_connection} " .
            "WHERE ({user_id} IN ({ids}) OR {connection_to} IN ({ids})) " .
            "GROUP BY  {result_col_name}" .
            ") AS x",
            [
                '{user_id}' => UserConnectionTableMap::COL_USER_ID,
                '{connection_to}' => UserConnectionTableMap::COL_CONNECTION_ID,
                '{user_connection}' => UserConnectionTableMap::TABLE_NAME,
                '{result_col_name}' => 'id',
                '{ids}' => implode(',', $userIds)
            ]
        ));
        $statement->execute();


        $fetch = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($fetch as $row) {
            $userId = intval($row['user_id']);
            $id = intval($row['id']);

            if (!key_exists($userId, $result))
                $result[$userId] = [];

            array_push($result[$userId], $id);
        }

        return $result;
    }




    /** @return null|UserConnection */
    public static function findUsersConnection($user1, $user2)  {

    }


}