<?php

namespace Base;

use \Location as ChildLocation;
use \LocationQuery as ChildLocationQuery;
use \Exception;
use \PDO;
use Map\LocationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'location' table.
 *
 *
 *
 * @method     ChildLocationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLocationQuery orderByAdminId($order = Criteria::ASC) Order by the admin_id column
 * @method     ChildLocationQuery orderBySignupTs($order = Criteria::ASC) Order by the signup_ts column
 * @method     ChildLocationQuery orderByVerified($order = Criteria::ASC) Order by the verified column
 * @method     ChildLocationQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildLocationQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildLocationQuery orderByCapacity($order = Criteria::ASC) Order by the capacity column
 * @method     ChildLocationQuery orderByPictureUrl($order = Criteria::ASC) Order by the picture_url column
 * @method     ChildLocationQuery orderByTimings($order = Criteria::ASC) Order by the timings column
 * @method     ChildLocationQuery orderByReputation($order = Criteria::ASC) Order by the reputation column
 * @method     ChildLocationQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildLocationQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 *
 * @method     ChildLocationQuery groupById() Group by the id column
 * @method     ChildLocationQuery groupByAdminId() Group by the admin_id column
 * @method     ChildLocationQuery groupBySignupTs() Group by the signup_ts column
 * @method     ChildLocationQuery groupByVerified() Group by the verified column
 * @method     ChildLocationQuery groupByName() Group by the name column
 * @method     ChildLocationQuery groupByDescription() Group by the description column
 * @method     ChildLocationQuery groupByCapacity() Group by the capacity column
 * @method     ChildLocationQuery groupByPictureUrl() Group by the picture_url column
 * @method     ChildLocationQuery groupByTimings() Group by the timings column
 * @method     ChildLocationQuery groupByReputation() Group by the reputation column
 * @method     ChildLocationQuery groupByEmail() Group by the email column
 * @method     ChildLocationQuery groupByPhone() Group by the phone column
 *
 * @method     ChildLocationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLocationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLocationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLocationQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLocationQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLocationQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLocationQuery leftJoinAdmin($relationAlias = null) Adds a LEFT JOIN clause to the query using the Admin relation
 * @method     ChildLocationQuery rightJoinAdmin($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Admin relation
 * @method     ChildLocationQuery innerJoinAdmin($relationAlias = null) Adds a INNER JOIN clause to the query using the Admin relation
 *
 * @method     ChildLocationQuery joinWithAdmin($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Admin relation
 *
 * @method     ChildLocationQuery leftJoinWithAdmin() Adds a LEFT JOIN clause and with to the query using the Admin relation
 * @method     ChildLocationQuery rightJoinWithAdmin() Adds a RIGHT JOIN clause and with to the query using the Admin relation
 * @method     ChildLocationQuery innerJoinWithAdmin() Adds a INNER JOIN clause and with to the query using the Admin relation
 *
 * @method     ChildLocationQuery leftJoinAddress($relationAlias = null) Adds a LEFT JOIN clause to the query using the Address relation
 * @method     ChildLocationQuery rightJoinAddress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Address relation
 * @method     ChildLocationQuery innerJoinAddress($relationAlias = null) Adds a INNER JOIN clause to the query using the Address relation
 *
 * @method     ChildLocationQuery joinWithAddress($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Address relation
 *
 * @method     ChildLocationQuery leftJoinWithAddress() Adds a LEFT JOIN clause and with to the query using the Address relation
 * @method     ChildLocationQuery rightJoinWithAddress() Adds a RIGHT JOIN clause and with to the query using the Address relation
 * @method     ChildLocationQuery innerJoinWithAddress() Adds a INNER JOIN clause and with to the query using the Address relation
 *
 * @method     ChildLocationQuery leftJoinSearchString($relationAlias = null) Adds a LEFT JOIN clause to the query using the SearchString relation
 * @method     ChildLocationQuery rightJoinSearchString($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SearchString relation
 * @method     ChildLocationQuery innerJoinSearchString($relationAlias = null) Adds a INNER JOIN clause to the query using the SearchString relation
 *
 * @method     ChildLocationQuery joinWithSearchString($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SearchString relation
 *
 * @method     ChildLocationQuery leftJoinWithSearchString() Adds a LEFT JOIN clause and with to the query using the SearchString relation
 * @method     ChildLocationQuery rightJoinWithSearchString() Adds a RIGHT JOIN clause and with to the query using the SearchString relation
 * @method     ChildLocationQuery innerJoinWithSearchString() Adds a INNER JOIN clause and with to the query using the SearchString relation
 *
 * @method     ChildLocationQuery leftJoinSubscribedUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the SubscribedUser relation
 * @method     ChildLocationQuery rightJoinSubscribedUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SubscribedUser relation
 * @method     ChildLocationQuery innerJoinSubscribedUser($relationAlias = null) Adds a INNER JOIN clause to the query using the SubscribedUser relation
 *
 * @method     ChildLocationQuery joinWithSubscribedUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SubscribedUser relation
 *
 * @method     ChildLocationQuery leftJoinWithSubscribedUser() Adds a LEFT JOIN clause and with to the query using the SubscribedUser relation
 * @method     ChildLocationQuery rightJoinWithSubscribedUser() Adds a RIGHT JOIN clause and with to the query using the SubscribedUser relation
 * @method     ChildLocationQuery innerJoinWithSubscribedUser() Adds a INNER JOIN clause and with to the query using the SubscribedUser relation
 *
 * @method     ChildLocationQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildLocationQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildLocationQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildLocationQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildLocationQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildLocationQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildLocationQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildLocationQuery leftJoinExpiredUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the ExpiredUser relation
 * @method     ChildLocationQuery rightJoinExpiredUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ExpiredUser relation
 * @method     ChildLocationQuery innerJoinExpiredUser($relationAlias = null) Adds a INNER JOIN clause to the query using the ExpiredUser relation
 *
 * @method     ChildLocationQuery joinWithExpiredUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ExpiredUser relation
 *
 * @method     ChildLocationQuery leftJoinWithExpiredUser() Adds a LEFT JOIN clause and with to the query using the ExpiredUser relation
 * @method     ChildLocationQuery rightJoinWithExpiredUser() Adds a RIGHT JOIN clause and with to the query using the ExpiredUser relation
 * @method     ChildLocationQuery innerJoinWithExpiredUser() Adds a INNER JOIN clause and with to the query using the ExpiredUser relation
 *
 * @method     ChildLocationQuery leftJoinImage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Image relation
 * @method     ChildLocationQuery rightJoinImage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Image relation
 * @method     ChildLocationQuery innerJoinImage($relationAlias = null) Adds a INNER JOIN clause to the query using the Image relation
 *
 * @method     ChildLocationQuery joinWithImage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Image relation
 *
 * @method     ChildLocationQuery leftJoinWithImage() Adds a LEFT JOIN clause and with to the query using the Image relation
 * @method     ChildLocationQuery rightJoinWithImage() Adds a RIGHT JOIN clause and with to the query using the Image relation
 * @method     ChildLocationQuery innerJoinWithImage() Adds a INNER JOIN clause and with to the query using the Image relation
 *
 * @method     \UserQuery|\LocationAddressQuery|\SearchLocationQuery|\UserLocationFavoriteQuery|\UserLocationQuery|\UserLocationExpiredQuery|\LocationImageQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLocation findOne(ConnectionInterface $con = null) Return the first ChildLocation matching the query
 * @method     ChildLocation findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLocation matching the query, or a new ChildLocation object populated from the query conditions when no match is found
 *
 * @method     ChildLocation findOneById(int $id) Return the first ChildLocation filtered by the id column
 * @method     ChildLocation findOneByAdminId(int $admin_id) Return the first ChildLocation filtered by the admin_id column
 * @method     ChildLocation findOneBySignupTs(int $signup_ts) Return the first ChildLocation filtered by the signup_ts column
 * @method     ChildLocation findOneByVerified(boolean $verified) Return the first ChildLocation filtered by the verified column
 * @method     ChildLocation findOneByName(string $name) Return the first ChildLocation filtered by the name column
 * @method     ChildLocation findOneByDescription(string $description) Return the first ChildLocation filtered by the description column
 * @method     ChildLocation findOneByCapacity(int $capacity) Return the first ChildLocation filtered by the capacity column
 * @method     ChildLocation findOneByPictureUrl(string $picture_url) Return the first ChildLocation filtered by the picture_url column
 * @method     ChildLocation findOneByTimings(string $timings) Return the first ChildLocation filtered by the timings column
 * @method     ChildLocation findOneByReputation(int $reputation) Return the first ChildLocation filtered by the reputation column
 * @method     ChildLocation findOneByEmail(string $email) Return the first ChildLocation filtered by the email column
 * @method     ChildLocation findOneByPhone(string $phone) Return the first ChildLocation filtered by the phone column *

 * @method     ChildLocation requirePk($key, ConnectionInterface $con = null) Return the ChildLocation by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOne(ConnectionInterface $con = null) Return the first ChildLocation matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocation requireOneById(int $id) Return the first ChildLocation filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByAdminId(int $admin_id) Return the first ChildLocation filtered by the admin_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneBySignupTs(int $signup_ts) Return the first ChildLocation filtered by the signup_ts column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByVerified(boolean $verified) Return the first ChildLocation filtered by the verified column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByName(string $name) Return the first ChildLocation filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByDescription(string $description) Return the first ChildLocation filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByCapacity(int $capacity) Return the first ChildLocation filtered by the capacity column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByPictureUrl(string $picture_url) Return the first ChildLocation filtered by the picture_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByTimings(string $timings) Return the first ChildLocation filtered by the timings column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByReputation(int $reputation) Return the first ChildLocation filtered by the reputation column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByEmail(string $email) Return the first ChildLocation filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByPhone(string $phone) Return the first ChildLocation filtered by the phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocation[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLocation objects based on current ModelCriteria
 * @method     ChildLocation[]|ObjectCollection findById(int $id) Return ChildLocation objects filtered by the id column
 * @method     ChildLocation[]|ObjectCollection findByAdminId(int $admin_id) Return ChildLocation objects filtered by the admin_id column
 * @method     ChildLocation[]|ObjectCollection findBySignupTs(int $signup_ts) Return ChildLocation objects filtered by the signup_ts column
 * @method     ChildLocation[]|ObjectCollection findByVerified(boolean $verified) Return ChildLocation objects filtered by the verified column
 * @method     ChildLocation[]|ObjectCollection findByName(string $name) Return ChildLocation objects filtered by the name column
 * @method     ChildLocation[]|ObjectCollection findByDescription(string $description) Return ChildLocation objects filtered by the description column
 * @method     ChildLocation[]|ObjectCollection findByCapacity(int $capacity) Return ChildLocation objects filtered by the capacity column
 * @method     ChildLocation[]|ObjectCollection findByPictureUrl(string $picture_url) Return ChildLocation objects filtered by the picture_url column
 * @method     ChildLocation[]|ObjectCollection findByTimings(string $timings) Return ChildLocation objects filtered by the timings column
 * @method     ChildLocation[]|ObjectCollection findByReputation(int $reputation) Return ChildLocation objects filtered by the reputation column
 * @method     ChildLocation[]|ObjectCollection findByEmail(string $email) Return ChildLocation objects filtered by the email column
 * @method     ChildLocation[]|ObjectCollection findByPhone(string $phone) Return ChildLocation objects filtered by the phone column
 * @method     ChildLocation[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LocationQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\LocationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'catch_me', $modelName = '\\Location', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLocationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLocationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLocationQuery) {
            return $criteria;
        }
        $query = new ChildLocationQuery();
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
     * @return ChildLocation|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LocationTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LocationTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLocation A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, admin_id, signup_ts, verified, name, description, capacity, picture_url, timings, reputation, email, phone FROM location WHERE id = :p0';
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
            /** @var ChildLocation $obj */
            $obj = new ChildLocation();
            $obj->hydrate($row);
            LocationTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLocation|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LocationTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LocationTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LocationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LocationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the admin_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAdminId(1234); // WHERE admin_id = 1234
     * $query->filterByAdminId(array(12, 34)); // WHERE admin_id IN (12, 34)
     * $query->filterByAdminId(array('min' => 12)); // WHERE admin_id > 12
     * </code>
     *
     * @see       filterByAdmin()
     *
     * @param     mixed $adminId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByAdminId($adminId = null, $comparison = null)
    {
        if (is_array($adminId)) {
            $useMinMax = false;
            if (isset($adminId['min'])) {
                $this->addUsingAlias(LocationTableMap::COL_ADMIN_ID, $adminId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($adminId['max'])) {
                $this->addUsingAlias(LocationTableMap::COL_ADMIN_ID, $adminId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_ADMIN_ID, $adminId, $comparison);
    }

    /**
     * Filter the query on the signup_ts column
     *
     * Example usage:
     * <code>
     * $query->filterBySignupTs(1234); // WHERE signup_ts = 1234
     * $query->filterBySignupTs(array(12, 34)); // WHERE signup_ts IN (12, 34)
     * $query->filterBySignupTs(array('min' => 12)); // WHERE signup_ts > 12
     * </code>
     *
     * @param     mixed $signupTs The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterBySignupTs($signupTs = null, $comparison = null)
    {
        if (is_array($signupTs)) {
            $useMinMax = false;
            if (isset($signupTs['min'])) {
                $this->addUsingAlias(LocationTableMap::COL_SIGNUP_TS, $signupTs['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($signupTs['max'])) {
                $this->addUsingAlias(LocationTableMap::COL_SIGNUP_TS, $signupTs['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_SIGNUP_TS, $signupTs, $comparison);
    }

    /**
     * Filter the query on the verified column
     *
     * Example usage:
     * <code>
     * $query->filterByVerified(true); // WHERE verified = true
     * $query->filterByVerified('yes'); // WHERE verified = true
     * </code>
     *
     * @param     boolean|string $verified The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByVerified($verified = null, $comparison = null)
    {
        if (is_string($verified)) {
            $verified = in_array(strtolower($verified), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LocationTableMap::COL_VERIFIED, $verified, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the capacity column
     *
     * Example usage:
     * <code>
     * $query->filterByCapacity(1234); // WHERE capacity = 1234
     * $query->filterByCapacity(array(12, 34)); // WHERE capacity IN (12, 34)
     * $query->filterByCapacity(array('min' => 12)); // WHERE capacity > 12
     * </code>
     *
     * @param     mixed $capacity The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByCapacity($capacity = null, $comparison = null)
    {
        if (is_array($capacity)) {
            $useMinMax = false;
            if (isset($capacity['min'])) {
                $this->addUsingAlias(LocationTableMap::COL_CAPACITY, $capacity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($capacity['max'])) {
                $this->addUsingAlias(LocationTableMap::COL_CAPACITY, $capacity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_CAPACITY, $capacity, $comparison);
    }

    /**
     * Filter the query on the picture_url column
     *
     * Example usage:
     * <code>
     * $query->filterByPictureUrl('fooValue');   // WHERE picture_url = 'fooValue'
     * $query->filterByPictureUrl('%fooValue%', Criteria::LIKE); // WHERE picture_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pictureUrl The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByPictureUrl($pictureUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pictureUrl)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_PICTURE_URL, $pictureUrl, $comparison);
    }

    /**
     * Filter the query on the timings column
     *
     * Example usage:
     * <code>
     * $query->filterByTimings('fooValue');   // WHERE timings = 'fooValue'
     * $query->filterByTimings('%fooValue%', Criteria::LIKE); // WHERE timings LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timings The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByTimings($timings = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timings)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_TIMINGS, $timings, $comparison);
    }

    /**
     * Filter the query on the reputation column
     *
     * Example usage:
     * <code>
     * $query->filterByReputation(1234); // WHERE reputation = 1234
     * $query->filterByReputation(array(12, 34)); // WHERE reputation IN (12, 34)
     * $query->filterByReputation(array('min' => 12)); // WHERE reputation > 12
     * </code>
     *
     * @param     mixed $reputation The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByReputation($reputation = null, $comparison = null)
    {
        if (is_array($reputation)) {
            $useMinMax = false;
            if (isset($reputation['min'])) {
                $this->addUsingAlias(LocationTableMap::COL_REPUTATION, $reputation['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($reputation['max'])) {
                $this->addUsingAlias(LocationTableMap::COL_REPUTATION, $reputation['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_REPUTATION, $reputation, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_PHONE, $phone, $comparison);
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocationQuery The current query, for fluid interface
     */
    public function filterByAdmin($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(LocationTableMap::COL_ADMIN_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LocationTableMap::COL_ADMIN_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAdmin() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Admin relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function joinAdmin($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Admin');

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
            $this->addJoinObject($join, 'Admin');
        }

        return $this;
    }

    /**
     * Use the Admin relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useAdminQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAdmin($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Admin', '\UserQuery');
    }

    /**
     * Filter the query by a related \LocationAddress object
     *
     * @param \LocationAddress|ObjectCollection $locationAddress the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocationQuery The current query, for fluid interface
     */
    public function filterByAddress($locationAddress, $comparison = null)
    {
        if ($locationAddress instanceof \LocationAddress) {
            return $this
                ->addUsingAlias(LocationTableMap::COL_ID, $locationAddress->getLocationId(), $comparison);
        } elseif ($locationAddress instanceof ObjectCollection) {
            return $this
                ->useAddressQuery()
                ->filterByPrimaryKeys($locationAddress->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAddress() only accepts arguments of type \LocationAddress or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Address relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function joinAddress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Address');

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
            $this->addJoinObject($join, 'Address');
        }

        return $this;
    }

    /**
     * Use the Address relation LocationAddress object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LocationAddressQuery A secondary query class using the current class as primary query
     */
    public function useAddressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAddress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Address', '\LocationAddressQuery');
    }

    /**
     * Filter the query by a related \SearchLocation object
     *
     * @param \SearchLocation|ObjectCollection $searchLocation the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocationQuery The current query, for fluid interface
     */
    public function filterBySearchString($searchLocation, $comparison = null)
    {
        if ($searchLocation instanceof \SearchLocation) {
            return $this
                ->addUsingAlias(LocationTableMap::COL_ID, $searchLocation->getLocationId(), $comparison);
        } elseif ($searchLocation instanceof ObjectCollection) {
            return $this
                ->useSearchStringQuery()
                ->filterByPrimaryKeys($searchLocation->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySearchString() only accepts arguments of type \SearchLocation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SearchString relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function joinSearchString($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SearchString');

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
            $this->addJoinObject($join, 'SearchString');
        }

        return $this;
    }

    /**
     * Use the SearchString relation SearchLocation object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SearchLocationQuery A secondary query class using the current class as primary query
     */
    public function useSearchStringQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSearchString($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SearchString', '\SearchLocationQuery');
    }

    /**
     * Filter the query by a related \UserLocationFavorite object
     *
     * @param \UserLocationFavorite|ObjectCollection $userLocationFavorite the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocationQuery The current query, for fluid interface
     */
    public function filterBySubscribedUser($userLocationFavorite, $comparison = null)
    {
        if ($userLocationFavorite instanceof \UserLocationFavorite) {
            return $this
                ->addUsingAlias(LocationTableMap::COL_ID, $userLocationFavorite->getLocationId(), $comparison);
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
     * @return $this|ChildLocationQuery The current query, for fluid interface
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
     * Filter the query by a related \UserLocation object
     *
     * @param \UserLocation|ObjectCollection $userLocation the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocationQuery The current query, for fluid interface
     */
    public function filterByUser($userLocation, $comparison = null)
    {
        if ($userLocation instanceof \UserLocation) {
            return $this
                ->addUsingAlias(LocationTableMap::COL_ID, $userLocation->getLocationId(), $comparison);
        } elseif ($userLocation instanceof ObjectCollection) {
            return $this
                ->useUserQuery()
                ->filterByPrimaryKeys($userLocation->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \UserLocation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation UserLocation object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserLocationQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\UserLocationQuery');
    }

    /**
     * Filter the query by a related \UserLocationExpired object
     *
     * @param \UserLocationExpired|ObjectCollection $userLocationExpired the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocationQuery The current query, for fluid interface
     */
    public function filterByExpiredUser($userLocationExpired, $comparison = null)
    {
        if ($userLocationExpired instanceof \UserLocationExpired) {
            return $this
                ->addUsingAlias(LocationTableMap::COL_ID, $userLocationExpired->getLocationId(), $comparison);
        } elseif ($userLocationExpired instanceof ObjectCollection) {
            return $this
                ->useExpiredUserQuery()
                ->filterByPrimaryKeys($userLocationExpired->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByExpiredUser() only accepts arguments of type \UserLocationExpired or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ExpiredUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function joinExpiredUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ExpiredUser');

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
            $this->addJoinObject($join, 'ExpiredUser');
        }

        return $this;
    }

    /**
     * Use the ExpiredUser relation UserLocationExpired object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserLocationExpiredQuery A secondary query class using the current class as primary query
     */
    public function useExpiredUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinExpiredUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ExpiredUser', '\UserLocationExpiredQuery');
    }

    /**
     * Filter the query by a related \LocationImage object
     *
     * @param \LocationImage|ObjectCollection $locationImage the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocationQuery The current query, for fluid interface
     */
    public function filterByImage($locationImage, $comparison = null)
    {
        if ($locationImage instanceof \LocationImage) {
            return $this
                ->addUsingAlias(LocationTableMap::COL_ID, $locationImage->getLocationId(), $comparison);
        } elseif ($locationImage instanceof ObjectCollection) {
            return $this
                ->useImageQuery()
                ->filterByPrimaryKeys($locationImage->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByImage() only accepts arguments of type \LocationImage or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Image relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function joinImage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Image');

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
            $this->addJoinObject($join, 'Image');
        }

        return $this;
    }

    /**
     * Use the Image relation LocationImage object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LocationImageQuery A secondary query class using the current class as primary query
     */
    public function useImageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinImage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Image', '\LocationImageQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLocation $location Object to remove from the list of results
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function prune($location = null)
    {
        if ($location) {
            $this->addUsingAlias(LocationTableMap::COL_ID, $location->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the location table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LocationTableMap::clearInstancePool();
            LocationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LocationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LocationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LocationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LocationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LocationQuery
