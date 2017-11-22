<?php

namespace Map;

use \Location;
use \LocationQuery;
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
 * This class defines the structure of the 'location' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class LocationTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.LocationTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'catch_me';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'location';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Location';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Location';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 12;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 12;

    /**
     * the column name for the id field
     */
    const COL_ID = 'location.id';

    /**
     * the column name for the admin_id field
     */
    const COL_ADMIN_ID = 'location.admin_id';

    /**
     * the column name for the signup_ts field
     */
    const COL_SIGNUP_TS = 'location.signup_ts';

    /**
     * the column name for the verified field
     */
    const COL_VERIFIED = 'location.verified';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'location.name';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'location.description';

    /**
     * the column name for the capacity field
     */
    const COL_CAPACITY = 'location.capacity';

    /**
     * the column name for the picture_url field
     */
    const COL_PICTURE_URL = 'location.picture_url';

    /**
     * the column name for the timings field
     */
    const COL_TIMINGS = 'location.timings';

    /**
     * the column name for the reputation field
     */
    const COL_REPUTATION = 'location.reputation';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'location.email';

    /**
     * the column name for the phone field
     */
    const COL_PHONE = 'location.phone';

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
        self::TYPE_PHPNAME       => array('Id', 'AdminId', 'SignupTs', 'Verified', 'Name', 'Description', 'Capacity', 'PictureUrl', 'Timings', 'Reputation', 'Email', 'Phone', ),
        self::TYPE_CAMELNAME     => array('id', 'adminId', 'signupTs', 'verified', 'name', 'description', 'capacity', 'pictureUrl', 'timings', 'reputation', 'email', 'phone', ),
        self::TYPE_COLNAME       => array(LocationTableMap::COL_ID, LocationTableMap::COL_ADMIN_ID, LocationTableMap::COL_SIGNUP_TS, LocationTableMap::COL_VERIFIED, LocationTableMap::COL_NAME, LocationTableMap::COL_DESCRIPTION, LocationTableMap::COL_CAPACITY, LocationTableMap::COL_PICTURE_URL, LocationTableMap::COL_TIMINGS, LocationTableMap::COL_REPUTATION, LocationTableMap::COL_EMAIL, LocationTableMap::COL_PHONE, ),
        self::TYPE_FIELDNAME     => array('id', 'admin_id', 'signup_ts', 'verified', 'name', 'description', 'capacity', 'picture_url', 'timings', 'reputation', 'email', 'phone', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'AdminId' => 1, 'SignupTs' => 2, 'Verified' => 3, 'Name' => 4, 'Description' => 5, 'Capacity' => 6, 'PictureUrl' => 7, 'Timings' => 8, 'Reputation' => 9, 'Email' => 10, 'Phone' => 11, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'adminId' => 1, 'signupTs' => 2, 'verified' => 3, 'name' => 4, 'description' => 5, 'capacity' => 6, 'pictureUrl' => 7, 'timings' => 8, 'reputation' => 9, 'email' => 10, 'phone' => 11, ),
        self::TYPE_COLNAME       => array(LocationTableMap::COL_ID => 0, LocationTableMap::COL_ADMIN_ID => 1, LocationTableMap::COL_SIGNUP_TS => 2, LocationTableMap::COL_VERIFIED => 3, LocationTableMap::COL_NAME => 4, LocationTableMap::COL_DESCRIPTION => 5, LocationTableMap::COL_CAPACITY => 6, LocationTableMap::COL_PICTURE_URL => 7, LocationTableMap::COL_TIMINGS => 8, LocationTableMap::COL_REPUTATION => 9, LocationTableMap::COL_EMAIL => 10, LocationTableMap::COL_PHONE => 11, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'admin_id' => 1, 'signup_ts' => 2, 'verified' => 3, 'name' => 4, 'description' => 5, 'capacity' => 6, 'picture_url' => 7, 'timings' => 8, 'reputation' => 9, 'email' => 10, 'phone' => 11, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
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
        $this->setName('location');
        $this->setPhpName('Location');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Location');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('admin_id', 'AdminId', 'INTEGER', 'user', 'id', true, null, null);
        $this->addColumn('signup_ts', 'SignupTs', 'INTEGER', true, 10, 1483228800);
        $this->addColumn('verified', 'Verified', 'BOOLEAN', true, 1, false);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('description', 'Description', 'VARCHAR', true, 255, null);
        $this->addColumn('capacity', 'Capacity', 'INTEGER', true, null, 0);
        $this->addColumn('picture_url', 'PictureUrl', 'VARCHAR', true, 255, null);
        $this->addColumn('timings', 'Timings', 'VARCHAR', true, 168, '');
        $this->addColumn('reputation', 'Reputation', 'INTEGER', true, null, 0);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 255, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Admin', '\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':admin_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Address', '\\LocationAddress', RelationMap::ONE_TO_ONE, array (
  0 =>
  array (
    0 => ':location_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('SearchString', '\\SearchLocation', RelationMap::ONE_TO_ONE, array (
  0 =>
  array (
    0 => ':location_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('SubscribedUser', '\\UserLocationFavorite', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':location_id',
    1 => ':id',
  ),
), null, null, 'SubscribedUsers', false);
        $this->addRelation('User', '\\UserLocation', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':location_id',
    1 => ':id',
  ),
), null, null, 'Users', false);
        $this->addRelation('ExpiredUser', '\\UserLocationExpired', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':location_id',
    1 => ':id',
  ),
), null, null, 'ExpiredUsers', false);
        $this->addRelation('Image', '\\LocationImage', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':location_id',
    1 => ':id',
  ),
), null, null, 'Images', false);
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to location     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        SearchLocationTableMap::clearInstancePool();
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
        return $withPrefix ? LocationTableMap::CLASS_DEFAULT : LocationTableMap::OM_CLASS;
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
     * @return array           (Location object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LocationTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LocationTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LocationTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LocationTableMap::OM_CLASS;
            /** @var Location $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LocationTableMap::addInstanceToPool($obj, $key);
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
            $key = LocationTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LocationTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Location $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LocationTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(LocationTableMap::COL_ID);
            $criteria->addSelectColumn(LocationTableMap::COL_ADMIN_ID);
            $criteria->addSelectColumn(LocationTableMap::COL_SIGNUP_TS);
            $criteria->addSelectColumn(LocationTableMap::COL_VERIFIED);
            $criteria->addSelectColumn(LocationTableMap::COL_NAME);
            $criteria->addSelectColumn(LocationTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(LocationTableMap::COL_CAPACITY);
            $criteria->addSelectColumn(LocationTableMap::COL_PICTURE_URL);
            $criteria->addSelectColumn(LocationTableMap::COL_TIMINGS);
            $criteria->addSelectColumn(LocationTableMap::COL_REPUTATION);
            $criteria->addSelectColumn(LocationTableMap::COL_EMAIL);
            $criteria->addSelectColumn(LocationTableMap::COL_PHONE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.admin_id');
            $criteria->addSelectColumn($alias . '.signup_ts');
            $criteria->addSelectColumn($alias . '.verified');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.capacity');
            $criteria->addSelectColumn($alias . '.picture_url');
            $criteria->addSelectColumn($alias . '.timings');
            $criteria->addSelectColumn($alias . '.reputation');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.phone');
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
        return Propel::getServiceContainer()->getDatabaseMap(LocationTableMap::DATABASE_NAME)->getTable(LocationTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LocationTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(LocationTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new LocationTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Location or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Location object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LocationTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Location) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LocationTableMap::DATABASE_NAME);
            $criteria->add(LocationTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = LocationQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LocationTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                LocationTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the location table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LocationQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Location or Criteria object.
     *
     * @param mixed               $criteria Criteria or Location object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Location object
        }

        if ($criteria->containsKey(LocationTableMap::COL_ID) && $criteria->keyContainsValue(LocationTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LocationTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = LocationQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LocationTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LocationTableMap::buildTableMap();
