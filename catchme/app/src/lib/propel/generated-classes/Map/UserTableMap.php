<?php

namespace Map;

use \User;
use \UserQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.UserTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'catch_me';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'user';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\User';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'User';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 17;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 17;

    /**
     * the column name for the id field
     */
    const COL_ID = 'user.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'user.name';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'user.email';

    /**
     * the column name for the api_key field
     */
    const COL_API_KEY = 'user.api_key';

    /**
     * the column name for the pass_sha256 field
     */
    const COL_PASS_SHA256 = 'user.pass_sha256';

    /**
     * the column name for the pass_salt field
     */
    const COL_PASS_SALT = 'user.pass_salt';

    /**
     * the column name for the ban field
     */
    const COL_BAN = 'user.ban';

    /**
     * the column name for the signup_ts field
     */
    const COL_SIGNUP_TS = 'user.signup_ts';

    /**
     * the column name for the gender field
     */
    const COL_GENDER = 'user.gender';

    /**
     * the column name for the reputation field
     */
    const COL_REPUTATION = 'user.reputation';

    /**
     * the column name for the setting_privacy field
     */
    const COL_SETTING_PRIVACY = 'user.setting_privacy';

    /**
     * the column name for the setting_notifications field
     */
    const COL_SETTING_NOTIFICATIONS = 'user.setting_notifications';

    /**
     * the column name for the access_level field
     */
    const COL_ACCESS_LEVEL = 'user.access_level';

    /**
     * the column name for the phone field
     */
    const COL_PHONE = 'user.phone';

    /**
     * the column name for the public_message field
     */
    const COL_PUBLIC_MESSAGE = 'user.public_message';

    /**
     * the column name for the picture_url field
     */
    const COL_PICTURE_URL = 'user.picture_url';

    /**
     * the column name for the lang field
     */
    const COL_LANG = 'user.lang';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Email', 'ApiKey', 'PassSha256', 'PassSalt', 'Ban', 'SignupTs', 'Gender', 'Reputation', 'SettingPrivacy', 'SettingNotifications', 'AccessLevel', 'Phone', 'PublicMessage', 'PictureUrl', 'Lang', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'email', 'apiKey', 'passSha256', 'passSalt', 'ban', 'signupTs', 'gender', 'reputation', 'settingPrivacy', 'settingNotifications', 'accessLevel', 'phone', 'publicMessage', 'pictureUrl', 'lang', ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID, UserTableMap::COL_NAME, UserTableMap::COL_EMAIL, UserTableMap::COL_API_KEY, UserTableMap::COL_PASS_SHA256, UserTableMap::COL_PASS_SALT, UserTableMap::COL_BAN, UserTableMap::COL_SIGNUP_TS, UserTableMap::COL_GENDER, UserTableMap::COL_REPUTATION, UserTableMap::COL_SETTING_PRIVACY, UserTableMap::COL_SETTING_NOTIFICATIONS, UserTableMap::COL_ACCESS_LEVEL, UserTableMap::COL_PHONE, UserTableMap::COL_PUBLIC_MESSAGE, UserTableMap::COL_PICTURE_URL, UserTableMap::COL_LANG, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'email', 'api_key', 'pass_sha256', 'pass_salt', 'ban', 'signup_ts', 'gender', 'reputation', 'setting_privacy', 'setting_notifications', 'access_level', 'phone', 'public_message', 'picture_url', 'lang', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Email' => 2, 'ApiKey' => 3, 'PassSha256' => 4, 'PassSalt' => 5, 'Ban' => 6, 'SignupTs' => 7, 'Gender' => 8, 'Reputation' => 9, 'SettingPrivacy' => 10, 'SettingNotifications' => 11, 'AccessLevel' => 12, 'Phone' => 13, 'PublicMessage' => 14, 'PictureUrl' => 15, 'Lang' => 16, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'email' => 2, 'apiKey' => 3, 'passSha256' => 4, 'passSalt' => 5, 'ban' => 6, 'signupTs' => 7, 'gender' => 8, 'reputation' => 9, 'settingPrivacy' => 10, 'settingNotifications' => 11, 'accessLevel' => 12, 'phone' => 13, 'publicMessage' => 14, 'pictureUrl' => 15, 'lang' => 16, ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID => 0, UserTableMap::COL_NAME => 1, UserTableMap::COL_EMAIL => 2, UserTableMap::COL_API_KEY => 3, UserTableMap::COL_PASS_SHA256 => 4, UserTableMap::COL_PASS_SALT => 5, UserTableMap::COL_BAN => 6, UserTableMap::COL_SIGNUP_TS => 7, UserTableMap::COL_GENDER => 8, UserTableMap::COL_REPUTATION => 9, UserTableMap::COL_SETTING_PRIVACY => 10, UserTableMap::COL_SETTING_NOTIFICATIONS => 11, UserTableMap::COL_ACCESS_LEVEL => 12, UserTableMap::COL_PHONE => 13, UserTableMap::COL_PUBLIC_MESSAGE => 14, UserTableMap::COL_PICTURE_URL => 15, UserTableMap::COL_LANG => 16, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'email' => 2, 'api_key' => 3, 'pass_sha256' => 4, 'pass_salt' => 5, 'ban' => 6, 'signup_ts' => 7, 'gender' => 8, 'reputation' => 9, 'setting_privacy' => 10, 'setting_notifications' => 11, 'access_level' => 12, 'phone' => 13, 'public_message' => 14, 'picture_url' => 15, 'lang' => 16, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('user');
        $this->setPhpName('User');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\User');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 255, null);
        $this->addColumn('api_key', 'ApiKey', 'VARCHAR', true, 32, null);
        $this->addColumn('pass_sha256', 'PassSha256', 'VARCHAR', true, 64, null);
        $this->addColumn('pass_salt', 'PassSalt', 'VARCHAR', true, 15, null);
        $this->addColumn('ban', 'Ban', 'BOOLEAN', true, 1, true);
        $this->addColumn('signup_ts', 'SignupTs', 'INTEGER', true, 10, 1483228800);
        $this->addColumn('gender', 'Gender', 'TINYINT', true, 1, 0);
        $this->addColumn('reputation', 'Reputation', 'INTEGER', true, null, 0);
        $this->addColumn('setting_privacy', 'SettingPrivacy', 'VARCHAR', true, 255, '222');
        $this->addColumn('setting_notifications', 'SettingNotifications', 'VARCHAR', true, 255, '11111');
        $this->addColumn('access_level', 'AccessLevel', 'TINYINT', true, null, 0);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 255, null);
        $this->addColumn('public_message', 'PublicMessage', 'VARCHAR', false, 255, null);
        $this->addColumn('picture_url', 'PictureUrl', 'VARCHAR', false, 255, null);
        $this->addColumn('lang', 'Lang', 'VARCHAR', false, 8, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Social', '\\UserSocial', RelationMap::ONE_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('InsertedLocation', '\\Location', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':admin_id',
    1 => ':id',
  ),
), null, null, 'InsertedLocations', false);
        $this->addRelation('SearchString', '\\SearchUser', RelationMap::ONE_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('FavoriteLocation', '\\UserLocationFavorite', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'FavoriteLocations', false);
        $this->addRelation('AddedConnection', '\\UserConnection', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'AddedConnections', false);
        $this->addRelation('RequestedConnection', '\\UserConnection', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':connection_id',
    1 => ':id',
  ),
), null, null, 'RequestedConnections', false);
        $this->addRelation('Location', '\\UserLocation', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Locations', false);
        $this->addRelation('ExpiredLocation', '\\UserLocationExpired', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'ExpiredLocations', false);
        $this->addRelation('InsertedImage', '\\LocationImage', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':inserter_id',
    1 => ':id',
  ),
), null, null, 'InsertedImages', false);
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to user     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SearchUserTableMap::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? UserTableMap::CLASS_DEFAULT : UserTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (User object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserTableMap::OM_CLASS;
            /** @var User $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = UserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var User $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(UserTableMap::COL_ID);
            $criteria->addSelectColumn(UserTableMap::COL_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(UserTableMap::COL_API_KEY);
            $criteria->addSelectColumn(UserTableMap::COL_PASS_SHA256);
            $criteria->addSelectColumn(UserTableMap::COL_PASS_SALT);
            $criteria->addSelectColumn(UserTableMap::COL_BAN);
            $criteria->addSelectColumn(UserTableMap::COL_SIGNUP_TS);
            $criteria->addSelectColumn(UserTableMap::COL_GENDER);
            $criteria->addSelectColumn(UserTableMap::COL_REPUTATION);
            $criteria->addSelectColumn(UserTableMap::COL_SETTING_PRIVACY);
            $criteria->addSelectColumn(UserTableMap::COL_SETTING_NOTIFICATIONS);
            $criteria->addSelectColumn(UserTableMap::COL_ACCESS_LEVEL);
            $criteria->addSelectColumn(UserTableMap::COL_PHONE);
            $criteria->addSelectColumn(UserTableMap::COL_PUBLIC_MESSAGE);
            $criteria->addSelectColumn(UserTableMap::COL_PICTURE_URL);
            $criteria->addSelectColumn(UserTableMap::COL_LANG);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.api_key');
            $criteria->addSelectColumn($alias . '.pass_sha256');
            $criteria->addSelectColumn($alias . '.pass_salt');
            $criteria->addSelectColumn($alias . '.ban');
            $criteria->addSelectColumn($alias . '.signup_ts');
            $criteria->addSelectColumn($alias . '.gender');
            $criteria->addSelectColumn($alias . '.reputation');
            $criteria->addSelectColumn($alias . '.setting_privacy');
            $criteria->addSelectColumn($alias . '.setting_notifications');
            $criteria->addSelectColumn($alias . '.access_level');
            $criteria->addSelectColumn($alias . '.phone');
            $criteria->addSelectColumn($alias . '.public_message');
            $criteria->addSelectColumn($alias . '.picture_url');
            $criteria->addSelectColumn($alias . '.lang');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME)->getTable(UserTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UserTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UserTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a User or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or User object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \User) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserTableMap::DATABASE_NAME);
            $criteria->add(UserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = UserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a User or Criteria object.
     *
     * @param mixed               $criteria Criteria or User object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from User object
        }

        if ($criteria->containsKey(UserTableMap::COL_ID) && $criteria->keyContainsValue(UserTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UserTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = UserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UserTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UserTableMap::buildTableMap();
