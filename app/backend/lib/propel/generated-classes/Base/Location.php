<?php

namespace Base;

use \Location as ChildLocation;
use \LocationAddress as ChildLocationAddress;
use \LocationAddressQuery as ChildLocationAddressQuery;
use \LocationImage as ChildLocationImage;
use \LocationImageQuery as ChildLocationImageQuery;
use \LocationQuery as ChildLocationQuery;
use \SearchLocation as ChildSearchLocation;
use \SearchLocationQuery as ChildSearchLocationQuery;
use \User as ChildUser;
use \UserLocation as ChildUserLocation;
use \UserLocationExpired as ChildUserLocationExpired;
use \UserLocationExpiredQuery as ChildUserLocationExpiredQuery;
use \UserLocationFavorite as ChildUserLocationFavorite;
use \UserLocationFavoriteQuery as ChildUserLocationFavoriteQuery;
use \UserLocationQuery as ChildUserLocationQuery;
use \UserQuery as ChildUserQuery;
use \Exception;
use \PDO;
use Map\LocationImageTableMap;
use Map\LocationTableMap;
use Map\UserLocationExpiredTableMap;
use Map\UserLocationFavoriteTableMap;
use Map\UserLocationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'location' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class Location implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\LocationTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the admin_id field.
     *
     * @var        int
     */
    protected $admin_id;

    /**
     * The value for the signup_ts field.
     *
     * Note: this column has a database default value of: 1483228800
     * @var        int
     */
    protected $signup_ts;

    /**
     * The value for the verified field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $verified;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the description field.
     *
     * @var        string
     */
    protected $description;

    /**
     * The value for the capacity field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $capacity;

    /**
     * The value for the picture_url field.
     *
     * @var        string
     */
    protected $picture_url;

    /**
     * The value for the timings field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $timings;

    /**
     * The value for the reputation field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $reputation;

    /**
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the phone field.
     *
     * @var        string
     */
    protected $phone;

    /**
     * @var        ChildUser
     */
    protected $aAdmin;

    /**
     * @var        ChildLocationAddress one-to-one related ChildLocationAddress object
     */
    protected $singleAddress;

    /**
     * @var        ChildSearchLocation one-to-one related ChildSearchLocation object
     */
    protected $singleSearchString;

    /**
     * @var        ObjectCollection|ChildUserLocationFavorite[] Collection to store aggregation of ChildUserLocationFavorite objects.
     */
    protected $collSubscribedUsers;
    protected $collSubscribedUsersPartial;

    /**
     * @var        ObjectCollection|ChildUserLocation[] Collection to store aggregation of ChildUserLocation objects.
     */
    protected $collUsers;
    protected $collUsersPartial;

    /**
     * @var        ObjectCollection|ChildUserLocationExpired[] Collection to store aggregation of ChildUserLocationExpired objects.
     */
    protected $collExpiredUsers;
    protected $collExpiredUsersPartial;

    /**
     * @var        ObjectCollection|ChildLocationImage[] Collection to store aggregation of ChildLocationImage objects.
     */
    protected $collImages;
    protected $collImagesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserLocationFavorite[]
     */
    protected $subscribedUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserLocation[]
     */
    protected $usersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserLocationExpired[]
     */
    protected $expiredUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLocationImage[]
     */
    protected $imagesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->signup_ts = 1483228800;
        $this->verified = false;
        $this->capacity = 0;
        $this->timings = '';
        $this->reputation = 0;
    }

    /**
     * Initializes internal state of Base\Location object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Location</code> instance.  If
     * <code>obj</code> is an instance of <code>Location</code>, delegates to
     * <code>equals(Location)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Location The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [admin_id] column value.
     *
     * @return int
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * Get the [signup_ts] column value.
     *
     * @return int
     */
    public function getSignupTs()
    {
        return $this->signup_ts;
    }

    /**
     * Get the [verified] column value.
     *
     * @return boolean
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Get the [verified] column value.
     *
     * @return boolean
     */
    public function isVerified()
    {
        return $this->getVerified();
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [capacity] column value.
     *
     * @return int
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Get the [picture_url] column value.
     *
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->picture_url;
    }

    /**
     * Get the [timings] column value.
     *
     * @return string
     */
    public function getTimings()
    {
        return $this->timings;
    }

    /**
     * Get the [reputation] column value.
     *
     * @return int
     */
    public function getReputation()
    {
        return $this->reputation;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [phone] column value.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[LocationTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [admin_id] column.
     *
     * @param int $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setAdminId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->admin_id !== $v) {
            $this->admin_id = $v;
            $this->modifiedColumns[LocationTableMap::COL_ADMIN_ID] = true;
        }

        if ($this->aAdmin !== null && $this->aAdmin->getId() !== $v) {
            $this->aAdmin = null;
        }

        return $this;
    } // setAdminId()

    /**
     * Set the value of [signup_ts] column.
     *
     * @param int $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setSignupTs($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->signup_ts !== $v) {
            $this->signup_ts = $v;
            $this->modifiedColumns[LocationTableMap::COL_SIGNUP_TS] = true;
        }

        return $this;
    } // setSignupTs()

    /**
     * Sets the value of the [verified] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setVerified($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->verified !== $v) {
            $this->verified = $v;
            $this->modifiedColumns[LocationTableMap::COL_VERIFIED] = true;
        }

        return $this;
    } // setVerified()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[LocationTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[LocationTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [capacity] column.
     *
     * @param int $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setCapacity($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->capacity !== $v) {
            $this->capacity = $v;
            $this->modifiedColumns[LocationTableMap::COL_CAPACITY] = true;
        }

        return $this;
    } // setCapacity()

    /**
     * Set the value of [picture_url] column.
     *
     * @param string $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setPictureUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->picture_url !== $v) {
            $this->picture_url = $v;
            $this->modifiedColumns[LocationTableMap::COL_PICTURE_URL] = true;
        }

        return $this;
    } // setPictureUrl()

    /**
     * Set the value of [timings] column.
     *
     * @param string $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setTimings($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->timings !== $v) {
            $this->timings = $v;
            $this->modifiedColumns[LocationTableMap::COL_TIMINGS] = true;
        }

        return $this;
    } // setTimings()

    /**
     * Set the value of [reputation] column.
     *
     * @param int $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setReputation($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reputation !== $v) {
            $this->reputation = $v;
            $this->modifiedColumns[LocationTableMap::COL_REPUTATION] = true;
        }

        return $this;
    } // setReputation()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[LocationTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [phone] column.
     *
     * @param string $v new value
     * @return $this|\Location The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[LocationTableMap::COL_PHONE] = true;
        }

        return $this;
    } // setPhone()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->signup_ts !== 1483228800) {
                return false;
            }

            if ($this->verified !== false) {
                return false;
            }

            if ($this->capacity !== 0) {
                return false;
            }

            if ($this->timings !== '') {
                return false;
            }

            if ($this->reputation !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LocationTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LocationTableMap::translateFieldName('AdminId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->admin_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LocationTableMap::translateFieldName('SignupTs', TableMap::TYPE_PHPNAME, $indexType)];
            $this->signup_ts = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LocationTableMap::translateFieldName('Verified', TableMap::TYPE_PHPNAME, $indexType)];
            $this->verified = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LocationTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LocationTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : LocationTableMap::translateFieldName('Capacity', TableMap::TYPE_PHPNAME, $indexType)];
            $this->capacity = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : LocationTableMap::translateFieldName('PictureUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->picture_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : LocationTableMap::translateFieldName('Timings', TableMap::TYPE_PHPNAME, $indexType)];
            $this->timings = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : LocationTableMap::translateFieldName('Reputation', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reputation = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : LocationTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : LocationTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->phone = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = LocationTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Location'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aAdmin !== null && $this->admin_id !== $this->aAdmin->getId()) {
            $this->aAdmin = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LocationTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildLocationQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAdmin = null;
            $this->singleAddress = null;

            $this->singleSearchString = null;

            $this->collSubscribedUsers = null;

            $this->collUsers = null;

            $this->collExpiredUsers = null;

            $this->collImages = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Location::setDeleted()
     * @see Location::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildLocationQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                LocationTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aAdmin !== null) {
                if ($this->aAdmin->isModified() || $this->aAdmin->isNew()) {
                    $affectedRows += $this->aAdmin->save($con);
                }
                $this->setAdmin($this->aAdmin);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->singleAddress !== null) {
                if (!$this->singleAddress->isDeleted() && ($this->singleAddress->isNew() || $this->singleAddress->isModified())) {
                    $affectedRows += $this->singleAddress->save($con);
                }
            }

            if ($this->singleSearchString !== null) {
                if (!$this->singleSearchString->isDeleted() && ($this->singleSearchString->isNew() || $this->singleSearchString->isModified())) {
                    $affectedRows += $this->singleSearchString->save($con);
                }
            }

            if ($this->subscribedUsersScheduledForDeletion !== null) {
                if (!$this->subscribedUsersScheduledForDeletion->isEmpty()) {
                    \UserLocationFavoriteQuery::create()
                        ->filterByPrimaryKeys($this->subscribedUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->subscribedUsersScheduledForDeletion = null;
                }
            }

            if ($this->collSubscribedUsers !== null) {
                foreach ($this->collSubscribedUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    \UserLocationQuery::create()
                        ->filterByPrimaryKeys($this->usersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->usersScheduledForDeletion = null;
                }
            }

            if ($this->collUsers !== null) {
                foreach ($this->collUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->expiredUsersScheduledForDeletion !== null) {
                if (!$this->expiredUsersScheduledForDeletion->isEmpty()) {
                    \UserLocationExpiredQuery::create()
                        ->filterByPrimaryKeys($this->expiredUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->expiredUsersScheduledForDeletion = null;
                }
            }

            if ($this->collExpiredUsers !== null) {
                foreach ($this->collExpiredUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->imagesScheduledForDeletion !== null) {
                if (!$this->imagesScheduledForDeletion->isEmpty()) {
                    \LocationImageQuery::create()
                        ->filterByPrimaryKeys($this->imagesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->imagesScheduledForDeletion = null;
                }
            }

            if ($this->collImages !== null) {
                foreach ($this->collImages as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[LocationTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LocationTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LocationTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(LocationTableMap::COL_ADMIN_ID)) {
            $modifiedColumns[':p' . $index++]  = 'admin_id';
        }
        if ($this->isColumnModified(LocationTableMap::COL_SIGNUP_TS)) {
            $modifiedColumns[':p' . $index++]  = 'signup_ts';
        }
        if ($this->isColumnModified(LocationTableMap::COL_VERIFIED)) {
            $modifiedColumns[':p' . $index++]  = 'verified';
        }
        if ($this->isColumnModified(LocationTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(LocationTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(LocationTableMap::COL_CAPACITY)) {
            $modifiedColumns[':p' . $index++]  = 'capacity';
        }
        if ($this->isColumnModified(LocationTableMap::COL_PICTURE_URL)) {
            $modifiedColumns[':p' . $index++]  = 'picture_url';
        }
        if ($this->isColumnModified(LocationTableMap::COL_TIMINGS)) {
            $modifiedColumns[':p' . $index++]  = 'timings';
        }
        if ($this->isColumnModified(LocationTableMap::COL_REPUTATION)) {
            $modifiedColumns[':p' . $index++]  = 'reputation';
        }
        if ($this->isColumnModified(LocationTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(LocationTableMap::COL_PHONE)) {
            $modifiedColumns[':p' . $index++]  = 'phone';
        }

        $sql = sprintf(
            'INSERT INTO location (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'admin_id':
                        $stmt->bindValue($identifier, $this->admin_id, PDO::PARAM_INT);
                        break;
                    case 'signup_ts':
                        $stmt->bindValue($identifier, $this->signup_ts, PDO::PARAM_INT);
                        break;
                    case 'verified':
                        $stmt->bindValue($identifier, (int) $this->verified, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'description':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'capacity':
                        $stmt->bindValue($identifier, $this->capacity, PDO::PARAM_INT);
                        break;
                    case 'picture_url':
                        $stmt->bindValue($identifier, $this->picture_url, PDO::PARAM_STR);
                        break;
                    case 'timings':
                        $stmt->bindValue($identifier, $this->timings, PDO::PARAM_STR);
                        break;
                    case 'reputation':
                        $stmt->bindValue($identifier, $this->reputation, PDO::PARAM_INT);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'phone':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = LocationTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getAdminId();
                break;
            case 2:
                return $this->getSignupTs();
                break;
            case 3:
                return $this->getVerified();
                break;
            case 4:
                return $this->getName();
                break;
            case 5:
                return $this->getDescription();
                break;
            case 6:
                return $this->getCapacity();
                break;
            case 7:
                return $this->getPictureUrl();
                break;
            case 8:
                return $this->getTimings();
                break;
            case 9:
                return $this->getReputation();
                break;
            case 10:
                return $this->getEmail();
                break;
            case 11:
                return $this->getPhone();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Location'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Location'][$this->hashCode()] = true;
        $keys = LocationTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAdminId(),
            $keys[2] => $this->getSignupTs(),
            $keys[3] => $this->getVerified(),
            $keys[4] => $this->getName(),
            $keys[5] => $this->getDescription(),
            $keys[6] => $this->getCapacity(),
            $keys[7] => $this->getPictureUrl(),
            $keys[8] => $this->getTimings(),
            $keys[9] => $this->getReputation(),
            $keys[10] => $this->getEmail(),
            $keys[11] => $this->getPhone(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aAdmin) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user';
                        break;
                    default:
                        $key = 'Admin';
                }

                $result[$key] = $this->aAdmin->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->singleAddress) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'locationAddress';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'location_address';
                        break;
                    default:
                        $key = 'Address';
                }

                $result[$key] = $this->singleAddress->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->singleSearchString) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'searchLocation';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'search_location';
                        break;
                    default:
                        $key = 'SearchString';
                }

                $result[$key] = $this->singleSearchString->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->collSubscribedUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userLocationFavorites';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_location_favorites';
                        break;
                    default:
                        $key = 'SubscribedUsers';
                }

                $result[$key] = $this->collSubscribedUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userLocations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_locations';
                        break;
                    default:
                        $key = 'Users';
                }

                $result[$key] = $this->collUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collExpiredUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userLocationExpireds';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_location_expireds';
                        break;
                    default:
                        $key = 'ExpiredUsers';
                }

                $result[$key] = $this->collExpiredUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collImages) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'locationImages';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'location_images';
                        break;
                    default:
                        $key = 'Images';
                }

                $result[$key] = $this->collImages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Location
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = LocationTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Location
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setAdminId($value);
                break;
            case 2:
                $this->setSignupTs($value);
                break;
            case 3:
                $this->setVerified($value);
                break;
            case 4:
                $this->setName($value);
                break;
            case 5:
                $this->setDescription($value);
                break;
            case 6:
                $this->setCapacity($value);
                break;
            case 7:
                $this->setPictureUrl($value);
                break;
            case 8:
                $this->setTimings($value);
                break;
            case 9:
                $this->setReputation($value);
                break;
            case 10:
                $this->setEmail($value);
                break;
            case 11:
                $this->setPhone($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = LocationTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setAdminId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setSignupTs($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setVerified($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setName($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDescription($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCapacity($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setPictureUrl($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setTimings($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setReputation($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setEmail($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setPhone($arr[$keys[11]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Location The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(LocationTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LocationTableMap::COL_ID)) {
            $criteria->add(LocationTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(LocationTableMap::COL_ADMIN_ID)) {
            $criteria->add(LocationTableMap::COL_ADMIN_ID, $this->admin_id);
        }
        if ($this->isColumnModified(LocationTableMap::COL_SIGNUP_TS)) {
            $criteria->add(LocationTableMap::COL_SIGNUP_TS, $this->signup_ts);
        }
        if ($this->isColumnModified(LocationTableMap::COL_VERIFIED)) {
            $criteria->add(LocationTableMap::COL_VERIFIED, $this->verified);
        }
        if ($this->isColumnModified(LocationTableMap::COL_NAME)) {
            $criteria->add(LocationTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(LocationTableMap::COL_DESCRIPTION)) {
            $criteria->add(LocationTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(LocationTableMap::COL_CAPACITY)) {
            $criteria->add(LocationTableMap::COL_CAPACITY, $this->capacity);
        }
        if ($this->isColumnModified(LocationTableMap::COL_PICTURE_URL)) {
            $criteria->add(LocationTableMap::COL_PICTURE_URL, $this->picture_url);
        }
        if ($this->isColumnModified(LocationTableMap::COL_TIMINGS)) {
            $criteria->add(LocationTableMap::COL_TIMINGS, $this->timings);
        }
        if ($this->isColumnModified(LocationTableMap::COL_REPUTATION)) {
            $criteria->add(LocationTableMap::COL_REPUTATION, $this->reputation);
        }
        if ($this->isColumnModified(LocationTableMap::COL_EMAIL)) {
            $criteria->add(LocationTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(LocationTableMap::COL_PHONE)) {
            $criteria->add(LocationTableMap::COL_PHONE, $this->phone);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildLocationQuery::create();
        $criteria->add(LocationTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Location (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAdminId($this->getAdminId());
        $copyObj->setSignupTs($this->getSignupTs());
        $copyObj->setVerified($this->getVerified());
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCapacity($this->getCapacity());
        $copyObj->setPictureUrl($this->getPictureUrl());
        $copyObj->setTimings($this->getTimings());
        $copyObj->setReputation($this->getReputation());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setPhone($this->getPhone());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            $relObj = $this->getAddress();
            if ($relObj) {
                $copyObj->setAddress($relObj->copy($deepCopy));
            }

            $relObj = $this->getSearchString();
            if ($relObj) {
                $copyObj->setSearchString($relObj->copy($deepCopy));
            }

            foreach ($this->getSubscribedUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSubscribedUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getExpiredUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addExpiredUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getImages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addImage($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Location Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\Location The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAdmin(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setAdminId(NULL);
        } else {
            $this->setAdminId($v->getId());
        }

        $this->aAdmin = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addInsertedLocation($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getAdmin(ConnectionInterface $con = null)
    {
        if ($this->aAdmin === null && ($this->admin_id != 0)) {
            $this->aAdmin = ChildUserQuery::create()->findPk($this->admin_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAdmin->addInsertedLocations($this);
             */
        }

        return $this->aAdmin;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('SubscribedUser' == $relationName) {
            $this->initSubscribedUsers();
            return;
        }
        if ('User' == $relationName) {
            $this->initUsers();
            return;
        }
        if ('ExpiredUser' == $relationName) {
            $this->initExpiredUsers();
            return;
        }
        if ('Image' == $relationName) {
            $this->initImages();
            return;
        }
    }

    /**
     * Gets a single ChildLocationAddress object, which is related to this object by a one-to-one relationship.
     *
     * @param  ConnectionInterface $con optional connection object
     * @return ChildLocationAddress
     * @throws PropelException
     */
    public function getAddress(ConnectionInterface $con = null)
    {

        if ($this->singleAddress === null && !$this->isNew()) {
            $this->singleAddress = ChildLocationAddressQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleAddress;
    }

    /**
     * Sets a single ChildLocationAddress object as related to this object by a one-to-one relationship.
     *
     * @param  ChildLocationAddress $v ChildLocationAddress
     * @return $this|\Location The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAddress(ChildLocationAddress $v = null)
    {
        $this->singleAddress = $v;

        // Make sure that that the passed-in ChildLocationAddress isn't already associated with this object
        if ($v !== null && $v->getLocation(null, false) === null) {
            $v->setLocation($this);
        }

        return $this;
    }

    /**
     * Gets a single ChildSearchLocation object, which is related to this object by a one-to-one relationship.
     *
     * @param  ConnectionInterface $con optional connection object
     * @return ChildSearchLocation
     * @throws PropelException
     */
    public function getSearchString(ConnectionInterface $con = null)
    {

        if ($this->singleSearchString === null && !$this->isNew()) {
            $this->singleSearchString = ChildSearchLocationQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleSearchString;
    }

    /**
     * Sets a single ChildSearchLocation object as related to this object by a one-to-one relationship.
     *
     * @param  ChildSearchLocation $v ChildSearchLocation
     * @return $this|\Location The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSearchString(ChildSearchLocation $v = null)
    {
        $this->singleSearchString = $v;

        // Make sure that that the passed-in ChildSearchLocation isn't already associated with this object
        if ($v !== null && $v->getLocation(null, false) === null) {
            $v->setLocation($this);
        }

        return $this;
    }

    /**
     * Clears out the collSubscribedUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSubscribedUsers()
     */
    public function clearSubscribedUsers()
    {
        $this->collSubscribedUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSubscribedUsers collection loaded partially.
     */
    public function resetPartialSubscribedUsers($v = true)
    {
        $this->collSubscribedUsersPartial = $v;
    }

    /**
     * Initializes the collSubscribedUsers collection.
     *
     * By default this just sets the collSubscribedUsers collection to an empty array (like clearcollSubscribedUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSubscribedUsers($overrideExisting = true)
    {
        if (null !== $this->collSubscribedUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserLocationFavoriteTableMap::getTableMap()->getCollectionClassName();

        $this->collSubscribedUsers = new $collectionClassName;
        $this->collSubscribedUsers->setModel('\UserLocationFavorite');
    }

    /**
     * Gets an array of ChildUserLocationFavorite objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLocation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserLocationFavorite[] List of ChildUserLocationFavorite objects
     * @throws PropelException
     */
    public function getSubscribedUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSubscribedUsersPartial && !$this->isNew();
        if (null === $this->collSubscribedUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSubscribedUsers) {
                // return empty collection
                $this->initSubscribedUsers();
            } else {
                $collSubscribedUsers = ChildUserLocationFavoriteQuery::create(null, $criteria)
                    ->filterByLocation($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSubscribedUsersPartial && count($collSubscribedUsers)) {
                        $this->initSubscribedUsers(false);

                        foreach ($collSubscribedUsers as $obj) {
                            if (false == $this->collSubscribedUsers->contains($obj)) {
                                $this->collSubscribedUsers->append($obj);
                            }
                        }

                        $this->collSubscribedUsersPartial = true;
                    }

                    return $collSubscribedUsers;
                }

                if ($partial && $this->collSubscribedUsers) {
                    foreach ($this->collSubscribedUsers as $obj) {
                        if ($obj->isNew()) {
                            $collSubscribedUsers[] = $obj;
                        }
                    }
                }

                $this->collSubscribedUsers = $collSubscribedUsers;
                $this->collSubscribedUsersPartial = false;
            }
        }

        return $this->collSubscribedUsers;
    }

    /**
     * Sets a collection of ChildUserLocationFavorite objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $subscribedUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLocation The current object (for fluent API support)
     */
    public function setSubscribedUsers(Collection $subscribedUsers, ConnectionInterface $con = null)
    {
        /** @var ChildUserLocationFavorite[] $subscribedUsersToDelete */
        $subscribedUsersToDelete = $this->getSubscribedUsers(new Criteria(), $con)->diff($subscribedUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->subscribedUsersScheduledForDeletion = clone $subscribedUsersToDelete;

        foreach ($subscribedUsersToDelete as $subscribedUserRemoved) {
            $subscribedUserRemoved->setLocation(null);
        }

        $this->collSubscribedUsers = null;
        foreach ($subscribedUsers as $subscribedUser) {
            $this->addSubscribedUser($subscribedUser);
        }

        $this->collSubscribedUsers = $subscribedUsers;
        $this->collSubscribedUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserLocationFavorite objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserLocationFavorite objects.
     * @throws PropelException
     */
    public function countSubscribedUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSubscribedUsersPartial && !$this->isNew();
        if (null === $this->collSubscribedUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSubscribedUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSubscribedUsers());
            }

            $query = ChildUserLocationFavoriteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLocation($this)
                ->count($con);
        }

        return count($this->collSubscribedUsers);
    }

    /**
     * Method called to associate a ChildUserLocationFavorite object to this object
     * through the ChildUserLocationFavorite foreign key attribute.
     *
     * @param  ChildUserLocationFavorite $l ChildUserLocationFavorite
     * @return $this|\Location The current object (for fluent API support)
     */
    public function addSubscribedUser(ChildUserLocationFavorite $l)
    {
        if ($this->collSubscribedUsers === null) {
            $this->initSubscribedUsers();
            $this->collSubscribedUsersPartial = true;
        }

        if (!$this->collSubscribedUsers->contains($l)) {
            $this->doAddSubscribedUser($l);

            if ($this->subscribedUsersScheduledForDeletion and $this->subscribedUsersScheduledForDeletion->contains($l)) {
                $this->subscribedUsersScheduledForDeletion->remove($this->subscribedUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserLocationFavorite $subscribedUser The ChildUserLocationFavorite object to add.
     */
    protected function doAddSubscribedUser(ChildUserLocationFavorite $subscribedUser)
    {
        $this->collSubscribedUsers[]= $subscribedUser;
        $subscribedUser->setLocation($this);
    }

    /**
     * @param  ChildUserLocationFavorite $subscribedUser The ChildUserLocationFavorite object to remove.
     * @return $this|ChildLocation The current object (for fluent API support)
     */
    public function removeSubscribedUser(ChildUserLocationFavorite $subscribedUser)
    {
        if ($this->getSubscribedUsers()->contains($subscribedUser)) {
            $pos = $this->collSubscribedUsers->search($subscribedUser);
            $this->collSubscribedUsers->remove($pos);
            if (null === $this->subscribedUsersScheduledForDeletion) {
                $this->subscribedUsersScheduledForDeletion = clone $this->collSubscribedUsers;
                $this->subscribedUsersScheduledForDeletion->clear();
            }
            $this->subscribedUsersScheduledForDeletion[]= clone $subscribedUser;
            $subscribedUser->setLocation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Location is new, it will return
     * an empty collection; or if this Location has previously
     * been saved, it will retrieve related SubscribedUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Location.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserLocationFavorite[] List of ChildUserLocationFavorite objects
     */
    public function getSubscribedUsersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserLocationFavoriteQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getSubscribedUsers($query, $con);
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUsers collection loaded partially.
     */
    public function resetPartialUsers($v = true)
    {
        $this->collUsersPartial = $v;
    }

    /**
     * Initializes the collUsers collection.
     *
     * By default this just sets the collUsers collection to an empty array (like clearcollUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsers($overrideExisting = true)
    {
        if (null !== $this->collUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserLocationTableMap::getTableMap()->getCollectionClassName();

        $this->collUsers = new $collectionClassName;
        $this->collUsers->setModel('\UserLocation');
    }

    /**
     * Gets an array of ChildUserLocation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLocation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserLocation[] List of ChildUserLocation objects
     * @throws PropelException
     */
    public function getUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                // return empty collection
                $this->initUsers();
            } else {
                $collUsers = ChildUserLocationQuery::create(null, $criteria)
                    ->filterByLocation($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUsersPartial && count($collUsers)) {
                        $this->initUsers(false);

                        foreach ($collUsers as $obj) {
                            if (false == $this->collUsers->contains($obj)) {
                                $this->collUsers->append($obj);
                            }
                        }

                        $this->collUsersPartial = true;
                    }

                    return $collUsers;
                }

                if ($partial && $this->collUsers) {
                    foreach ($this->collUsers as $obj) {
                        if ($obj->isNew()) {
                            $collUsers[] = $obj;
                        }
                    }
                }

                $this->collUsers = $collUsers;
                $this->collUsersPartial = false;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of ChildUserLocation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $users A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLocation The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        /** @var ChildUserLocation[] $usersToDelete */
        $usersToDelete = $this->getUsers(new Criteria(), $con)->diff($users);


        $this->usersScheduledForDeletion = $usersToDelete;

        foreach ($usersToDelete as $userRemoved) {
            $userRemoved->setLocation(null);
        }

        $this->collUsers = null;
        foreach ($users as $user) {
            $this->addUser($user);
        }

        $this->collUsers = $users;
        $this->collUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserLocation objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserLocation objects.
     * @throws PropelException
     */
    public function countUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsers());
            }

            $query = ChildUserLocationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLocation($this)
                ->count($con);
        }

        return count($this->collUsers);
    }

    /**
     * Method called to associate a ChildUserLocation object to this object
     * through the ChildUserLocation foreign key attribute.
     *
     * @param  ChildUserLocation $l ChildUserLocation
     * @return $this|\Location The current object (for fluent API support)
     */
    public function addUser(ChildUserLocation $l)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
            $this->collUsersPartial = true;
        }

        if (!$this->collUsers->contains($l)) {
            $this->doAddUser($l);

            if ($this->usersScheduledForDeletion and $this->usersScheduledForDeletion->contains($l)) {
                $this->usersScheduledForDeletion->remove($this->usersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserLocation $user The ChildUserLocation object to add.
     */
    protected function doAddUser(ChildUserLocation $user)
    {
        $this->collUsers[]= $user;
        $user->setLocation($this);
    }

    /**
     * @param  ChildUserLocation $user The ChildUserLocation object to remove.
     * @return $this|ChildLocation The current object (for fluent API support)
     */
    public function removeUser(ChildUserLocation $user)
    {
        if ($this->getUsers()->contains($user)) {
            $pos = $this->collUsers->search($user);
            $this->collUsers->remove($pos);
            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }
            $this->usersScheduledForDeletion[]= clone $user;
            $user->setLocation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Location is new, it will return
     * an empty collection; or if this Location has previously
     * been saved, it will retrieve related Users from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Location.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserLocation[] List of ChildUserLocation objects
     */
    public function getUsersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserLocationQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUsers($query, $con);
    }

    /**
     * Clears out the collExpiredUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addExpiredUsers()
     */
    public function clearExpiredUsers()
    {
        $this->collExpiredUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collExpiredUsers collection loaded partially.
     */
    public function resetPartialExpiredUsers($v = true)
    {
        $this->collExpiredUsersPartial = $v;
    }

    /**
     * Initializes the collExpiredUsers collection.
     *
     * By default this just sets the collExpiredUsers collection to an empty array (like clearcollExpiredUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initExpiredUsers($overrideExisting = true)
    {
        if (null !== $this->collExpiredUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserLocationExpiredTableMap::getTableMap()->getCollectionClassName();

        $this->collExpiredUsers = new $collectionClassName;
        $this->collExpiredUsers->setModel('\UserLocationExpired');
    }

    /**
     * Gets an array of ChildUserLocationExpired objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLocation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserLocationExpired[] List of ChildUserLocationExpired objects
     * @throws PropelException
     */
    public function getExpiredUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collExpiredUsersPartial && !$this->isNew();
        if (null === $this->collExpiredUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collExpiredUsers) {
                // return empty collection
                $this->initExpiredUsers();
            } else {
                $collExpiredUsers = ChildUserLocationExpiredQuery::create(null, $criteria)
                    ->filterByLocation($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collExpiredUsersPartial && count($collExpiredUsers)) {
                        $this->initExpiredUsers(false);

                        foreach ($collExpiredUsers as $obj) {
                            if (false == $this->collExpiredUsers->contains($obj)) {
                                $this->collExpiredUsers->append($obj);
                            }
                        }

                        $this->collExpiredUsersPartial = true;
                    }

                    return $collExpiredUsers;
                }

                if ($partial && $this->collExpiredUsers) {
                    foreach ($this->collExpiredUsers as $obj) {
                        if ($obj->isNew()) {
                            $collExpiredUsers[] = $obj;
                        }
                    }
                }

                $this->collExpiredUsers = $collExpiredUsers;
                $this->collExpiredUsersPartial = false;
            }
        }

        return $this->collExpiredUsers;
    }

    /**
     * Sets a collection of ChildUserLocationExpired objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $expiredUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLocation The current object (for fluent API support)
     */
    public function setExpiredUsers(Collection $expiredUsers, ConnectionInterface $con = null)
    {
        /** @var ChildUserLocationExpired[] $expiredUsersToDelete */
        $expiredUsersToDelete = $this->getExpiredUsers(new Criteria(), $con)->diff($expiredUsers);


        $this->expiredUsersScheduledForDeletion = $expiredUsersToDelete;

        foreach ($expiredUsersToDelete as $expiredUserRemoved) {
            $expiredUserRemoved->setLocation(null);
        }

        $this->collExpiredUsers = null;
        foreach ($expiredUsers as $expiredUser) {
            $this->addExpiredUser($expiredUser);
        }

        $this->collExpiredUsers = $expiredUsers;
        $this->collExpiredUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserLocationExpired objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserLocationExpired objects.
     * @throws PropelException
     */
    public function countExpiredUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collExpiredUsersPartial && !$this->isNew();
        if (null === $this->collExpiredUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collExpiredUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getExpiredUsers());
            }

            $query = ChildUserLocationExpiredQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLocation($this)
                ->count($con);
        }

        return count($this->collExpiredUsers);
    }

    /**
     * Method called to associate a ChildUserLocationExpired object to this object
     * through the ChildUserLocationExpired foreign key attribute.
     *
     * @param  ChildUserLocationExpired $l ChildUserLocationExpired
     * @return $this|\Location The current object (for fluent API support)
     */
    public function addExpiredUser(ChildUserLocationExpired $l)
    {
        if ($this->collExpiredUsers === null) {
            $this->initExpiredUsers();
            $this->collExpiredUsersPartial = true;
        }

        if (!$this->collExpiredUsers->contains($l)) {
            $this->doAddExpiredUser($l);

            if ($this->expiredUsersScheduledForDeletion and $this->expiredUsersScheduledForDeletion->contains($l)) {
                $this->expiredUsersScheduledForDeletion->remove($this->expiredUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserLocationExpired $expiredUser The ChildUserLocationExpired object to add.
     */
    protected function doAddExpiredUser(ChildUserLocationExpired $expiredUser)
    {
        $this->collExpiredUsers[]= $expiredUser;
        $expiredUser->setLocation($this);
    }

    /**
     * @param  ChildUserLocationExpired $expiredUser The ChildUserLocationExpired object to remove.
     * @return $this|ChildLocation The current object (for fluent API support)
     */
    public function removeExpiredUser(ChildUserLocationExpired $expiredUser)
    {
        if ($this->getExpiredUsers()->contains($expiredUser)) {
            $pos = $this->collExpiredUsers->search($expiredUser);
            $this->collExpiredUsers->remove($pos);
            if (null === $this->expiredUsersScheduledForDeletion) {
                $this->expiredUsersScheduledForDeletion = clone $this->collExpiredUsers;
                $this->expiredUsersScheduledForDeletion->clear();
            }
            $this->expiredUsersScheduledForDeletion[]= clone $expiredUser;
            $expiredUser->setLocation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Location is new, it will return
     * an empty collection; or if this Location has previously
     * been saved, it will retrieve related ExpiredUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Location.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserLocationExpired[] List of ChildUserLocationExpired objects
     */
    public function getExpiredUsersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserLocationExpiredQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getExpiredUsers($query, $con);
    }

    /**
     * Clears out the collImages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addImages()
     */
    public function clearImages()
    {
        $this->collImages = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collImages collection loaded partially.
     */
    public function resetPartialImages($v = true)
    {
        $this->collImagesPartial = $v;
    }

    /**
     * Initializes the collImages collection.
     *
     * By default this just sets the collImages collection to an empty array (like clearcollImages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initImages($overrideExisting = true)
    {
        if (null !== $this->collImages && !$overrideExisting) {
            return;
        }

        $collectionClassName = LocationImageTableMap::getTableMap()->getCollectionClassName();

        $this->collImages = new $collectionClassName;
        $this->collImages->setModel('\LocationImage');
    }

    /**
     * Gets an array of ChildLocationImage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLocation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLocationImage[] List of ChildLocationImage objects
     * @throws PropelException
     */
    public function getImages(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collImagesPartial && !$this->isNew();
        if (null === $this->collImages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collImages) {
                // return empty collection
                $this->initImages();
            } else {
                $collImages = ChildLocationImageQuery::create(null, $criteria)
                    ->filterByLocation($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collImagesPartial && count($collImages)) {
                        $this->initImages(false);

                        foreach ($collImages as $obj) {
                            if (false == $this->collImages->contains($obj)) {
                                $this->collImages->append($obj);
                            }
                        }

                        $this->collImagesPartial = true;
                    }

                    return $collImages;
                }

                if ($partial && $this->collImages) {
                    foreach ($this->collImages as $obj) {
                        if ($obj->isNew()) {
                            $collImages[] = $obj;
                        }
                    }
                }

                $this->collImages = $collImages;
                $this->collImagesPartial = false;
            }
        }

        return $this->collImages;
    }

    /**
     * Sets a collection of ChildLocationImage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $images A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLocation The current object (for fluent API support)
     */
    public function setImages(Collection $images, ConnectionInterface $con = null)
    {
        /** @var ChildLocationImage[] $imagesToDelete */
        $imagesToDelete = $this->getImages(new Criteria(), $con)->diff($images);


        $this->imagesScheduledForDeletion = $imagesToDelete;

        foreach ($imagesToDelete as $imageRemoved) {
            $imageRemoved->setLocation(null);
        }

        $this->collImages = null;
        foreach ($images as $image) {
            $this->addImage($image);
        }

        $this->collImages = $images;
        $this->collImagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related LocationImage objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related LocationImage objects.
     * @throws PropelException
     */
    public function countImages(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collImagesPartial && !$this->isNew();
        if (null === $this->collImages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collImages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getImages());
            }

            $query = ChildLocationImageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLocation($this)
                ->count($con);
        }

        return count($this->collImages);
    }

    /**
     * Method called to associate a ChildLocationImage object to this object
     * through the ChildLocationImage foreign key attribute.
     *
     * @param  ChildLocationImage $l ChildLocationImage
     * @return $this|\Location The current object (for fluent API support)
     */
    public function addImage(ChildLocationImage $l)
    {
        if ($this->collImages === null) {
            $this->initImages();
            $this->collImagesPartial = true;
        }

        if (!$this->collImages->contains($l)) {
            $this->doAddImage($l);

            if ($this->imagesScheduledForDeletion and $this->imagesScheduledForDeletion->contains($l)) {
                $this->imagesScheduledForDeletion->remove($this->imagesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLocationImage $image The ChildLocationImage object to add.
     */
    protected function doAddImage(ChildLocationImage $image)
    {
        $this->collImages[]= $image;
        $image->setLocation($this);
    }

    /**
     * @param  ChildLocationImage $image The ChildLocationImage object to remove.
     * @return $this|ChildLocation The current object (for fluent API support)
     */
    public function removeImage(ChildLocationImage $image)
    {
        if ($this->getImages()->contains($image)) {
            $pos = $this->collImages->search($image);
            $this->collImages->remove($pos);
            if (null === $this->imagesScheduledForDeletion) {
                $this->imagesScheduledForDeletion = clone $this->collImages;
                $this->imagesScheduledForDeletion->clear();
            }
            $this->imagesScheduledForDeletion[]= clone $image;
            $image->setLocation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Location is new, it will return
     * an empty collection; or if this Location has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Location.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocationImage[] List of ChildLocationImage objects
     */
    public function getImagesJoinInserter(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocationImageQuery::create(null, $criteria);
        $query->joinWith('Inserter', $joinBehavior);

        return $this->getImages($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aAdmin) {
            $this->aAdmin->removeInsertedLocation($this);
        }
        $this->id = null;
        $this->admin_id = null;
        $this->signup_ts = null;
        $this->verified = null;
        $this->name = null;
        $this->description = null;
        $this->capacity = null;
        $this->picture_url = null;
        $this->timings = null;
        $this->reputation = null;
        $this->email = null;
        $this->phone = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->singleAddress) {
                $this->singleAddress->clearAllReferences($deep);
            }
            if ($this->singleSearchString) {
                $this->singleSearchString->clearAllReferences($deep);
            }
            if ($this->collSubscribedUsers) {
                foreach ($this->collSubscribedUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collExpiredUsers) {
                foreach ($this->collExpiredUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collImages) {
                foreach ($this->collImages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->singleAddress = null;
        $this->singleSearchString = null;
        $this->collSubscribedUsers = null;
        $this->collUsers = null;
        $this->collExpiredUsers = null;
        $this->collImages = null;
        $this->aAdmin = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LocationTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
