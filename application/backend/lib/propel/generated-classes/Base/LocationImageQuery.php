<?php

namespace Base;

use \LocationImage as ChildLocationImage;
use \LocationImageQuery as ChildLocationImageQuery;
use \Exception;
use \PDO;
use Map\LocationImageTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'location_image' table.
 *
 *
 *
 * @method     ChildLocationImageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLocationImageQuery orderByLocationId($order = Criteria::ASC) Order by the location_id column
 * @method     ChildLocationImageQuery orderByInserterId($order = Criteria::ASC) Order by the inserter_id column
 * @method     ChildLocationImageQuery orderByInsertedTs($order = Criteria::ASC) Order by the inserted_ts column
 * @method     ChildLocationImageQuery orderByApproved($order = Criteria::ASC) Order by the approved column
 * @method     ChildLocationImageQuery orderByHash($order = Criteria::ASC) Order by the hash column
 *
 * @method     ChildLocationImageQuery groupById() Group by the id column
 * @method     ChildLocationImageQuery groupByLocationId() Group by the location_id column
 * @method     ChildLocationImageQuery groupByInserterId() Group by the inserter_id column
 * @method     ChildLocationImageQuery groupByInsertedTs() Group by the inserted_ts column
 * @method     ChildLocationImageQuery groupByApproved() Group by the approved column
 * @method     ChildLocationImageQuery groupByHash() Group by the hash column
 *
 * @method     ChildLocationImageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLocationImageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLocationImageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLocationImageQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLocationImageQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLocationImageQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLocationImageQuery leftJoinLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the Location relation
 * @method     ChildLocationImageQuery rightJoinLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Location relation
 * @method     ChildLocationImageQuery innerJoinLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the Location relation
 *
 * @method     ChildLocationImageQuery joinWithLocation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Location relation
 *
 * @method     ChildLocationImageQuery leftJoinWithLocation() Adds a LEFT JOIN clause and with to the query using the Location relation
 * @method     ChildLocationImageQuery rightJoinWithLocation() Adds a RIGHT JOIN clause and with to the query using the Location relation
 * @method     ChildLocationImageQuery innerJoinWithLocation() Adds a INNER JOIN clause and with to the query using the Location relation
 *
 * @method     ChildLocationImageQuery leftJoinInserter($relationAlias = null) Adds a LEFT JOIN clause to the query using the Inserter relation
 * @method     ChildLocationImageQuery rightJoinInserter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Inserter relation
 * @method     ChildLocationImageQuery innerJoinInserter($relationAlias = null) Adds a INNER JOIN clause to the query using the Inserter relation
 *
 * @method     ChildLocationImageQuery joinWithInserter($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Inserter relation
 *
 * @method     ChildLocationImageQuery leftJoinWithInserter() Adds a LEFT JOIN clause and with to the query using the Inserter relation
 * @method     ChildLocationImageQuery rightJoinWithInserter() Adds a RIGHT JOIN clause and with to the query using the Inserter relation
 * @method     ChildLocationImageQuery innerJoinWithInserter() Adds a INNER JOIN clause and with to the query using the Inserter relation
 *
 * @method     \LocationQuery|\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLocationImage findOne(ConnectionInterface $con = null) Return the first ChildLocationImage matching the query
 * @method     ChildLocationImage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLocationImage matching the query, or a new ChildLocationImage object populated from the query conditions when no match is found
 *
 * @method     ChildLocationImage findOneById(int $id) Return the first ChildLocationImage filtered by the id column
 * @method     ChildLocationImage findOneByLocationId(int $location_id) Return the first ChildLocationImage filtered by the location_id column
 * @method     ChildLocationImage findOneByInserterId(int $inserter_id) Return the first ChildLocationImage filtered by the inserter_id column
 * @method     ChildLocationImage findOneByInsertedTs(int $inserted_ts) Return the first ChildLocationImage filtered by the inserted_ts column
 * @method     ChildLocationImage findOneByApproved(boolean $approved) Return the first ChildLocationImage filtered by the approved column
 * @method     ChildLocationImage findOneByHash(string $hash) Return the first ChildLocationImage filtered by the hash column *

 * @method     ChildLocationImage requirePk($key, ConnectionInterface $con = null) Return the ChildLocationImage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationImage requireOne(ConnectionInterface $con = null) Return the first ChildLocationImage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocationImage requireOneById(int $id) Return the first ChildLocationImage filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationImage requireOneByLocationId(int $location_id) Return the first ChildLocationImage filtered by the location_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationImage requireOneByInserterId(int $inserter_id) Return the first ChildLocationImage filtered by the inserter_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationImage requireOneByInsertedTs(int $inserted_ts) Return the first ChildLocationImage filtered by the inserted_ts column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationImage requireOneByApproved(boolean $approved) Return the first ChildLocationImage filtered by the approved column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocationImage requireOneByHash(string $hash) Return the first ChildLocationImage filtered by the hash column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocationImage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLocationImage objects based on current ModelCriteria
 * @method     ChildLocationImage[]|ObjectCollection findById(int $id) Return ChildLocationImage objects filtered by the id column
 * @method     ChildLocationImage[]|ObjectCollection findByLocationId(int $location_id) Return ChildLocationImage objects filtered by the location_id column
 * @method     ChildLocationImage[]|ObjectCollection findByInserterId(int $inserter_id) Return ChildLocationImage objects filtered by the inserter_id column
 * @method     ChildLocationImage[]|ObjectCollection findByInsertedTs(int $inserted_ts) Return ChildLocationImage objects filtered by the inserted_ts column
 * @method     ChildLocationImage[]|ObjectCollection findByApproved(boolean $approved) Return ChildLocationImage objects filtered by the approved column
 * @method     ChildLocationImage[]|ObjectCollection findByHash(string $hash) Return ChildLocationImage objects filtered by the hash column
 * @method     ChildLocationImage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LocationImageQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\LocationImageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'catch_me', $modelName = '\\LocationImage', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLocationImageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLocationImageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLocationImageQuery) {
            return $criteria;
        }
        $query = new ChildLocationImageQuery();
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
     * @return ChildLocationImage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LocationImageTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LocationImageTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLocationImage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, location_id, inserter_id, inserted_ts, approved, hash FROM location_image WHERE id = :p0';
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
            /** @var ChildLocationImage $obj */
            $obj = new ChildLocationImage();
            $obj->hydrate($row);
            LocationImageTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLocationImage|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LocationImageTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LocationImageTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LocationImageTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LocationImageTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationImageTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
     */
    public function filterByLocationId($locationId = null, $comparison = null)
    {
        if (is_array($locationId)) {
            $useMinMax = false;
            if (isset($locationId['min'])) {
                $this->addUsingAlias(LocationImageTableMap::COL_LOCATION_ID, $locationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($locationId['max'])) {
                $this->addUsingAlias(LocationImageTableMap::COL_LOCATION_ID, $locationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationImageTableMap::COL_LOCATION_ID, $locationId, $comparison);
    }

    /**
     * Filter the query on the inserter_id column
     *
     * Example usage:
     * <code>
     * $query->filterByInserterId(1234); // WHERE inserter_id = 1234
     * $query->filterByInserterId(array(12, 34)); // WHERE inserter_id IN (12, 34)
     * $query->filterByInserterId(array('min' => 12)); // WHERE inserter_id > 12
     * </code>
     *
     * @see       filterByInserter()
     *
     * @param     mixed $inserterId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
     */
    public function filterByInserterId($inserterId = null, $comparison = null)
    {
        if (is_array($inserterId)) {
            $useMinMax = false;
            if (isset($inserterId['min'])) {
                $this->addUsingAlias(LocationImageTableMap::COL_INSERTER_ID, $inserterId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($inserterId['max'])) {
                $this->addUsingAlias(LocationImageTableMap::COL_INSERTER_ID, $inserterId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationImageTableMap::COL_INSERTER_ID, $inserterId, $comparison);
    }

    /**
     * Filter the query on the inserted_ts column
     *
     * Example usage:
     * <code>
     * $query->filterByInsertedTs(1234); // WHERE inserted_ts = 1234
     * $query->filterByInsertedTs(array(12, 34)); // WHERE inserted_ts IN (12, 34)
     * $query->filterByInsertedTs(array('min' => 12)); // WHERE inserted_ts > 12
     * </code>
     *
     * @param     mixed $insertedTs The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
     */
    public function filterByInsertedTs($insertedTs = null, $comparison = null)
    {
        if (is_array($insertedTs)) {
            $useMinMax = false;
            if (isset($insertedTs['min'])) {
                $this->addUsingAlias(LocationImageTableMap::COL_INSERTED_TS, $insertedTs['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insertedTs['max'])) {
                $this->addUsingAlias(LocationImageTableMap::COL_INSERTED_TS, $insertedTs['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationImageTableMap::COL_INSERTED_TS, $insertedTs, $comparison);
    }

    /**
     * Filter the query on the approved column
     *
     * Example usage:
     * <code>
     * $query->filterByApproved(true); // WHERE approved = true
     * $query->filterByApproved('yes'); // WHERE approved = true
     * </code>
     *
     * @param     boolean|string $approved The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
     */
    public function filterByApproved($approved = null, $comparison = null)
    {
        if (is_string($approved)) {
            $approved = in_array(strtolower($approved), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LocationImageTableMap::COL_APPROVED, $approved, $comparison);
    }

    /**
     * Filter the query on the hash column
     *
     * Example usage:
     * <code>
     * $query->filterByHash('fooValue');   // WHERE hash = 'fooValue'
     * $query->filterByHash('%fooValue%', Criteria::LIKE); // WHERE hash LIKE '%fooValue%'
     * </code>
     *
     * @param     string $hash The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
     */
    public function filterByHash($hash = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($hash)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationImageTableMap::COL_HASH, $hash, $comparison);
    }

    /**
     * Filter the query by a related \Location object
     *
     * @param \Location|ObjectCollection $location The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocationImageQuery The current query, for fluid interface
     */
    public function filterByLocation($location, $comparison = null)
    {
        if ($location instanceof \Location) {
            return $this
                ->addUsingAlias(LocationImageTableMap::COL_LOCATION_ID, $location->getId(), $comparison);
        } elseif ($location instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LocationImageTableMap::COL_LOCATION_ID, $location->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
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
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocationImageQuery The current query, for fluid interface
     */
    public function filterByInserter($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(LocationImageTableMap::COL_INSERTER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LocationImageTableMap::COL_INSERTER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByInserter() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Inserter relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
     */
    public function joinInserter($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Inserter');

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
            $this->addJoinObject($join, 'Inserter');
        }

        return $this;
    }

    /**
     * Use the Inserter relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useInserterQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInserter($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Inserter', '\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLocationImage $locationImage Object to remove from the list of results
     *
     * @return $this|ChildLocationImageQuery The current query, for fluid interface
     */
    public function prune($locationImage = null)
    {
        if ($locationImage) {
            $this->addUsingAlias(LocationImageTableMap::COL_ID, $locationImage->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the location_image table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationImageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LocationImageTableMap::clearInstancePool();
            LocationImageTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LocationImageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LocationImageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LocationImageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LocationImageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LocationImageQuery
