<?php

namespace Base;

use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \Exception;
use \PDO;
use Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user' table.
 *
 *
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByApiKey($order = Criteria::ASC) Order by the api_key column
 * @method     ChildUserQuery orderByPassSha256($order = Criteria::ASC) Order by the pass_sha256 column
 * @method     ChildUserQuery orderByPassSalt($order = Criteria::ASC) Order by the pass_salt column
 * @method     ChildUserQuery orderByBan($order = Criteria::ASC) Order by the ban column
 * @method     ChildUserQuery orderByPrivacy($order = Criteria::ASC) Order by the privacy column
 * @method     ChildUserQuery orderBySignupTs($order = Criteria::ASC) Order by the signup_ts column
 * @method     ChildUserQuery orderByGender($order = Criteria::ASC) Order by the gender column
 * @method     ChildUserQuery orderByReputation($order = Criteria::ASC) Order by the reputation column
 * @method     ChildUserQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method     ChildUserQuery orderByPublicMessage($order = Criteria::ASC) Order by the public_message column
 * @method     ChildUserQuery orderByPictureUrl($order = Criteria::ASC) Order by the picture_url column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByName() Group by the name column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByApiKey() Group by the api_key column
 * @method     ChildUserQuery groupByPassSha256() Group by the pass_sha256 column
 * @method     ChildUserQuery groupByPassSalt() Group by the pass_salt column
 * @method     ChildUserQuery groupByBan() Group by the ban column
 * @method     ChildUserQuery groupByPrivacy() Group by the privacy column
 * @method     ChildUserQuery groupBySignupTs() Group by the signup_ts column
 * @method     ChildUserQuery groupByGender() Group by the gender column
 * @method     ChildUserQuery groupByReputation() Group by the reputation column
 * @method     ChildUserQuery groupByPhone() Group by the phone column
 * @method     ChildUserQuery groupByPublicMessage() Group by the public_message column
 * @method     ChildUserQuery groupByPictureUrl() Group by the picture_url column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinSocial($relationAlias = null) Adds a LEFT JOIN clause to the query using the Social relation
 * @method     ChildUserQuery rightJoinSocial($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Social relation
 * @method     ChildUserQuery innerJoinSocial($relationAlias = null) Adds a INNER JOIN clause to the query using the Social relation
 *
 * @method     ChildUserQuery joinWithSocial($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Social relation
 *
 * @method     ChildUserQuery leftJoinWithSocial() Adds a LEFT JOIN clause and with to the query using the Social relation
 * @method     ChildUserQuery rightJoinWithSocial() Adds a RIGHT JOIN clause and with to the query using the Social relation
 * @method     ChildUserQuery innerJoinWithSocial() Adds a INNER JOIN clause and with to the query using the Social relation
 *
 * @method     ChildUserQuery leftJoinInsertedLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the InsertedLocation relation
 * @method     ChildUserQuery rightJoinInsertedLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InsertedLocation relation
 * @method     ChildUserQuery innerJoinInsertedLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the InsertedLocation relation
 *
 * @method     ChildUserQuery joinWithInsertedLocation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InsertedLocation relation
 *
 * @method     ChildUserQuery leftJoinWithInsertedLocation() Adds a LEFT JOIN clause and with to the query using the InsertedLocation relation
 * @method     ChildUserQuery rightJoinWithInsertedLocation() Adds a RIGHT JOIN clause and with to the query using the InsertedLocation relation
 * @method     ChildUserQuery innerJoinWithInsertedLocation() Adds a INNER JOIN clause and with to the query using the InsertedLocation relation
 *
 * @method     ChildUserQuery leftJoinSearchString($relationAlias = null) Adds a LEFT JOIN clause to the query using the SearchString relation
 * @method     ChildUserQuery rightJoinSearchString($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SearchString relation
 * @method     ChildUserQuery innerJoinSearchString($relationAlias = null) Adds a INNER JOIN clause to the query using the SearchString relation
 *
 * @method     ChildUserQuery joinWithSearchString($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SearchString relation
 *
 * @method     ChildUserQuery leftJoinWithSearchString() Adds a LEFT JOIN clause and with to the query using the SearchString relation
 * @method     ChildUserQuery rightJoinWithSearchString() Adds a RIGHT JOIN clause and with to the query using the SearchString relation
 * @method     ChildUserQuery innerJoinWithSearchString() Adds a INNER JOIN clause and with to the query using the SearchString relation
 *
 * @method     ChildUserQuery leftJoinFavoriteLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the FavoriteLocation relation
 * @method     ChildUserQuery rightJoinFavoriteLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FavoriteLocation relation
 * @method     ChildUserQuery innerJoinFavoriteLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the FavoriteLocation relation
 *
 * @method     ChildUserQuery joinWithFavoriteLocation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the FavoriteLocation relation
 *
 * @method     ChildUserQuery leftJoinWithFavoriteLocation() Adds a LEFT JOIN clause and with to the query using the FavoriteLocation relation
 * @method     ChildUserQuery rightJoinWithFavoriteLocation() Adds a RIGHT JOIN clause and with to the query using the FavoriteLocation relation
 * @method     ChildUserQuery innerJoinWithFavoriteLocation() Adds a INNER JOIN clause and with to the query using the FavoriteLocation relation
 *
 * @method     ChildUserQuery leftJoinAddedConnection($relationAlias = null) Adds a LEFT JOIN clause to the query using the AddedConnection relation
 * @method     ChildUserQuery rightJoinAddedConnection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AddedConnection relation
 * @method     ChildUserQuery innerJoinAddedConnection($relationAlias = null) Adds a INNER JOIN clause to the query using the AddedConnection relation
 *
 * @method     ChildUserQuery joinWithAddedConnection($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AddedConnection relation
 *
 * @method     ChildUserQuery leftJoinWithAddedConnection() Adds a LEFT JOIN clause and with to the query using the AddedConnection relation
 * @method     ChildUserQuery rightJoinWithAddedConnection() Adds a RIGHT JOIN clause and with to the query using the AddedConnection relation
 * @method     ChildUserQuery innerJoinWithAddedConnection() Adds a INNER JOIN clause and with to the query using the AddedConnection relation
 *
 * @method     ChildUserQuery leftJoinRequestedConnection($relationAlias = null) Adds a LEFT JOIN clause to the query using the RequestedConnection relation
 * @method     ChildUserQuery rightJoinRequestedConnection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RequestedConnection relation
 * @method     ChildUserQuery innerJoinRequestedConnection($relationAlias = null) Adds a INNER JOIN clause to the query using the RequestedConnection relation
 *
 * @method     ChildUserQuery joinWithRequestedConnection($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the RequestedConnection relation
 *
 * @method     ChildUserQuery leftJoinWithRequestedConnection() Adds a LEFT JOIN clause and with to the query using the RequestedConnection relation
 * @method     ChildUserQuery rightJoinWithRequestedConnection() Adds a RIGHT JOIN clause and with to the query using the RequestedConnection relation
 * @method     ChildUserQuery innerJoinWithRequestedConnection() Adds a INNER JOIN clause and with to the query using the RequestedConnection relation
 *
 * @method     ChildUserQuery leftJoinLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the Location relation
 * @method     ChildUserQuery rightJoinLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Location relation
 * @method     ChildUserQuery innerJoinLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the Location relation
 *
 * @method     ChildUserQuery joinWithLocation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Location relation
 *
 * @method     ChildUserQuery leftJoinWithLocation() Adds a LEFT JOIN clause and with to the query using the Location relation
 * @method     ChildUserQuery rightJoinWithLocation() Adds a RIGHT JOIN clause and with to the query using the Location relation
 * @method     ChildUserQuery innerJoinWithLocation() Adds a INNER JOIN clause and with to the query using the Location relation
 *
 * @method     ChildUserQuery leftJoinExpiredLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the ExpiredLocation relation
 * @method     ChildUserQuery rightJoinExpiredLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ExpiredLocation relation
 * @method     ChildUserQuery innerJoinExpiredLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the ExpiredLocation relation
 *
 * @method     ChildUserQuery joinWithExpiredLocation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ExpiredLocation relation
 *
 * @method     ChildUserQuery leftJoinWithExpiredLocation() Adds a LEFT JOIN clause and with to the query using the ExpiredLocation relation
 * @method     ChildUserQuery rightJoinWithExpiredLocation() Adds a RIGHT JOIN clause and with to the query using the ExpiredLocation relation
 * @method     ChildUserQuery innerJoinWithExpiredLocation() Adds a INNER JOIN clause and with to the query using the ExpiredLocation relation
 *
 * @method     ChildUserQuery leftJoinInsertedImage($relationAlias = null) Adds a LEFT JOIN clause to the query using the InsertedImage relation
 * @method     ChildUserQuery rightJoinInsertedImage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InsertedImage relation
 * @method     ChildUserQuery innerJoinInsertedImage($relationAlias = null) Adds a INNER JOIN clause to the query using the InsertedImage relation
 *
 * @method     ChildUserQuery joinWithInsertedImage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InsertedImage relation
 *
 * @method     ChildUserQuery leftJoinWithInsertedImage() Adds a LEFT JOIN clause and with to the query using the InsertedImage relation
 * @method     ChildUserQuery rightJoinWithInsertedImage() Adds a RIGHT JOIN clause and with to the query using the InsertedImage relation
 * @method     ChildUserQuery innerJoinWithInsertedImage() Adds a INNER JOIN clause and with to the query using the InsertedImage relation
 *
 * @method     \UserSocialQuery|\LocationQuery|\SearchUserQuery|\UserLocationFavoriteQuery|\UserConnectionQuery|\UserLocationQuery|\UserLocationExpiredQuery|\LocationImageQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser findOneByName(string $name) Return the first ChildUser filtered by the name column
 * @method     ChildUser findOneByEmail(string $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser findOneByApiKey(string $api_key) Return the first ChildUser filtered by the api_key column
 * @method     ChildUser findOneByPassSha256(string $pass_sha256) Return the first ChildUser filtered by the pass_sha256 column
 * @method     ChildUser findOneByPassSalt(string $pass_salt) Return the first ChildUser filtered by the pass_salt column
 * @method     ChildUser findOneByBan(boolean $ban) Return the first ChildUser filtered by the ban column
 * @method     ChildUser findOneByPrivacy(string $privacy) Return the first ChildUser filtered by the privacy column
 * @method     ChildUser findOneBySignupTs(int $signup_ts) Return the first ChildUser filtered by the signup_ts column
 * @method     ChildUser findOneByGender(int $gender) Return the first ChildUser filtered by the gender column
 * @method     ChildUser findOneByReputation(int $reputation) Return the first ChildUser filtered by the reputation column
 * @method     ChildUser findOneByPhone(string $phone) Return the first ChildUser filtered by the phone column
 * @method     ChildUser findOneByPublicMessage(string $public_message) Return the first ChildUser filtered by the public_message column
 * @method     ChildUser findOneByPictureUrl(string $picture_url) Return the first ChildUser filtered by the picture_url column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByName(string $name) Return the first ChildUser filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(string $email) Return the first ChildUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByApiKey(string $api_key) Return the first ChildUser filtered by the api_key column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassSha256(string $pass_sha256) Return the first ChildUser filtered by the pass_sha256 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassSalt(string $pass_salt) Return the first ChildUser filtered by the pass_salt column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByBan(boolean $ban) Return the first ChildUser filtered by the ban column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPrivacy(string $privacy) Return the first ChildUser filtered by the privacy column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySignupTs(int $signup_ts) Return the first ChildUser filtered by the signup_ts column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByGender(int $gender) Return the first ChildUser filtered by the gender column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByReputation(int $reputation) Return the first ChildUser filtered by the reputation column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPhone(string $phone) Return the first ChildUser filtered by the phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPublicMessage(string $public_message) Return the first ChildUser filtered by the public_message column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPictureUrl(string $picture_url) Return the first ChildUser filtered by the picture_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByName(string $name) Return ChildUser objects filtered by the name column
 * @method     ChildUser[]|ObjectCollection findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|ObjectCollection findByApiKey(string $api_key) Return ChildUser objects filtered by the api_key column
 * @method     ChildUser[]|ObjectCollection findByPassSha256(string $pass_sha256) Return ChildUser objects filtered by the pass_sha256 column
 * @method     ChildUser[]|ObjectCollection findByPassSalt(string $pass_salt) Return ChildUser objects filtered by the pass_salt column
 * @method     ChildUser[]|ObjectCollection findByBan(boolean $ban) Return ChildUser objects filtered by the ban column
 * @method     ChildUser[]|ObjectCollection findByPrivacy(string $privacy) Return ChildUser objects filtered by the privacy column
 * @method     ChildUser[]|ObjectCollection findBySignupTs(int $signup_ts) Return ChildUser objects filtered by the signup_ts column
 * @method     ChildUser[]|ObjectCollection findByGender(int $gender) Return ChildUser objects filtered by the gender column
 * @method     ChildUser[]|ObjectCollection findByReputation(int $reputation) Return ChildUser objects filtered by the reputation column
 * @method     ChildUser[]|ObjectCollection findByPhone(string $phone) Return ChildUser objects filtered by the phone column
 * @method     ChildUser[]|ObjectCollection findByPublicMessage(string $public_message) Return ChildUser objects filtered by the public_message column
 * @method     ChildUser[]|ObjectCollection findByPictureUrl(string $picture_url) Return ChildUser objects filtered by the picture_url column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'catch_me', $modelName = '\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, email, api_key, pass_sha256, pass_salt, ban, privacy, signup_ts, gender, reputation, phone, public_message, picture_url FROM user WHERE id = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the api_key column
     *
     * Example usage:
     * <code>
     * $query->filterByApiKey('fooValue');   // WHERE api_key = 'fooValue'
     * $query->filterByApiKey('%fooValue%', Criteria::LIKE); // WHERE api_key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $apiKey The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByApiKey($apiKey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($apiKey)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_API_KEY, $apiKey, $comparison);
    }

    /**
     * Filter the query on the pass_sha256 column
     *
     * Example usage:
     * <code>
     * $query->filterByPassSha256('fooValue');   // WHERE pass_sha256 = 'fooValue'
     * $query->filterByPassSha256('%fooValue%', Criteria::LIKE); // WHERE pass_sha256 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $passSha256 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassSha256($passSha256 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($passSha256)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASS_SHA256, $passSha256, $comparison);
    }

    /**
     * Filter the query on the pass_salt column
     *
     * Example usage:
     * <code>
     * $query->filterByPassSalt('fooValue');   // WHERE pass_salt = 'fooValue'
     * $query->filterByPassSalt('%fooValue%', Criteria::LIKE); // WHERE pass_salt LIKE '%fooValue%'
     * </code>
     *
     * @param     string $passSalt The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassSalt($passSalt = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($passSalt)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASS_SALT, $passSalt, $comparison);
    }

    /**
     * Filter the query on the ban column
     *
     * Example usage:
     * <code>
     * $query->filterByBan(true); // WHERE ban = true
     * $query->filterByBan('yes'); // WHERE ban = true
     * </code>
     *
     * @param     boolean|string $ban The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByBan($ban = null, $comparison = null)
    {
        if (is_string($ban)) {
            $ban = in_array(strtolower($ban), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_BAN, $ban, $comparison);
    }

    /**
     * Filter the query on the privacy column
     *
     * Example usage:
     * <code>
     * $query->filterByPrivacy('fooValue');   // WHERE privacy = 'fooValue'
     * $query->filterByPrivacy('%fooValue%', Criteria::LIKE); // WHERE privacy LIKE '%fooValue%'
     * </code>
     *
     * @param     string $privacy The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrivacy($privacy = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($privacy)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PRIVACY, $privacy, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterBySignupTs($signupTs = null, $comparison = null)
    {
        if (is_array($signupTs)) {
            $useMinMax = false;
            if (isset($signupTs['min'])) {
                $this->addUsingAlias(UserTableMap::COL_SIGNUP_TS, $signupTs['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($signupTs['max'])) {
                $this->addUsingAlias(UserTableMap::COL_SIGNUP_TS, $signupTs['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_SIGNUP_TS, $signupTs, $comparison);
    }

    /**
     * Filter the query on the gender column
     *
     * Example usage:
     * <code>
     * $query->filterByGender(1234); // WHERE gender = 1234
     * $query->filterByGender(array(12, 34)); // WHERE gender IN (12, 34)
     * $query->filterByGender(array('min' => 12)); // WHERE gender > 12
     * </code>
     *
     * @param     mixed $gender The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByGender($gender = null, $comparison = null)
    {
        if (is_array($gender)) {
            $useMinMax = false;
            if (isset($gender['min'])) {
                $this->addUsingAlias(UserTableMap::COL_GENDER, $gender['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gender['max'])) {
                $this->addUsingAlias(UserTableMap::COL_GENDER, $gender['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_GENDER, $gender, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByReputation($reputation = null, $comparison = null)
    {
        if (is_array($reputation)) {
            $useMinMax = false;
            if (isset($reputation['min'])) {
                $this->addUsingAlias(UserTableMap::COL_REPUTATION, $reputation['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($reputation['max'])) {
                $this->addUsingAlias(UserTableMap::COL_REPUTATION, $reputation['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_REPUTATION, $reputation, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the public_message column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicMessage('fooValue');   // WHERE public_message = 'fooValue'
     * $query->filterByPublicMessage('%fooValue%', Criteria::LIKE); // WHERE public_message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $publicMessage The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPublicMessage($publicMessage = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($publicMessage)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PUBLIC_MESSAGE, $publicMessage, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPictureUrl($pictureUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pictureUrl)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PICTURE_URL, $pictureUrl, $comparison);
    }

    /**
     * Filter the query by a related \UserSocial object
     *
     * @param \UserSocial|ObjectCollection $userSocial the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterBySocial($userSocial, $comparison = null)
    {
        if ($userSocial instanceof \UserSocial) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userSocial->getUserId(), $comparison);
        } elseif ($userSocial instanceof ObjectCollection) {
            return $this
                ->useSocialQuery()
                ->filterByPrimaryKeys($userSocial->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySocial() only accepts arguments of type \UserSocial or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Social relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinSocial($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Social');

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
            $this->addJoinObject($join, 'Social');
        }

        return $this;
    }

    /**
     * Use the Social relation UserSocial object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserSocialQuery A secondary query class using the current class as primary query
     */
    public function useSocialQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSocial($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Social', '\UserSocialQuery');
    }

    /**
     * Filter the query by a related \Location object
     *
     * @param \Location|ObjectCollection $location the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByInsertedLocation($location, $comparison = null)
    {
        if ($location instanceof \Location) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $location->getAdminId(), $comparison);
        } elseif ($location instanceof ObjectCollection) {
            return $this
                ->useInsertedLocationQuery()
                ->filterByPrimaryKeys($location->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInsertedLocation() only accepts arguments of type \Location or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InsertedLocation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinInsertedLocation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InsertedLocation');

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
            $this->addJoinObject($join, 'InsertedLocation');
        }

        return $this;
    }

    /**
     * Use the InsertedLocation relation Location object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LocationQuery A secondary query class using the current class as primary query
     */
    public function useInsertedLocationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInsertedLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InsertedLocation', '\LocationQuery');
    }

    /**
     * Filter the query by a related \SearchUser object
     *
     * @param \SearchUser|ObjectCollection $searchUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterBySearchString($searchUser, $comparison = null)
    {
        if ($searchUser instanceof \SearchUser) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $searchUser->getUserId(), $comparison);
        } elseif ($searchUser instanceof ObjectCollection) {
            return $this
                ->useSearchStringQuery()
                ->filterByPrimaryKeys($searchUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySearchString() only accepts arguments of type \SearchUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SearchString relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * Use the SearchString relation SearchUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SearchUserQuery A secondary query class using the current class as primary query
     */
    public function useSearchStringQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSearchString($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SearchString', '\SearchUserQuery');
    }

    /**
     * Filter the query by a related \UserLocationFavorite object
     *
     * @param \UserLocationFavorite|ObjectCollection $userLocationFavorite the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByFavoriteLocation($userLocationFavorite, $comparison = null)
    {
        if ($userLocationFavorite instanceof \UserLocationFavorite) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userLocationFavorite->getUserId(), $comparison);
        } elseif ($userLocationFavorite instanceof ObjectCollection) {
            return $this
                ->useFavoriteLocationQuery()
                ->filterByPrimaryKeys($userLocationFavorite->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFavoriteLocation() only accepts arguments of type \UserLocationFavorite or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FavoriteLocation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinFavoriteLocation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FavoriteLocation');

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
            $this->addJoinObject($join, 'FavoriteLocation');
        }

        return $this;
    }

    /**
     * Use the FavoriteLocation relation UserLocationFavorite object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserLocationFavoriteQuery A secondary query class using the current class as primary query
     */
    public function useFavoriteLocationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFavoriteLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FavoriteLocation', '\UserLocationFavoriteQuery');
    }

    /**
     * Filter the query by a related \UserConnection object
     *
     * @param \UserConnection|ObjectCollection $userConnection the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByAddedConnection($userConnection, $comparison = null)
    {
        if ($userConnection instanceof \UserConnection) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userConnection->getUserId(), $comparison);
        } elseif ($userConnection instanceof ObjectCollection) {
            return $this
                ->useAddedConnectionQuery()
                ->filterByPrimaryKeys($userConnection->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAddedConnection() only accepts arguments of type \UserConnection or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AddedConnection relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinAddedConnection($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AddedConnection');

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
            $this->addJoinObject($join, 'AddedConnection');
        }

        return $this;
    }

    /**
     * Use the AddedConnection relation UserConnection object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserConnectionQuery A secondary query class using the current class as primary query
     */
    public function useAddedConnectionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAddedConnection($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AddedConnection', '\UserConnectionQuery');
    }

    /**
     * Filter the query by a related \UserConnection object
     *
     * @param \UserConnection|ObjectCollection $userConnection the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByRequestedConnection($userConnection, $comparison = null)
    {
        if ($userConnection instanceof \UserConnection) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userConnection->getConnectionId(), $comparison);
        } elseif ($userConnection instanceof ObjectCollection) {
            return $this
                ->useRequestedConnectionQuery()
                ->filterByPrimaryKeys($userConnection->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRequestedConnection() only accepts arguments of type \UserConnection or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RequestedConnection relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinRequestedConnection($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RequestedConnection');

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
            $this->addJoinObject($join, 'RequestedConnection');
        }

        return $this;
    }

    /**
     * Use the RequestedConnection relation UserConnection object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserConnectionQuery A secondary query class using the current class as primary query
     */
    public function useRequestedConnectionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRequestedConnection($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'RequestedConnection', '\UserConnectionQuery');
    }

    /**
     * Filter the query by a related \UserLocation object
     *
     * @param \UserLocation|ObjectCollection $userLocation the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByLocation($userLocation, $comparison = null)
    {
        if ($userLocation instanceof \UserLocation) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userLocation->getUserId(), $comparison);
        } elseif ($userLocation instanceof ObjectCollection) {
            return $this
                ->useLocationQuery()
                ->filterByPrimaryKeys($userLocation->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLocation() only accepts arguments of type \UserLocation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Location relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * Use the Location relation UserLocation object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserLocationQuery A secondary query class using the current class as primary query
     */
    public function useLocationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Location', '\UserLocationQuery');
    }

    /**
     * Filter the query by a related \UserLocationExpired object
     *
     * @param \UserLocationExpired|ObjectCollection $userLocationExpired the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByExpiredLocation($userLocationExpired, $comparison = null)
    {
        if ($userLocationExpired instanceof \UserLocationExpired) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userLocationExpired->getUserId(), $comparison);
        } elseif ($userLocationExpired instanceof ObjectCollection) {
            return $this
                ->useExpiredLocationQuery()
                ->filterByPrimaryKeys($userLocationExpired->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByExpiredLocation() only accepts arguments of type \UserLocationExpired or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ExpiredLocation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinExpiredLocation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ExpiredLocation');

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
            $this->addJoinObject($join, 'ExpiredLocation');
        }

        return $this;
    }

    /**
     * Use the ExpiredLocation relation UserLocationExpired object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserLocationExpiredQuery A secondary query class using the current class as primary query
     */
    public function useExpiredLocationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinExpiredLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ExpiredLocation', '\UserLocationExpiredQuery');
    }

    /**
     * Filter the query by a related \LocationImage object
     *
     * @param \LocationImage|ObjectCollection $locationImage the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByInsertedImage($locationImage, $comparison = null)
    {
        if ($locationImage instanceof \LocationImage) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $locationImage->getInserterId(), $comparison);
        } elseif ($locationImage instanceof ObjectCollection) {
            return $this
                ->useInsertedImageQuery()
                ->filterByPrimaryKeys($locationImage->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInsertedImage() only accepts arguments of type \LocationImage or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InsertedImage relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinInsertedImage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InsertedImage');

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
            $this->addJoinObject($join, 'InsertedImage');
        }

        return $this;
    }

    /**
     * Use the InsertedImage relation LocationImage object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LocationImageQuery A secondary query class using the current class as primary query
     */
    public function useInsertedImageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInsertedImage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InsertedImage', '\LocationImageQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserQuery
