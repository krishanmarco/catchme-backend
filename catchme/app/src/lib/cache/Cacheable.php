<?php /** Created by Krishan Marco Madan on 04-Jun-18. */

namespace cache;

use Map\UserConnectionTableMap;
use Propel\Runtime\Propel;
use Closure;

abstract class Cacheable {

    public function __construct($cacheTableName, $cacheEntryId, Closure $calculateData) {
        $this->cacheTableName = $cacheTableName;
        $this->cacheEntryId = $cacheEntryId;
        $this->calculateData = $calculateData;
    }

    /** @var string */
    private $cacheTableName;

    /** @var int|string */
    private $cacheEntryId;

    /** @var Closure */
    private $calculateData;

    /** @var null|mixed */
    private $data;

    /** @var boolean */
    private $dataLoaded = false;

    /** @return mixed */
    public function getCachedData() {

        if (!$this->dataLoaded)
            $this->getData();

        if (is_null($this->data))
            $this->refreshData();

        return $this->data;
    }

    private function getData() {
        // Set the data as loaded regardless of the result
        $this->dataLoaded = true;

        $result = $this->queryGetData();
        if (is_null($result))
            return;

        // The entry exists
        $insertTs = $result[CacheableConstants::SQL_COL_CACHE_INSERT_TS];
        $dataExpiry = intval($insertTs) + CacheableConstants::CACHE_TTL_SECS[$this->cacheTableName];
        if (time() >= $dataExpiry) {
            // The data is not valid anymore, delete and return null
            $this->queryDeleteData();
            return;
        }

        // The data is still valid
        $this->data = json_decode($result[CacheableConstants::SQL_COL_CACHE_DATA], true);
    }

    private function refreshData() {
        // Calculate the data and set as loaded
        $this->data = $this->calculateData->call($this);
        $this->dataLoaded = true;

        // Save the data
        $this->querySetData(json_encode($this->data));
    }

    private function queryGetData() {
        $statement = Propel::getReadConnection(UserConnectionTableMap::DATABASE_NAME)
            ->prepare(strtr(
                "SELECT {col_data}, {col_insert_ts} " .
                "FROM {tbl_name} " .
                "WHERE {col_id} = {col_id_val}",
                [
                    '{tbl_name}' => $this->cacheTableName,
                    '{col_id}' => CacheableConstants::SQL_COL_CACHE_ID,
                    '{col_data}' => CacheableConstants::SQL_COL_CACHE_DATA,
                    '{col_insert_ts}' => CacheableConstants::SQL_COL_CACHE_INSERT_TS,
                    '{col_id_val}' => $this->cacheEntryId
                ]
            ));
        $statement->execute();
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if (sizeof($results) <= 0)
            return null;

        return $results[0];
    }

    private function querySetData($dataStr) {
        Propel::getReadConnection(UserConnectionTableMap::DATABASE_NAME)
            ->prepare(strtr(
                "INSERT INTO {tbl_name} ({col_id}, {col_data}, {col_insert_ts}) " .
                "VALUES ({col_id_val}, {col_data_val}, {col_insert_ts_val}) " .
                "ON DUPLICATE KEY UPDATE {col_data}={col_data_val}, {col_insert_ts}={col_insert_ts_val};",
                [
                    '{tbl_name}' => $this->cacheTableName,
                    '{col_id}' => CacheableConstants::SQL_COL_CACHE_ID,
                    '{col_data}' => CacheableConstants::SQL_COL_CACHE_DATA,
                    '{col_insert_ts}' => CacheableConstants::SQL_COL_CACHE_INSERT_TS,
                    '{col_id_val}' => $this->cacheEntryId,
                    '{col_data_val}' => $dataStr,
                    '{col_insert_ts_val}' => time(),
                ]
            ))
            ->execute();
    }

    private function queryDeleteData() {
        Propel::getReadConnection(UserConnectionTableMap::DATABASE_NAME)
            ->prepare(strtr("DELETE FROM {tbl_name} WHERE {col_id} = {id};", [
                '{tbl_name}' => $this->cacheTableName,
                '{col_id}' => CacheableConstants::SQL_COL_CACHE_ID,
                '{id}' => $this->cacheEntryId
            ]))
            ->execute();
    }

}