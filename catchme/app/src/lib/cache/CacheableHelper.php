<?php /** Created by Krishan Marco Madan on 04-Jun-18. */

namespace cache;

use Map\UserConnectionTableMap;
use Propel\Runtime\Propel;

class CacheableHelper {

    public static function terraform() {

        // Create all the cache tables
        foreach (CacheableConstants::CACHE_TABLES as $tableName)
            CacheableHelper::createCacheTable($tableName);

    }


    private static function createCacheTable($tableName) {
        $connection = Propel::getReadConnection(UserConnectionTableMap::DATABASE_NAME);

        $params = [
            '{tbl_name}' => $tableName,
            '{col_id}' => CacheableConstants::SQL_COL_CACHE_ID,
            '{col_data}' => CacheableConstants::SQL_COL_CACHE_DATA,
            '{col_insert_ts}' => CacheableConstants::SQL_COL_CACHE_INSERT_TS
        ];

        $connection
            ->prepare(strtr("DROP TABLE IF EXISTS {tbl_name};", $params))
            ->execute();

        $connection->prepare(strtr(
            "CREATE TABLE {tbl_name} (" .
            "{col_id} INTEGER NOT NULL, " .
            "{col_data} MEDIUMTEXT NOT NULL, " .
            "{col_insert_ts} TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
            "PRIMARY KEY ({col_id})" .
            ") ENGINE=MyISAM;",
            $params
        ))->execute();

    }

}