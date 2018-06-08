<?php

namespace Base;

use \LocationAddress as ChildLocationAddress;
use \LocationAddressQuery as ChildLocationAddressQuery;
use \Exception;
use \PDO;
use Map\LocationAddressTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'location_address' table.
 *
 *
 *
 * @method     ChildLocationAddressQuery orderByLocationId($order = Criteria::ASC) Order by the location_id column
 * @method     ChildLocationAddressQuery orderByCountry($order = Criteria::ASC) Order by the country column
 * @method     ChildLocationAddressQuery orderByState($order = Criteria::ASC) Order by the state column
 * @method     ChildLocationAddressQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method     ChildLocationAddressQuery orderByTown($order = Criteria::ASC) Order by the town column
 * @method     ChildLocationAddressQuery orderByPostcode($order = Criteria::ASC) Order by the postcode column
 * @method     ChildLocationAddressQuery orderByAddress($order = Criteria::ASC) Order by the address column
 * @method     ChildLocationAddressQuery orderByLat($order = Criteria::ASC) Order by the lat column
 * @method     ChildLocationAddressQuery orderByLng($order = Criteria::ASC) Order by the lng column
 * @method     ChildLocationAddressQuery orderByGooglePlaceId($order = Criteria::ASC) Order by the google_place_id column
 *
 * @method     ChildLocationAddressQuery groupByLocationId() Group by the location_id column
 * @method     ChildLocationAddressQuery groupByCountry() Group by the country column
 * @method     ChildLocationAddressQuery groupByState() Group by the state column
 * @method     ChildLocationAddressQuery groupByCity() Group by the city column
 * @method     ChildLocationAddressQuery groupByTown() Group by the town column
 * @method     ChildLocationAddressQuery groupByPostcode() Group by the postcode column
 * @method     ChildLocationAddressQuery groupByAddress() Group by the address column
 * @method     ChildLocationAddressQuery groupByLat() Group by the lat column
 * @method     ChildLocationAddressQuery groupByLng() Group by the lng column
 * @method     ChildLocationAddressQuery groupByGooglePlaceId() Group by the google_place_id column
 *
 * @method     ChildLocationAddressQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLocationAddressQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLocationAddressQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLocationAddressQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLocationAddressQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLocationAddressQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLocationAddressQuery leftJoinLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the Location relation
 * @method     ChildLocationAddressQuery rightJoinLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Location relation
 * @method     ChildLocationAddressQuery innerJoinLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the Location relation
 *
 * @method     ChildLocationAddressQuery joinWithLocation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Location relation
 *
 * @method     ChildLocationAddressQuery leftJoinWithLocation() Adds a LEFT JOIN clause and with to the query using the Location relation
 * @method     ChildLocationAddressQuery rightJoinWithLocation() Adds a RIGHT JOIN clause and with to the query using the Location relation
 * @method     ChildLocationAddressQuery innerJoinWithLocation() Adds a INNER JOIN clause and with to the query using the Location relation
 *
 * @method     ChildLocationAddressQuery leftJoinSubscribedUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the SubscribedUser relation
 * @method     ChildLocationAddressQuery rightJoinSubscribedUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SubscribedUser relation
 * @method     ChildLocationAddressQuery innerJoinSubscribedUser($relationAlias = null) Adds a INNER JOIN clause to the query using the SubscribedUser relation
 *
 * @method     ChildLocationAddressQuery joinWithSubscribedUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SubscribedUser relation
 *
 * @method     ChildLocationAddressQuery leftJoinWithSubscribedUser() Adds a LEFT JOIN clause and with to the query using the SubscribedUser relation
 * @method     ChildLocationAddressQuery rightJoinWithSubscribedUser() Adds a RIGHT JOIN clause and with to the query using the SubscribedUser relation
 * @method     ChildLocationAddressQuery innerJoinWithSubscribedUser() Adds a INNER JOIN clause and with to the query using the SubscribedUser relation
 *
 * @method     \LocationQuery|\UserLocationFavoriteQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLocationAddress findOne(ConnectionInterface $con = null) Return the first ChildLocationAddress matching the query
 * @method     ChildLocationAddress findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLocationAddress matching the query, or a new ChildLocationAddress object populated from the query conditions when no match is found
 *
 * @method     ChildLocationAddress findOneByLocationId(int $location_id) Return the first ChildLocationAddress filtered by the location_id column
 * @method     ChildLocationAddress findOneByCountry(string $country) Return the first ChildLocationAddress filtered by the country column
 * @method     ChildLocationAddress findOneByState(string $state) Return the first ChildLocationAddress filtered by the state column
 * @method     ChildLocationAddress findOneByCity(string $city) Return the first ChildLocationAddress filtered by the city column
 * @method     ChildLocationAddress findOneByTown(string $town) Return the first ChildLocationAddress filtered by the town column
 * @method     ChildLocationAddress findOneByPostcode(string $postcode) Return the first ChildLocationAddress filtered by the postcode column
 * @method     ChildLocationAddress findOneByAddress(string $address) Return the first ChildLocationAddress filtered by the address column
 * @method     ChildLocationAddress findOneByLat(double $lat) Return the first ChildLocationAddress filtered by the lat column
 * @method     ChildLocationAddress findOneByLng(double $lng) Return the first ChildLocationAddress filtered by the lng column
 * @method     ChildLocationAddress findOneByGooglePlaceId(string $google_place_id) Return the first ChildLocationAddress filtered by the google_place_id column *

 * @method     ChildLocationAddress requirePk($key, ConnectionInterface $con = null) Return the ChildLocationAddress by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationAddress requireOne(ConnectionInterface $con = null) Return the first ChildLocationAddress matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocationAddress requireOneByLocationId(int $location_id) Return the first ChildLocationAddress filtered by the location_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationAddress requireOneByCountry(string $country) Return the first ChildLocationAddress filtered by the country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationAddress requireOneByState(string $state) Return the first ChildLocationAddress filtered by the state column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationAddress requireOneByCity(string $city) Return the first ChildLocationAddress filtered by the city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationAddress requireOneByTown(string $town) Return the first ChildLocationAddress filtered by the town column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationAddress requireOneByPostcode(string $postcode) Return the first ChildLocationAddress filtered by the postcode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationAddress requireOneByAddress(string $address) Return the first ChildLocationAddress filtered by the address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationAddress requireOneByLat(double $lat) Return the first ChildLocationAddress filtered by the lat column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationAddress requireOneByLng(double $lng) Return the first ChildLocationAddress filtered by the lng column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationAddress requireOneByGooglePlaceId(string $google_place_id) Return the first ChildLocationAddress filtered by the google_place_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocationAddress[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLocationAddress objects based on current ModelCriteria
 * @method     ChildLocationAddress[]|ObjectCollection findByLocationId(int $location_id) Return ChildLocationAddress objects filtered by the location_id column
 * @method     ChildLocationAddress[]|ObjectCollection findByCountry(string $country) Return ChildLocationAddress objects filtered by the country column
 * @method     ChildLocationAddress[]|ObjectCollection findByState(string $state) Return ChildLocationAddress objects filtered by the state column
 * @method     ChildLocationAddress[]|ObjectCollection findByCity(string $city) Return ChildLocationAddress objects filtered by the city column
 * @method     ChildLocationAddress[]|ObjectCollection findByTown(string $town) Return ChildLocationAddress objects filtered by the town column
 * @method     ChildLocationAddress[]|ObjectCollection findByPostcode(string $postcode) Return ChildLocationAddress objects filtered by the postcode column
 * @method     ChildLocationAddress[]|ObjectCollection findByAddress(string $address) Return ChildLocationAddress objects filtered by the address column
 * @method     ChildLocationAddress[]|ObjectCollection findByLat(double $lat) Return ChildLocationAddress objects filtered by the lat column
 * @method     ChildLocationAddress[]|ObjectCollection findByLng(double $lng) Return ChildLocationAddress objects filtered by the lng column
 * @method     ChildLocationAddress[]|ObjectCollection findByGooglePlaceId(string $google_place_id) Return ChildLocationAddress objects filtered by the google_place_id column
 * @method     ChildLocationAddress[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LocationAddressQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\LocationAddressQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'catch_me', $modelName = '\\LocationAddress', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLocationAddressQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLocationAddressQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLocationAddressQuery) {
            return $criteria;
        }
        $query = new ChildLocationAddressQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildLocationAddress|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LocationAddressTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LocationAddressTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocationAddress A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT location_id, country, state, city, town, postcode, address, lat, lng, google_place_id FROM location_address WHERE location_id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildLocationAddress $obj */
            $obj = new ChildLocationAddress();
            $obj->hydrate($row);
            LocationAddressTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildLocationAddress|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LocationAddressTableMap::COL_LOCATION_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LocationAddressTableMap::COL_LOCATION_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the location_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationId(1234); // WHERE location_id = 1234
     * $query->filterByLocationId(array(12, 34)); // WHERE location_id IN (12, 34)
     * $query->filterByLocationId(array('min' => 12)); // WHERE location_id > 12
     * </code>
     *
     * @see       filterByLocation()
     *
     * @param     mixed $locationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByLocationId($locationId = null, $comparison = null)
    {
        if (is_array($locationId)) {
            $useMinMax = false;
            if (isset($locationId['min'])) {
                $this->addUsingAlias(LocationAddressTableMap::COL_LOCATION_ID, $locationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($locationId['max'])) {
                $this->addUsingAlias(LocationAddressTableMap::COL_LOCATION_ID, $locationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationAddressTableMap::COL_LOCATION_ID, $locationId, $comparison);
    }

    /**
     * Filter the query on the country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE country = 'fooValue'
     * $query->filterByCountry('%fooValue%', Criteria::LIKE); // WHERE country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $country The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationAddressTableMap::COL_COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the state column
     *
     * Example usage:
     * <code>
     * $query->filterByState('fooValue');   // WHERE state = 'fooValue'
     * $query->filterByState('%fooValue%', Criteria::LIKE); // WHERE state LIKE '%fooValue%'
     * </code>
     *
     * @param     string $state The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByState($state = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($state)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationAddressTableMap::COL_STATE, $state, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationAddressTableMap::COL_CITY, $city, $comparison);
    }

    /**
     * Filter the query on the town column
     *
     * Example usage:
     * <code>
     * $query->filterByTown('fooValue');   // WHERE town = 'fooValue'
     * $query->filterByTown('%fooValue%', Criteria::LIKE); // WHERE town LIKE '%fooValue%'
     * </code>
     *
     * @param     string $town The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByTown($town = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($town)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationAddressTableMap::COL_TOWN, $town, $comparison);
    }

    /**
     * Filter the query on the postcode column
     *
     * Example usage:
     * <code>
     * $query->filterByPostcode('fooValue');   // WHERE postcode = 'fooValue'
     * $query->filterByPostcode('%fooValue%', Criteria::LIKE); // WHERE postcode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $postcode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByPostcode($postcode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postcode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationAddressTableMap::COL_POSTCODE, $postcode, $comparison);
    }

    /**
     * Filter the query on the address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE address = 'fooValue'
     * $query->filterByAddress('%fooValue%', Criteria::LIKE); // WHERE address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationAddressTableMap::COL_ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the lat column
     *
     * Example usage:
     * <code>
     * $query->filterByLat(1234); // WHERE lat = 1234
     * $query->filterByLat(array(12, 34)); // WHERE lat IN (12, 34)
     * $query->filterByLat(array('min' => 12)); // WHERE lat > 12
     * </code>
     *
     * @param     mixed $lat The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByLat($lat = null, $comparison = null)
    {
        if (is_array($lat)) {
            $useMinMax = false;
            if (isset($lat['min'])) {
                $this->addUsingAlias(LocationAddressTableMap::COL_LAT, $lat['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lat['max'])) {
                $this->addUsingAlias(LocationAddressTableMap::COL_LAT, $lat['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationAddressTableMap::COL_LAT, $lat, $comparison);
    }

    /**
     * Filter the query on the lng column
     *
     * Example usage:
     * <code>
     * $query->filterByLng(1234); // WHERE lng = 1234
     * $query->filterByLng(array(12, 34)); // WHERE lng IN (12, 34)
     * $query->filterByLng(array('min' => 12)); // WHERE lng > 12
     * </code>
     *
     * @param     mixed $lng The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByLng($lng = null, $comparison = null)
    {
        if (is_array($lng)) {
            $useMinMax = false;
            if (isset($lng['min'])) {
                $this->addUsingAlias(LocationAddressTableMap::COL_LNG, $lng['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lng['max'])) {
                $this->addUsingAlias(LocationAddressTableMap::COL_LNG, $lng['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationAddressTableMap::COL_LNG, $lng, $comparison);
    }

    /**
     * Filter the query on the google_place_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGooglePlaceId('fooValue');   // WHERE google_place_id = 'fooValue'
     * $query->filterByGooglePlaceId('%fooValue%', Criteria::LIKE); // WHERE google_place_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $googlePlaceId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByGooglePlaceId($googlePlaceId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($googlePlaceId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationAddressTableMap::COL_GOOGLE_PLACE_ID, $googlePlaceId, $comparison);
    }

    /**
     * Filter the query by a related \Location object
     *
     * @param \Location|ObjectCollection $location The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterByLocation($location, $comparison = null)
    {
        if ($location instanceof \Location) {
            return $this
                ->addUsingAlias(LocationAddressTableMap::COL_LOCATION_ID, $location->getId(), $comparison);
        } elseif ($location instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LocationAddressTableMap::COL_LOCATION_ID, $location->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLocation() only accepts arguments of type \Location or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Location relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function joinLocation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Location');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Location');
        }

        return $this;
    }

    /**
     * Use the Location relation Location object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LocationQuery A secondary query class using the current class as primary query
     */
    public function useLocationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Location', '\LocationQuery');
    }

    /**
     * Filter the query by a related \UserLocationFavorite object
     *
     * @param \UserLocationFavorite|ObjectCollection $userLocationFavorite the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocationAddressQuery The current query, for fluid interface
     */
    public function filterBySubscribedUser($userLocationFavorite, $comparison = null)
    {
        if ($userLocationFavorite instanceof \UserLocationFavorite) {
            return $this
                ->addUsingAlias(LocationAddressTableMap::COL_LOCATION_ID, $userLocationFavorite->getLocationId(), $comparison);
        } elseif ($userLocationFavorite instanceof ObjectCollection) {
            return $this
                ->useSubscribedUserQuery()
                ->filterByPrimaryKeys($userLocationFavorite->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySubscribedUser() only accepts arguments of type \UserLocationFavorite or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SubscribedUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function joinSubscribedUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SubscribedUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'SubscribedUser');
        }

        return $this;
    }

    /**
     * Use the SubscribedUser relation UserLocationFavorite object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserLocationFavoriteQuery A secondary query class using the current class as primary query
     */
    public function useSubscribedUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSubscribedUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SubscribedUser', '\UserLocationFavoriteQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLocationAddress $locationAddress Object to remove from the list of results
     *
     * @return $this|ChildLocationAddressQuery The current query, for fluid interface
     */
    public function prune($locationAddress = null)
    {
        if ($locationAddress) {
            $this->addUsingAlias(LocationAddressTableMap::COL_LOCATION_ID, $locationAddress->getLocationId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the location_address table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationAddressTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LocationAddressTableMap::clearInstancePool();
            LocationAddressTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationAddressTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LocationAddressTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LocationAddressTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LocationAddressTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LocationAddressQuery
