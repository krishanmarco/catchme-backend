<?php /** Created by Krishan Marco Madan on 04-Jun-18. */

namespace cache;

class CacheableConstants {
    const SQL_COL_CACHE_ID = 'id';
    const SQL_COL_CACHE_DATA = 'data';
    const SQL_COL_CACHE_INSERT_TS = 'insert_ts';

    const CACHE_TABLE_BASE = 'cache_';
    const CACHE_TABLE_USER_SUGGESTED_LOCATION = self:: CACHE_TABLE_BASE . 'user_suggested_location';

    const CACHE_TABLES = [
        self::CACHE_TABLE_USER_SUGGESTED_LOCATION
    ];

    const CACHE_TTL_SECS = [
        self::CACHE_TABLE_USER_SUGGESTED_LOCATION => 1 * 60 * 60        // 1 hour
    ];

}