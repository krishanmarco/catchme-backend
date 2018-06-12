<?php

namespace Map;

use LocationAddress;
use LocationAddressQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use Propel\Runtime\Propel;


/**
 * This class defines the structure of the 'location_address' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class LocationAddressTableMap extends TableMap {
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.LocationAddressTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'catch_me';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'location_address';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\LocationAddress';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'LocationAddress';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the location_id field
     */
    const COL_LOCATION_ID = 'location_address.location_id';

    /**
     * the column name for the country field
     */
    const COL_COUNTRY = 'location_address.country';

    /**
     * the column name for the state field
     */
    const COL_STATE = 'location_address.state';

    /**
     * the column name for the city field
     */
    const COL_CITY = 'location_address.city';

    /**
     * the column name for the town field
     */
    const COL_TOWN = 'location_address.town';

    /**
     * the column name for the postcode field
     */
    const COL_POSTCODE = 'location_address.postcode';

    /**
     * the column name for the address field
     */
    const COL_ADDRESS = 'location_address.address';

    /**
     * the column name for the lat field
     */
    const COL_LAT = 'location_address.lat';

    /**
     * the column name for the lng field
     */
    const COL_LNG = 'location_address.lng';

    /**
     * the column name for the google_place_id field
     */
    const COL_GOOGLE_PLACE_ID = 'location_address.google_place_id';

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
    protected static $fieldNames = array(
        self::TYPE_PHPNAME => array('LocationId', 'Country', 'State', 'City', 'Town', 'Postcode', 'Address', 'Lat', 'Lng', 'GooglePlaceId',),
        self::TYPE_CAMELNAME => array('locationId', 'country', 'state', 'city', 'town', 'postcode', 'address', 'lat', 'lng', 'googlePlaceId',),
        self::TYPE_COLNAME => array(LocationAddressTableMap::COL_LOCATION_ID, LocationAddressTableMap::COL_COUNTRY, LocationAddressTableMap::COL_STATE, LocationAddressTableMap::COL_CITY, LocationAddressTableMap::COL_TOWN, LocationAddressTableMap::COL_POSTCODE, LocationAddressTableMap::COL_ADDRESS, LocationAddressTableMap::COL_LAT, LocationAddressTableMap::COL_LNG, LocationAddressTableMap::COL_GOOGLE_PLACE_ID,),
        self::TYPE_FIELDNAME => array('location_id', 'country', 'state', 'city', 'town', 'postcode', 'address', 'lat', 'lng', 'google_place_id',),
        self::TYPE_NUM => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,)
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME => array('LocationId' => 0, 'Country' => 1, 'State' => 2, 'City' => 3, 'Town' => 4, 'Postcode' => 5, 'Address' => 6, 'Lat' => 7, 'Lng' => 8, 'GooglePlaceId' => 9,),
        self::TYPE_CAMELNAME => array('locationId' => 0, 'country' => 1, 'state' => 2, 'city' => 3, 'town' => 4, 'postcode' => 5, 'address' => 6, 'lat' => 7, 'lng' => 8, 'googlePlaceId' => 9,),
        self::TYPE_COLNAME => array(LocationAddressTableMap::COL_LOCATION_ID => 0, LocationAddressTableMap::COL_COUNTRY => 1, LocationAddressTableMap::COL_STATE => 2, LocationAddressTableMap::COL_CITY => 3, LocationAddressTableMap::COL_TOWN => 4, LocationAddressTableMap::COL_POSTCODE => 5, LocationAddressTableMap::COL_ADDRESS => 6, LocationAddressTableMap::COL_LAT => 7, LocationAddressTableMap::COL_LNG => 8, LocationAddressTableMap::COL_GOOGLE_PLACE_ID => 9,),
        self::TYPE_FIELDNAME => array('location_id' => 0, 'country' => 1, 'state' => 2, 'city' => 3, 'town' => 4, 'postcode' => 5, 'address' => 6, 'lat' => 7, 'lng' => 8, 'google_place_id' => 9,),
        self::TYPE_NUM => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,)
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize() {
        // attributes
        $this->setName('location_address');
        $this->setPhpName('LocationAddress');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\LocationAddress');
        $this->setPackage('');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('location_id', 'LocationId', 'INTEGER', 'location', 'id', true, null, null);
        $this->addColumn('country', 'Country', 'VARCHAR', true, 2, null);
        $this->addColumn('state', 'State', 'VARCHAR', false, 255, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 255, null);
        $this->addColumn('town', 'Town', 'VARCHAR', false, 255, null);
        $this->addColumn('postcode', 'Postcode', 'VARCHAR', false, 255, null);
        $this->addColumn('address', 'Address', 'VARCHAR', true, 255, null);
        $this->addColumn('lat', 'Lat', 'DOUBLE', false, null, null);
        $this->addColumn('lng', 'Lng', 'DOUBLE', false, null, null);
        $this->addColumn('google_place_id', 'GooglePlaceId', 'VARCHAR', false, 255, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations() {
        $this->addRelation('Location', '\\Location', RelationMap::MANY_TO_ONE, array(
            0 =>
                array(
                    0 => ':location_id',
                    1 => ':id',
                ),
        ), null, null, null, false);
        $this->addRelation('SubscribedUser', '\\UserLocationFavorite', RelationMap::ONE_TO_MANY, array(
            0 =>
                array(
                    0 => ':location_id',
                    1 => ':location_id',
                ),
        ), null, null, 'SubscribedUsers', false);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM) {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('LocationId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('LocationId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('LocationId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('LocationId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string)$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('LocationId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('LocationId', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array $row resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM) {
        return (int)$row[$indexType == TableMap::TYPE_NUM
            ? 0 + $offset
            : self::translateFieldName('LocationId', TableMap::TYPE_PHPNAME, $indexType)];
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
    public static function getOMClass($withPrefix = true) {
        return $withPrefix ? LocationAddressTableMap::CLASS_DEFAULT : LocationAddressTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
     * One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (LocationAddress object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM) {
        $key = LocationAddressTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LocationAddressTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LocationAddressTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LocationAddressTableMap::OM_CLASS;
            /** @var LocationAddress $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LocationAddressTableMap::addInstanceToPool($obj, $key);
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
    public static function populateObjects(DataFetcherInterface $dataFetcher) {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = LocationAddressTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LocationAddressTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var LocationAddress $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LocationAddressTableMap::addInstanceToPool($obj, $key);
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
     * @param string $alias optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null) {
        if (null === $alias) {
            $criteria->addSelectColumn(LocationAddressTableMap::COL_LOCATION_ID);
            $criteria->addSelectColumn(LocationAddressTableMap::COL_COUNTRY);
            $criteria->addSelectColumn(LocationAddressTableMap::COL_STATE);
            $criteria->addSelectColumn(LocationAddressTableMap::COL_CITY);
            $criteria->addSelectColumn(LocationAddressTableMap::COL_TOWN);
            $criteria->addSelectColumn(LocationAddressTableMap::COL_POSTCODE);
            $criteria->addSelectColumn(LocationAddressTableMap::COL_ADDRESS);
            $criteria->addSelectColumn(LocationAddressTableMap::COL_LAT);
            $criteria->addSelectColumn(LocationAddressTableMap::COL_LNG);
            $criteria->addSelectColumn(LocationAddressTableMap::COL_GOOGLE_PLACE_ID);
        } else {
            $criteria->addSelectColumn($alias . '.location_id');
            $criteria->addSelectColumn($alias . '.country');
            $criteria->addSelectColumn($alias . '.state');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.town');
            $criteria->addSelectColumn($alias . '.postcode');
            $criteria->addSelectColumn($alias . '.address');
            $criteria->addSelectColumn($alias . '.lat');
            $criteria->addSelectColumn($alias . '.lng');
            $criteria->addSelectColumn($alias . '.google_place_id');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap() {
        return Propel::getServiceContainer()->getDatabaseMap(LocationAddressTableMap::DATABASE_NAME)->getTable(LocationAddressTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap() {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LocationAddressTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(LocationAddressTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new LocationAddressTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a LocationAddress or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or LocationAddress object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doDelete($values, ConnectionInterface $con = null) {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationAddressTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \LocationAddress) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LocationAddressTableMap::DATABASE_NAME);
            $criteria->add(LocationAddressTableMap::COL_LOCATION_ID, (array)$values, Criteria::IN);
        }

        $query = LocationAddressQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LocationAddressTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array)$values as $singleval) {
                LocationAddressTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the location_address table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null) {
        return LocationAddressQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a LocationAddress or Criteria object.
     *
     * @param mixed $criteria Criteria or LocationAddress object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null) {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationAddressTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from LocationAddress object
        }


        // Set the correct dbName
        $query = LocationAddressQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LocationAddressTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LocationAddressTableMap::buildTableMap();
