<?php

namespace Base;

use \Location as ChildLocation;
use \LocationImage as ChildLocationImage;
use \LocationImageQuery as ChildLocationImageQuery;
use \LocationQuery as ChildLocationQuery;
use \SearchUser as ChildSearchUser;
use \SearchUserQuery as ChildSearchUserQuery;
use \User as ChildUser;
use \UserConnection as ChildUserConnection;
use \UserConnectionQuery as ChildUserConnectionQuery;
use \UserLocation as ChildUserLocation;
use \UserLocationExpired as ChildUserLocationExpired;
use \UserLocationExpiredQuery as ChildUserLocationExpiredQuery;
use \UserLocationFavorite as ChildUserLocationFavorite;
use \UserLocationFavoriteQuery as ChildUserLocationFavoriteQuery;
use \UserLocationQuery as ChildUserLocationQuery;
use \UserQuery as ChildUserQuery;
use \UserSocial as ChildUserSocial;
use \UserSocialQuery as ChildUserSocialQuery;
use \Exception;
use \PDO;
use Map\LocationImageTableMap;
use Map\LocationTableMap;
use Map\UserConnectionTableMap;
use Map\UserLocationExpiredTableMap;
use Map\UserLocationFavoriteTableMap;
use Map\UserLocationTableMap;
use Map\UserTableMap;
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
 * Base class that represents a row from the 'user' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\UserTableMap';


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
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the api_key field.
     *
     * @var        string
     */
    protected $api_key;

    /**
     * The value for the pass_sha256 field.
     *
     * @var        string
     */
    protected $pass_sha256;

    /**
     * The value for the pass_salt field.
     *
     * @var        string
     */
    protected $pass_salt;

    /**
     * The value for the ban field.
     *
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $ban;

    /**
     * The value for the signup_ts field.
     *
     * Note: this column has a database default value of: 1483228800
     * @var        int
     */
    protected $signup_ts;

    /**
     * The value for the gender field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $gender;

    /**
     * The value for the reputation field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $reputation;

    /**
     * The value for the setting_privacy field.
     *
     * Note: this column has a database default value of: '333'
     * @var        string
     */
    protected $setting_privacy;

    /**
     * The value for the setting_notifications field.
     *
     * Note: this column has a database default value of: '333'
     * @var        string
     */
    protected $setting_notifications;

    /**
     * The value for the phone field.
     *
     * @var        string
     */
    protected $phone;

    /**
     * The value for the public_message field.
     *
     * @var        string
     */
    protected $public_message;

    /**
     * The value for the picture_url field.
     *
     * @var        string
     */
    protected $picture_url;

    /**
     * @var        ChildUserSocial one-to-one related ChildUserSocial object
     */
    protected $singleSocial;

    /**
     * @var        ObjectCollection|ChildLocation[] Collection to store aggregation of ChildLocation objects.
     */
    protected $collInsertedLocations;
    protected $collInsertedLocationsPartial;

    /**
     * @var        ChildSearchUser one-to-one related ChildSearchUser object
     */
    protected $singleSearchString;

    /**
     * @var        ObjectCollection|ChildUserLocationFavorite[] Collection to store aggregation of ChildUserLocationFavorite objects.
     */
    protected $collFavoriteLocations;
    protected $collFavoriteLocationsPartial;

    /**
     * @var        ObjectCollection|ChildUserConnection[] Collection to store aggregation of ChildUserConnection objects.
     */
    protected $collAddedConnections;
    protected $collAddedConnectionsPartial;

    /**
     * @var        ObjectCollection|ChildUserConnection[] Collection to store aggregation of ChildUserConnection objects.
     */
    protected $collRequestedConnections;
    protected $collRequestedConnectionsPartial;

    /**
     * @var        ObjectCollection|ChildUserLocation[] Collection to store aggregation of ChildUserLocation objects.
     */
    protected $collLocations;
    protected $collLocationsPartial;

    /**
     * @var        ObjectCollection|ChildUserLocationExpired[] Collection to store aggregation of ChildUserLocationExpired objects.
     */
    protected $collExpiredLocations;
    protected $collExpiredLocationsPartial;

    /**
     * @var        ObjectCollection|ChildLocationImage[] Collection to store aggregation of ChildLocationImage objects.
     */
    protected $collInsertedImages;
    protected $collInsertedImagesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLocation[]
     */
    protected $insertedLocationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserLocationFavorite[]
     */
    protected $favoriteLocationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserConnection[]
     */
    protected $addedConnectionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserConnection[]
     */
    protected $requestedConnectionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserLocation[]
     */
    protected $locationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserLocationExpired[]
     */
    protected $expiredLocationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLocationImage[]
     */
    protected $insertedImagesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->ban = true;
        $this->signup_ts = 1483228800;
        $this->gender = 0;
        $this->reputation = 0;
        $this->setting_privacy = '333';
        $this->setting_notifications = '333';
    }

    /**
     * Initializes internal state of Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Get the [api_key] column value.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Get the [pass_sha256] column value.
     *
     * @return string
     */
    public function getPassSha256()
    {
        return $this->pass_sha256;
    }

    /**
     * Get the [pass_salt] column value.
     *
     * @return string
     */
    public function getPassSalt()
    {
        return $this->pass_salt;
    }

    /**
     * Get the [ban] column value.
     *
     * @return boolean
     */
    public function getBan()
    {
        return $this->ban;
    }

    /**
     * Get the [ban] column value.
     *
     * @return boolean
     */
    public function isBan()
    {
        return $this->getBan();
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
     * Get the [gender] column value.
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
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
     * Get the [setting_privacy] column value.
     *
     * @return string
     */
    public function getSettingPrivacy()
    {
        return $this->setting_privacy;
    }

    /**
     * Get the [setting_notifications] column value.
     *
     * @return string
     */
    public function getSettingNotifications()
    {
        return $this->setting_notifications;
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
     * Get the [public_message] column value.
     *
     * @return string
     */
    public function getPublicMessage()
    {
        return $this->public_message;
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
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[UserTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [api_key] column.
     *
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setApiKey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->api_key !== $v) {
            $this->api_key = $v;
            $this->modifiedColumns[UserTableMap::COL_API_KEY] = true;
        }

        return $this;
    } // setApiKey()

    /**
     * Set the value of [pass_sha256] column.
     *
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPassSha256($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->pass_sha256 !== $v) {
            $this->pass_sha256 = $v;
            $this->modifiedColumns[UserTableMap::COL_PASS_SHA256] = true;
        }

        return $this;
    } // setPassSha256()

    /**
     * Set the value of [pass_salt] column.
     *
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPassSalt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->pass_salt !== $v) {
            $this->pass_salt = $v;
            $this->modifiedColumns[UserTableMap::COL_PASS_SALT] = true;
        }

        return $this;
    } // setPassSalt()

    /**
     * Sets the value of the [ban] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setBan($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->ban !== $v) {
            $this->ban = $v;
            $this->modifiedColumns[UserTableMap::COL_BAN] = true;
        }

        return $this;
    } // setBan()

    /**
     * Set the value of [signup_ts] column.
     *
     * @param int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setSignupTs($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->signup_ts !== $v) {
            $this->signup_ts = $v;
            $this->modifiedColumns[UserTableMap::COL_SIGNUP_TS] = true;
        }

        return $this;
    } // setSignupTs()

    /**
     * Set the value of [gender] column.
     *
     * @param int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setGender($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->gender !== $v) {
            $this->gender = $v;
            $this->modifiedColumns[UserTableMap::COL_GENDER] = true;
        }

        return $this;
    } // setGender()

    /**
     * Set the value of [reputation] column.
     *
     * @param int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setReputation($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reputation !== $v) {
            $this->reputation = $v;
            $this->modifiedColumns[UserTableMap::COL_REPUTATION] = true;
        }

        return $this;
    } // setReputation()

    /**
     * Set the value of [setting_privacy] column.
     *
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setSettingPrivacy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->setting_privacy !== $v) {
            $this->setting_privacy = $v;
            $this->modifiedColumns[UserTableMap::COL_SETTING_PRIVACY] = true;
        }

        return $this;
    } // setSettingPrivacy()

    /**
     * Set the value of [setting_notifications] column.
     *
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setSettingNotifications($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->setting_notifications !== $v) {
            $this->setting_notifications = $v;
            $this->modifiedColumns[UserTableMap::COL_SETTING_NOTIFICATIONS] = true;
        }

        return $this;
    } // setSettingNotifications()

    /**
     * Set the value of [phone] column.
     *
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[UserTableMap::COL_PHONE] = true;
        }

        return $this;
    } // setPhone()

    /**
     * Set the value of [public_message] column.
     *
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPublicMessage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->public_message !== $v) {
            $this->public_message = $v;
            $this->modifiedColumns[UserTableMap::COL_PUBLIC_MESSAGE] = true;
        }

        return $this;
    } // setPublicMessage()

    /**
     * Set the value of [picture_url] column.
     *
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPictureUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->picture_url !== $v) {
            $this->picture_url = $v;
            $this->modifiedColumns[UserTableMap::COL_PICTURE_URL] = true;
        }

        return $this;
    } // setPictureUrl()

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
            if ($this->ban !== true) {
                return false;
            }

            if ($this->signup_ts !== 1483228800) {
                return false;
            }

            if ($this->gender !== 0) {
                return false;
            }

            if ($this->reputation !== 0) {
                return false;
            }

            if ($this->setting_privacy !== '333') {
                return false;
            }

            if ($this->setting_notifications !== '333') {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('ApiKey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->api_key = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('PassSha256', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pass_sha256 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('PassSalt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pass_salt = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('Ban', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ban = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('SignupTs', TableMap::TYPE_PHPNAME, $indexType)];
            $this->signup_ts = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('Gender', TableMap::TYPE_PHPNAME, $indexType)];
            $this->gender = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('Reputation', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reputation = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('SettingPrivacy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->setting_privacy = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('SettingNotifications', TableMap::TYPE_PHPNAME, $indexType)];
            $this->setting_notifications = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : UserTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : UserTableMap::translateFieldName('PublicMessage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->public_message = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : UserTableMap::translateFieldName('PictureUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->picture_url = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 15; // 15 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\User'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->singleSocial = null;

            $this->collInsertedLocations = null;

            $this->singleSearchString = null;

            $this->collFavoriteLocations = null;

            $this->collAddedConnections = null;

            $this->collRequestedConnections = null;

            $this->collLocations = null;

            $this->collExpiredLocations = null;

            $this->collInsertedImages = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
                UserTableMap::addInstanceToPool($this);
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

            if ($this->singleSocial !== null) {
                if (!$this->singleSocial->isDeleted() && ($this->singleSocial->isNew() || $this->singleSocial->isModified())) {
                    $affectedRows += $this->singleSocial->save($con);
                }
            }

            if ($this->insertedLocationsScheduledForDeletion !== null) {
                if (!$this->insertedLocationsScheduledForDeletion->isEmpty()) {
                    \LocationQuery::create()
                        ->filterByPrimaryKeys($this->insertedLocationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->insertedLocationsScheduledForDeletion = null;
                }
            }

            if ($this->collInsertedLocations !== null) {
                foreach ($this->collInsertedLocations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->singleSearchString !== null) {
                if (!$this->singleSearchString->isDeleted() && ($this->singleSearchString->isNew() || $this->singleSearchString->isModified())) {
                    $affectedRows += $this->singleSearchString->save($con);
                }
            }

            if ($this->favoriteLocationsScheduledForDeletion !== null) {
                if (!$this->favoriteLocationsScheduledForDeletion->isEmpty()) {
                    \UserLocationFavoriteQuery::create()
                        ->filterByPrimaryKeys($this->favoriteLocationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->favoriteLocationsScheduledForDeletion = null;
                }
            }

            if ($this->collFavoriteLocations !== null) {
                foreach ($this->collFavoriteLocations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->addedConnectionsScheduledForDeletion !== null) {
                if (!$this->addedConnectionsScheduledForDeletion->isEmpty()) {
                    \UserConnectionQuery::create()
                        ->filterByPrimaryKeys($this->addedConnectionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->addedConnectionsScheduledForDeletion = null;
                }
            }

            if ($this->collAddedConnections !== null) {
                foreach ($this->collAddedConnections as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->requestedConnectionsScheduledForDeletion !== null) {
                if (!$this->requestedConnectionsScheduledForDeletion->isEmpty()) {
                    \UserConnectionQuery::create()
                        ->filterByPrimaryKeys($this->requestedConnectionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->requestedConnectionsScheduledForDeletion = null;
                }
            }

            if ($this->collRequestedConnections !== null) {
                foreach ($this->collRequestedConnections as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->locationsScheduledForDeletion !== null) {
                if (!$this->locationsScheduledForDeletion->isEmpty()) {
                    \UserLocationQuery::create()
                        ->filterByPrimaryKeys($this->locationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->locationsScheduledForDeletion = null;
                }
            }

            if ($this->collLocations !== null) {
                foreach ($this->collLocations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->expiredLocationsScheduledForDeletion !== null) {
                if (!$this->expiredLocationsScheduledForDeletion->isEmpty()) {
                    \UserLocationExpiredQuery::create()
                        ->filterByPrimaryKeys($this->expiredLocationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->expiredLocationsScheduledForDeletion = null;
                }
            }

            if ($this->collExpiredLocations !== null) {
                foreach ($this->collExpiredLocations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->insertedImagesScheduledForDeletion !== null) {
                if (!$this->insertedImagesScheduledForDeletion->isEmpty()) {
                    \LocationImageQuery::create()
                        ->filterByPrimaryKeys($this->insertedImagesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->insertedImagesScheduledForDeletion = null;
                }
            }

            if ($this->collInsertedImages !== null) {
                foreach ($this->collInsertedImages as $referrerFK) {
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UserTableMap::COL_API_KEY)) {
            $modifiedColumns[':p' . $index++]  = 'api_key';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASS_SHA256)) {
            $modifiedColumns[':p' . $index++]  = 'pass_sha256';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASS_SALT)) {
            $modifiedColumns[':p' . $index++]  = 'pass_salt';
        }
        if ($this->isColumnModified(UserTableMap::COL_BAN)) {
            $modifiedColumns[':p' . $index++]  = 'ban';
        }
        if ($this->isColumnModified(UserTableMap::COL_SIGNUP_TS)) {
            $modifiedColumns[':p' . $index++]  = 'signup_ts';
        }
        if ($this->isColumnModified(UserTableMap::COL_GENDER)) {
            $modifiedColumns[':p' . $index++]  = 'gender';
        }
        if ($this->isColumnModified(UserTableMap::COL_REPUTATION)) {
            $modifiedColumns[':p' . $index++]  = 'reputation';
        }
        if ($this->isColumnModified(UserTableMap::COL_SETTING_PRIVACY)) {
            $modifiedColumns[':p' . $index++]  = 'setting_privacy';
        }
        if ($this->isColumnModified(UserTableMap::COL_SETTING_NOTIFICATIONS)) {
            $modifiedColumns[':p' . $index++]  = 'setting_notifications';
        }
        if ($this->isColumnModified(UserTableMap::COL_PHONE)) {
            $modifiedColumns[':p' . $index++]  = 'phone';
        }
        if ($this->isColumnModified(UserTableMap::COL_PUBLIC_MESSAGE)) {
            $modifiedColumns[':p' . $index++]  = 'public_message';
        }
        if ($this->isColumnModified(UserTableMap::COL_PICTURE_URL)) {
            $modifiedColumns[':p' . $index++]  = 'picture_url';
        }

        $sql = sprintf(
            'INSERT INTO user (%s) VALUES (%s)',
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
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'api_key':
                        $stmt->bindValue($identifier, $this->api_key, PDO::PARAM_STR);
                        break;
                    case 'pass_sha256':
                        $stmt->bindValue($identifier, $this->pass_sha256, PDO::PARAM_STR);
                        break;
                    case 'pass_salt':
                        $stmt->bindValue($identifier, $this->pass_salt, PDO::PARAM_STR);
                        break;
                    case 'ban':
                        $stmt->bindValue($identifier, (int) $this->ban, PDO::PARAM_INT);
                        break;
                    case 'signup_ts':
                        $stmt->bindValue($identifier, $this->signup_ts, PDO::PARAM_INT);
                        break;
                    case 'gender':
                        $stmt->bindValue($identifier, $this->gender, PDO::PARAM_INT);
                        break;
                    case 'reputation':
                        $stmt->bindValue($identifier, $this->reputation, PDO::PARAM_INT);
                        break;
                    case 'setting_privacy':
                        $stmt->bindValue($identifier, $this->setting_privacy, PDO::PARAM_STR);
                        break;
                    case 'setting_notifications':
                        $stmt->bindValue($identifier, $this->setting_notifications, PDO::PARAM_STR);
                        break;
                    case 'phone':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
                        break;
                    case 'public_message':
                        $stmt->bindValue($identifier, $this->public_message, PDO::PARAM_STR);
                        break;
                    case 'picture_url':
                        $stmt->bindValue($identifier, $this->picture_url, PDO::PARAM_STR);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getEmail();
                break;
            case 3:
                return $this->getApiKey();
                break;
            case 4:
                return $this->getPassSha256();
                break;
            case 5:
                return $this->getPassSalt();
                break;
            case 6:
                return $this->getBan();
                break;
            case 7:
                return $this->getSignupTs();
                break;
            case 8:
                return $this->getGender();
                break;
            case 9:
                return $this->getReputation();
                break;
            case 10:
                return $this->getSettingPrivacy();
                break;
            case 11:
                return $this->getSettingNotifications();
                break;
            case 12:
                return $this->getPhone();
                break;
            case 13:
                return $this->getPublicMessage();
                break;
            case 14:
                return $this->getPictureUrl();
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getEmail(),
            $keys[3] => $this->getApiKey(),
            $keys[4] => $this->getPassSha256(),
            $keys[5] => $this->getPassSalt(),
            $keys[6] => $this->getBan(),
            $keys[7] => $this->getSignupTs(),
            $keys[8] => $this->getGender(),
            $keys[9] => $this->getReputation(),
            $keys[10] => $this->getSettingPrivacy(),
            $keys[11] => $this->getSettingNotifications(),
            $keys[12] => $this->getPhone(),
            $keys[13] => $this->getPublicMessage(),
            $keys[14] => $this->getPictureUrl(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->singleSocial) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userSocial';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_social';
                        break;
                    default:
                        $key = 'Social';
                }

                $result[$key] = $this->singleSocial->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->collInsertedLocations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'locations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'locations';
                        break;
                    default:
                        $key = 'InsertedLocations';
                }

                $result[$key] = $this->collInsertedLocations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->singleSearchString) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'searchUser';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'search_user';
                        break;
                    default:
                        $key = 'SearchString';
                }

                $result[$key] = $this->singleSearchString->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->collFavoriteLocations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userLocationFavorites';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_location_favorites';
                        break;
                    default:
                        $key = 'FavoriteLocations';
                }

                $result[$key] = $this->collFavoriteLocations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAddedConnections) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userConnections';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_connections';
                        break;
                    default:
                        $key = 'AddedConnections';
                }

                $result[$key] = $this->collAddedConnections->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRequestedConnections) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userConnections';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_connections';
                        break;
                    default:
                        $key = 'RequestedConnections';
                }

                $result[$key] = $this->collRequestedConnections->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLocations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userLocations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_locations';
                        break;
                    default:
                        $key = 'Locations';
                }

                $result[$key] = $this->collLocations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collExpiredLocations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userLocationExpireds';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_location_expireds';
                        break;
                    default:
                        $key = 'ExpiredLocations';
                }

                $result[$key] = $this->collExpiredLocations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInsertedImages) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'locationImages';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'location_images';
                        break;
                    default:
                        $key = 'InsertedImages';
                }

                $result[$key] = $this->collInsertedImages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setEmail($value);
                break;
            case 3:
                $this->setApiKey($value);
                break;
            case 4:
                $this->setPassSha256($value);
                break;
            case 5:
                $this->setPassSalt($value);
                break;
            case 6:
                $this->setBan($value);
                break;
            case 7:
                $this->setSignupTs($value);
                break;
            case 8:
                $this->setGender($value);
                break;
            case 9:
                $this->setReputation($value);
                break;
            case 10:
                $this->setSettingPrivacy($value);
                break;
            case 11:
                $this->setSettingNotifications($value);
                break;
            case 12:
                $this->setPhone($value);
                break;
            case 13:
                $this->setPublicMessage($value);
                break;
            case 14:
                $this->setPictureUrl($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setEmail($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setApiKey($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPassSha256($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setPassSalt($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setBan($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSignupTs($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setGender($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setReputation($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setSettingPrivacy($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setSettingNotifications($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setPhone($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setPublicMessage($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setPictureUrl($arr[$keys[14]]);
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
     * @return $this|\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_NAME)) {
            $criteria->add(UserTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_API_KEY)) {
            $criteria->add(UserTableMap::COL_API_KEY, $this->api_key);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASS_SHA256)) {
            $criteria->add(UserTableMap::COL_PASS_SHA256, $this->pass_sha256);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASS_SALT)) {
            $criteria->add(UserTableMap::COL_PASS_SALT, $this->pass_salt);
        }
        if ($this->isColumnModified(UserTableMap::COL_BAN)) {
            $criteria->add(UserTableMap::COL_BAN, $this->ban);
        }
        if ($this->isColumnModified(UserTableMap::COL_SIGNUP_TS)) {
            $criteria->add(UserTableMap::COL_SIGNUP_TS, $this->signup_ts);
        }
        if ($this->isColumnModified(UserTableMap::COL_GENDER)) {
            $criteria->add(UserTableMap::COL_GENDER, $this->gender);
        }
        if ($this->isColumnModified(UserTableMap::COL_REPUTATION)) {
            $criteria->add(UserTableMap::COL_REPUTATION, $this->reputation);
        }
        if ($this->isColumnModified(UserTableMap::COL_SETTING_PRIVACY)) {
            $criteria->add(UserTableMap::COL_SETTING_PRIVACY, $this->setting_privacy);
        }
        if ($this->isColumnModified(UserTableMap::COL_SETTING_NOTIFICATIONS)) {
            $criteria->add(UserTableMap::COL_SETTING_NOTIFICATIONS, $this->setting_notifications);
        }
        if ($this->isColumnModified(UserTableMap::COL_PHONE)) {
            $criteria->add(UserTableMap::COL_PHONE, $this->phone);
        }
        if ($this->isColumnModified(UserTableMap::COL_PUBLIC_MESSAGE)) {
            $criteria->add(UserTableMap::COL_PUBLIC_MESSAGE, $this->public_message);
        }
        if ($this->isColumnModified(UserTableMap::COL_PICTURE_URL)) {
            $criteria->add(UserTableMap::COL_PICTURE_URL, $this->picture_url);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setApiKey($this->getApiKey());
        $copyObj->setPassSha256($this->getPassSha256());
        $copyObj->setPassSalt($this->getPassSalt());
        $copyObj->setBan($this->getBan());
        $copyObj->setSignupTs($this->getSignupTs());
        $copyObj->setGender($this->getGender());
        $copyObj->setReputation($this->getReputation());
        $copyObj->setSettingPrivacy($this->getSettingPrivacy());
        $copyObj->setSettingNotifications($this->getSettingNotifications());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setPublicMessage($this->getPublicMessage());
        $copyObj->setPictureUrl($this->getPictureUrl());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            $relObj = $this->getSocial();
            if ($relObj) {
                $copyObj->setSocial($relObj->copy($deepCopy));
            }

            foreach ($this->getInsertedLocations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInsertedLocation($relObj->copy($deepCopy));
                }
            }

            $relObj = $this->getSearchString();
            if ($relObj) {
                $copyObj->setSearchString($relObj->copy($deepCopy));
            }

            foreach ($this->getFavoriteLocations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFavoriteLocation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAddedConnections() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAddedConnection($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRequestedConnections() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRequestedConnection($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLocations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLocation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getExpiredLocations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addExpiredLocation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInsertedImages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInsertedImage($relObj->copy($deepCopy));
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
     * @return \User Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('InsertedLocation' == $relationName) {
            $this->initInsertedLocations();
            return;
        }
        if ('FavoriteLocation' == $relationName) {
            $this->initFavoriteLocations();
            return;
        }
        if ('AddedConnection' == $relationName) {
            $this->initAddedConnections();
            return;
        }
        if ('RequestedConnection' == $relationName) {
            $this->initRequestedConnections();
            return;
        }
        if ('Location' == $relationName) {
            $this->initLocations();
            return;
        }
        if ('ExpiredLocation' == $relationName) {
            $this->initExpiredLocations();
            return;
        }
        if ('InsertedImage' == $relationName) {
            $this->initInsertedImages();
            return;
        }
    }

    /**
     * Gets a single ChildUserSocial object, which is related to this object by a one-to-one relationship.
     *
     * @param  ConnectionInterface $con optional connection object
     * @return ChildUserSocial
     * @throws PropelException
     */
    public function getSocial(ConnectionInterface $con = null)
    {

        if ($this->singleSocial === null && !$this->isNew()) {
            $this->singleSocial = ChildUserSocialQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleSocial;
    }

    /**
     * Sets a single ChildUserSocial object as related to this object by a one-to-one relationship.
     *
     * @param  ChildUserSocial $v ChildUserSocial
     * @return $this|\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSocial(ChildUserSocial $v = null)
    {
        $this->singleSocial = $v;

        // Make sure that that the passed-in ChildUserSocial isn't already associated with this object
        if ($v !== null && $v->getUser(null, false) === null) {
            $v->setUser($this);
        }

        return $this;
    }

    /**
     * Clears out the collInsertedLocations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInsertedLocations()
     */
    public function clearInsertedLocations()
    {
        $this->collInsertedLocations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInsertedLocations collection loaded partially.
     */
    public function resetPartialInsertedLocations($v = true)
    {
        $this->collInsertedLocationsPartial = $v;
    }

    /**
     * Initializes the collInsertedLocations collection.
     *
     * By default this just sets the collInsertedLocations collection to an empty array (like clearcollInsertedLocations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInsertedLocations($overrideExisting = true)
    {
        if (null !== $this->collInsertedLocations && !$overrideExisting) {
            return;
        }

        $collectionClassName = LocationTableMap::getTableMap()->getCollectionClassName();

        $this->collInsertedLocations = new $collectionClassName;
        $this->collInsertedLocations->setModel('\Location');
    }

    /**
     * Gets an array of ChildLocation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLocation[] List of ChildLocation objects
     * @throws PropelException
     */
    public function getInsertedLocations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInsertedLocationsPartial && !$this->isNew();
        if (null === $this->collInsertedLocations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInsertedLocations) {
                // return empty collection
                $this->initInsertedLocations();
            } else {
                $collInsertedLocations = ChildLocationQuery::create(null, $criteria)
                    ->filterByAdmin($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInsertedLocationsPartial && count($collInsertedLocations)) {
                        $this->initInsertedLocations(false);

                        foreach ($collInsertedLocations as $obj) {
                            if (false == $this->collInsertedLocations->contains($obj)) {
                                $this->collInsertedLocations->append($obj);
                            }
                        }

                        $this->collInsertedLocationsPartial = true;
                    }

                    return $collInsertedLocations;
                }

                if ($partial && $this->collInsertedLocations) {
                    foreach ($this->collInsertedLocations as $obj) {
                        if ($obj->isNew()) {
                            $collInsertedLocations[] = $obj;
                        }
                    }
                }

                $this->collInsertedLocations = $collInsertedLocations;
                $this->collInsertedLocationsPartial = false;
            }
        }

        return $this->collInsertedLocations;
    }

    /**
     * Sets a collection of ChildLocation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $insertedLocations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setInsertedLocations(Collection $insertedLocations, ConnectionInterface $con = null)
    {
        /** @var ChildLocation[] $insertedLocationsToDelete */
        $insertedLocationsToDelete = $this->getInsertedLocations(new Criteria(), $con)->diff($insertedLocations);


        $this->insertedLocationsScheduledForDeletion = $insertedLocationsToDelete;

        foreach ($insertedLocationsToDelete as $insertedLocationRemoved) {
            $insertedLocationRemoved->setAdmin(null);
        }

        $this->collInsertedLocations = null;
        foreach ($insertedLocations as $insertedLocation) {
            $this->addInsertedLocation($insertedLocation);
        }

        $this->collInsertedLocations = $insertedLocations;
        $this->collInsertedLocationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Location objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Location objects.
     * @throws PropelException
     */
    public function countInsertedLocations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInsertedLocationsPartial && !$this->isNew();
        if (null === $this->collInsertedLocations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInsertedLocations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInsertedLocations());
            }

            $query = ChildLocationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAdmin($this)
                ->count($con);
        }

        return count($this->collInsertedLocations);
    }

    /**
     * Method called to associate a ChildLocation object to this object
     * through the ChildLocation foreign key attribute.
     *
     * @param  ChildLocation $l ChildLocation
     * @return $this|\User The current object (for fluent API support)
     */
    public function addInsertedLocation(ChildLocation $l)
    {
        if ($this->collInsertedLocations === null) {
            $this->initInsertedLocations();
            $this->collInsertedLocationsPartial = true;
        }

        if (!$this->collInsertedLocations->contains($l)) {
            $this->doAddInsertedLocation($l);

            if ($this->insertedLocationsScheduledForDeletion and $this->insertedLocationsScheduledForDeletion->contains($l)) {
                $this->insertedLocationsScheduledForDeletion->remove($this->insertedLocationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLocation $insertedLocation The ChildLocation object to add.
     */
    protected function doAddInsertedLocation(ChildLocation $insertedLocation)
    {
        $this->collInsertedLocations[]= $insertedLocation;
        $insertedLocation->setAdmin($this);
    }

    /**
     * @param  ChildLocation $insertedLocation The ChildLocation object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeInsertedLocation(ChildLocation $insertedLocation)
    {
        if ($this->getInsertedLocations()->contains($insertedLocation)) {
            $pos = $this->collInsertedLocations->search($insertedLocation);
            $this->collInsertedLocations->remove($pos);
            if (null === $this->insertedLocationsScheduledForDeletion) {
                $this->insertedLocationsScheduledForDeletion = clone $this->collInsertedLocations;
                $this->insertedLocationsScheduledForDeletion->clear();
            }
            $this->insertedLocationsScheduledForDeletion[]= clone $insertedLocation;
            $insertedLocation->setAdmin(null);
        }

        return $this;
    }

    /**
     * Gets a single ChildSearchUser object, which is related to this object by a one-to-one relationship.
     *
     * @param  ConnectionInterface $con optional connection object
     * @return ChildSearchUser
     * @throws PropelException
     */
    public function getSearchString(ConnectionInterface $con = null)
    {

        if ($this->singleSearchString === null && !$this->isNew()) {
            $this->singleSearchString = ChildSearchUserQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleSearchString;
    }

    /**
     * Sets a single ChildSearchUser object as related to this object by a one-to-one relationship.
     *
     * @param  ChildSearchUser $v ChildSearchUser
     * @return $this|\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSearchString(ChildSearchUser $v = null)
    {
        $this->singleSearchString = $v;

        // Make sure that that the passed-in ChildSearchUser isn't already associated with this object
        if ($v !== null && $v->getUser(null, false) === null) {
            $v->setUser($this);
        }

        return $this;
    }

    /**
     * Clears out the collFavoriteLocations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFavoriteLocations()
     */
    public function clearFavoriteLocations()
    {
        $this->collFavoriteLocations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFavoriteLocations collection loaded partially.
     */
    public function resetPartialFavoriteLocations($v = true)
    {
        $this->collFavoriteLocationsPartial = $v;
    }

    /**
     * Initializes the collFavoriteLocations collection.
     *
     * By default this just sets the collFavoriteLocations collection to an empty array (like clearcollFavoriteLocations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFavoriteLocations($overrideExisting = true)
    {
        if (null !== $this->collFavoriteLocations && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserLocationFavoriteTableMap::getTableMap()->getCollectionClassName();

        $this->collFavoriteLocations = new $collectionClassName;
        $this->collFavoriteLocations->setModel('\UserLocationFavorite');
    }

    /**
     * Gets an array of ChildUserLocationFavorite objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserLocationFavorite[] List of ChildUserLocationFavorite objects
     * @throws PropelException
     */
    public function getFavoriteLocations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFavoriteLocationsPartial && !$this->isNew();
        if (null === $this->collFavoriteLocations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFavoriteLocations) {
                // return empty collection
                $this->initFavoriteLocations();
            } else {
                $collFavoriteLocations = ChildUserLocationFavoriteQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFavoriteLocationsPartial && count($collFavoriteLocations)) {
                        $this->initFavoriteLocations(false);

                        foreach ($collFavoriteLocations as $obj) {
                            if (false == $this->collFavoriteLocations->contains($obj)) {
                                $this->collFavoriteLocations->append($obj);
                            }
                        }

                        $this->collFavoriteLocationsPartial = true;
                    }

                    return $collFavoriteLocations;
                }

                if ($partial && $this->collFavoriteLocations) {
                    foreach ($this->collFavoriteLocations as $obj) {
                        if ($obj->isNew()) {
                            $collFavoriteLocations[] = $obj;
                        }
                    }
                }

                $this->collFavoriteLocations = $collFavoriteLocations;
                $this->collFavoriteLocationsPartial = false;
            }
        }

        return $this->collFavoriteLocations;
    }

    /**
     * Sets a collection of ChildUserLocationFavorite objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $favoriteLocations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setFavoriteLocations(Collection $favoriteLocations, ConnectionInterface $con = null)
    {
        /** @var ChildUserLocationFavorite[] $favoriteLocationsToDelete */
        $favoriteLocationsToDelete = $this->getFavoriteLocations(new Criteria(), $con)->diff($favoriteLocations);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->favoriteLocationsScheduledForDeletion = clone $favoriteLocationsToDelete;

        foreach ($favoriteLocationsToDelete as $favoriteLocationRemoved) {
            $favoriteLocationRemoved->setUser(null);
        }

        $this->collFavoriteLocations = null;
        foreach ($favoriteLocations as $favoriteLocation) {
            $this->addFavoriteLocation($favoriteLocation);
        }

        $this->collFavoriteLocations = $favoriteLocations;
        $this->collFavoriteLocationsPartial = false;

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
    public function countFavoriteLocations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFavoriteLocationsPartial && !$this->isNew();
        if (null === $this->collFavoriteLocations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFavoriteLocations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFavoriteLocations());
            }

            $query = ChildUserLocationFavoriteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collFavoriteLocations);
    }

    /**
     * Method called to associate a ChildUserLocationFavorite object to this object
     * through the ChildUserLocationFavorite foreign key attribute.
     *
     * @param  ChildUserLocationFavorite $l ChildUserLocationFavorite
     * @return $this|\User The current object (for fluent API support)
     */
    public function addFavoriteLocation(ChildUserLocationFavorite $l)
    {
        if ($this->collFavoriteLocations === null) {
            $this->initFavoriteLocations();
            $this->collFavoriteLocationsPartial = true;
        }

        if (!$this->collFavoriteLocations->contains($l)) {
            $this->doAddFavoriteLocation($l);

            if ($this->favoriteLocationsScheduledForDeletion and $this->favoriteLocationsScheduledForDeletion->contains($l)) {
                $this->favoriteLocationsScheduledForDeletion->remove($this->favoriteLocationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserLocationFavorite $favoriteLocation The ChildUserLocationFavorite object to add.
     */
    protected function doAddFavoriteLocation(ChildUserLocationFavorite $favoriteLocation)
    {
        $this->collFavoriteLocations[]= $favoriteLocation;
        $favoriteLocation->setUser($this);
    }

    /**
     * @param  ChildUserLocationFavorite $favoriteLocation The ChildUserLocationFavorite object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeFavoriteLocation(ChildUserLocationFavorite $favoriteLocation)
    {
        if ($this->getFavoriteLocations()->contains($favoriteLocation)) {
            $pos = $this->collFavoriteLocations->search($favoriteLocation);
            $this->collFavoriteLocations->remove($pos);
            if (null === $this->favoriteLocationsScheduledForDeletion) {
                $this->favoriteLocationsScheduledForDeletion = clone $this->collFavoriteLocations;
                $this->favoriteLocationsScheduledForDeletion->clear();
            }
            $this->favoriteLocationsScheduledForDeletion[]= clone $favoriteLocation;
            $favoriteLocation->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related FavoriteLocations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserLocationFavorite[] List of ChildUserLocationFavorite objects
     */
    public function getFavoriteLocationsJoinLocation(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserLocationFavoriteQuery::create(null, $criteria);
        $query->joinWith('Location', $joinBehavior);

        return $this->getFavoriteLocations($query, $con);
    }

    /**
     * Clears out the collAddedConnections collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAddedConnections()
     */
    public function clearAddedConnections()
    {
        $this->collAddedConnections = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAddedConnections collection loaded partially.
     */
    public function resetPartialAddedConnections($v = true)
    {
        $this->collAddedConnectionsPartial = $v;
    }

    /**
     * Initializes the collAddedConnections collection.
     *
     * By default this just sets the collAddedConnections collection to an empty array (like clearcollAddedConnections());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAddedConnections($overrideExisting = true)
    {
        if (null !== $this->collAddedConnections && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserConnectionTableMap::getTableMap()->getCollectionClassName();

        $this->collAddedConnections = new $collectionClassName;
        $this->collAddedConnections->setModel('\UserConnection');
    }

    /**
     * Gets an array of ChildUserConnection objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserConnection[] List of ChildUserConnection objects
     * @throws PropelException
     */
    public function getAddedConnections(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAddedConnectionsPartial && !$this->isNew();
        if (null === $this->collAddedConnections || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAddedConnections) {
                // return empty collection
                $this->initAddedConnections();
            } else {
                $collAddedConnections = ChildUserConnectionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAddedConnectionsPartial && count($collAddedConnections)) {
                        $this->initAddedConnections(false);

                        foreach ($collAddedConnections as $obj) {
                            if (false == $this->collAddedConnections->contains($obj)) {
                                $this->collAddedConnections->append($obj);
                            }
                        }

                        $this->collAddedConnectionsPartial = true;
                    }

                    return $collAddedConnections;
                }

                if ($partial && $this->collAddedConnections) {
                    foreach ($this->collAddedConnections as $obj) {
                        if ($obj->isNew()) {
                            $collAddedConnections[] = $obj;
                        }
                    }
                }

                $this->collAddedConnections = $collAddedConnections;
                $this->collAddedConnectionsPartial = false;
            }
        }

        return $this->collAddedConnections;
    }

    /**
     * Sets a collection of ChildUserConnection objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $addedConnections A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setAddedConnections(Collection $addedConnections, ConnectionInterface $con = null)
    {
        /** @var ChildUserConnection[] $addedConnectionsToDelete */
        $addedConnectionsToDelete = $this->getAddedConnections(new Criteria(), $con)->diff($addedConnections);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->addedConnectionsScheduledForDeletion = clone $addedConnectionsToDelete;

        foreach ($addedConnectionsToDelete as $addedConnectionRemoved) {
            $addedConnectionRemoved->setUser(null);
        }

        $this->collAddedConnections = null;
        foreach ($addedConnections as $addedConnection) {
            $this->addAddedConnection($addedConnection);
        }

        $this->collAddedConnections = $addedConnections;
        $this->collAddedConnectionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserConnection objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserConnection objects.
     * @throws PropelException
     */
    public function countAddedConnections(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAddedConnectionsPartial && !$this->isNew();
        if (null === $this->collAddedConnections || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAddedConnections) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAddedConnections());
            }

            $query = ChildUserConnectionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collAddedConnections);
    }

    /**
     * Method called to associate a ChildUserConnection object to this object
     * through the ChildUserConnection foreign key attribute.
     *
     * @param  ChildUserConnection $l ChildUserConnection
     * @return $this|\User The current object (for fluent API support)
     */
    public function addAddedConnection(ChildUserConnection $l)
    {
        if ($this->collAddedConnections === null) {
            $this->initAddedConnections();
            $this->collAddedConnectionsPartial = true;
        }

        if (!$this->collAddedConnections->contains($l)) {
            $this->doAddAddedConnection($l);

            if ($this->addedConnectionsScheduledForDeletion and $this->addedConnectionsScheduledForDeletion->contains($l)) {
                $this->addedConnectionsScheduledForDeletion->remove($this->addedConnectionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserConnection $addedConnection The ChildUserConnection object to add.
     */
    protected function doAddAddedConnection(ChildUserConnection $addedConnection)
    {
        $this->collAddedConnections[]= $addedConnection;
        $addedConnection->setUser($this);
    }

    /**
     * @param  ChildUserConnection $addedConnection The ChildUserConnection object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeAddedConnection(ChildUserConnection $addedConnection)
    {
        if ($this->getAddedConnections()->contains($addedConnection)) {
            $pos = $this->collAddedConnections->search($addedConnection);
            $this->collAddedConnections->remove($pos);
            if (null === $this->addedConnectionsScheduledForDeletion) {
                $this->addedConnectionsScheduledForDeletion = clone $this->collAddedConnections;
                $this->addedConnectionsScheduledForDeletion->clear();
            }
            $this->addedConnectionsScheduledForDeletion[]= clone $addedConnection;
            $addedConnection->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collRequestedConnections collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRequestedConnections()
     */
    public function clearRequestedConnections()
    {
        $this->collRequestedConnections = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collRequestedConnections collection loaded partially.
     */
    public function resetPartialRequestedConnections($v = true)
    {
        $this->collRequestedConnectionsPartial = $v;
    }

    /**
     * Initializes the collRequestedConnections collection.
     *
     * By default this just sets the collRequestedConnections collection to an empty array (like clearcollRequestedConnections());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRequestedConnections($overrideExisting = true)
    {
        if (null !== $this->collRequestedConnections && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserConnectionTableMap::getTableMap()->getCollectionClassName();

        $this->collRequestedConnections = new $collectionClassName;
        $this->collRequestedConnections->setModel('\UserConnection');
    }

    /**
     * Gets an array of ChildUserConnection objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserConnection[] List of ChildUserConnection objects
     * @throws PropelException
     */
    public function getRequestedConnections(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collRequestedConnectionsPartial && !$this->isNew();
        if (null === $this->collRequestedConnections || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRequestedConnections) {
                // return empty collection
                $this->initRequestedConnections();
            } else {
                $collRequestedConnections = ChildUserConnectionQuery::create(null, $criteria)
                    ->filterByConnectionTo($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collRequestedConnectionsPartial && count($collRequestedConnections)) {
                        $this->initRequestedConnections(false);

                        foreach ($collRequestedConnections as $obj) {
                            if (false == $this->collRequestedConnections->contains($obj)) {
                                $this->collRequestedConnections->append($obj);
                            }
                        }

                        $this->collRequestedConnectionsPartial = true;
                    }

                    return $collRequestedConnections;
                }

                if ($partial && $this->collRequestedConnections) {
                    foreach ($this->collRequestedConnections as $obj) {
                        if ($obj->isNew()) {
                            $collRequestedConnections[] = $obj;
                        }
                    }
                }

                $this->collRequestedConnections = $collRequestedConnections;
                $this->collRequestedConnectionsPartial = false;
            }
        }

        return $this->collRequestedConnections;
    }

    /**
     * Sets a collection of ChildUserConnection objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $requestedConnections A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setRequestedConnections(Collection $requestedConnections, ConnectionInterface $con = null)
    {
        /** @var ChildUserConnection[] $requestedConnectionsToDelete */
        $requestedConnectionsToDelete = $this->getRequestedConnections(new Criteria(), $con)->diff($requestedConnections);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->requestedConnectionsScheduledForDeletion = clone $requestedConnectionsToDelete;

        foreach ($requestedConnectionsToDelete as $requestedConnectionRemoved) {
            $requestedConnectionRemoved->setConnectionTo(null);
        }

        $this->collRequestedConnections = null;
        foreach ($requestedConnections as $requestedConnection) {
            $this->addRequestedConnection($requestedConnection);
        }

        $this->collRequestedConnections = $requestedConnections;
        $this->collRequestedConnectionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserConnection objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserConnection objects.
     * @throws PropelException
     */
    public function countRequestedConnections(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collRequestedConnectionsPartial && !$this->isNew();
        if (null === $this->collRequestedConnections || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRequestedConnections) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRequestedConnections());
            }

            $query = ChildUserConnectionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByConnectionTo($this)
                ->count($con);
        }

        return count($this->collRequestedConnections);
    }

    /**
     * Method called to associate a ChildUserConnection object to this object
     * through the ChildUserConnection foreign key attribute.
     *
     * @param  ChildUserConnection $l ChildUserConnection
     * @return $this|\User The current object (for fluent API support)
     */
    public function addRequestedConnection(ChildUserConnection $l)
    {
        if ($this->collRequestedConnections === null) {
            $this->initRequestedConnections();
            $this->collRequestedConnectionsPartial = true;
        }

        if (!$this->collRequestedConnections->contains($l)) {
            $this->doAddRequestedConnection($l);

            if ($this->requestedConnectionsScheduledForDeletion and $this->requestedConnectionsScheduledForDeletion->contains($l)) {
                $this->requestedConnectionsScheduledForDeletion->remove($this->requestedConnectionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserConnection $requestedConnection The ChildUserConnection object to add.
     */
    protected function doAddRequestedConnection(ChildUserConnection $requestedConnection)
    {
        $this->collRequestedConnections[]= $requestedConnection;
        $requestedConnection->setConnectionTo($this);
    }

    /**
     * @param  ChildUserConnection $requestedConnection The ChildUserConnection object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeRequestedConnection(ChildUserConnection $requestedConnection)
    {
        if ($this->getRequestedConnections()->contains($requestedConnection)) {
            $pos = $this->collRequestedConnections->search($requestedConnection);
            $this->collRequestedConnections->remove($pos);
            if (null === $this->requestedConnectionsScheduledForDeletion) {
                $this->requestedConnectionsScheduledForDeletion = clone $this->collRequestedConnections;
                $this->requestedConnectionsScheduledForDeletion->clear();
            }
            $this->requestedConnectionsScheduledForDeletion[]= clone $requestedConnection;
            $requestedConnection->setConnectionTo(null);
        }

        return $this;
    }

    /**
     * Clears out the collLocations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLocations()
     */
    public function clearLocations()
    {
        $this->collLocations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLocations collection loaded partially.
     */
    public function resetPartialLocations($v = true)
    {
        $this->collLocationsPartial = $v;
    }

    /**
     * Initializes the collLocations collection.
     *
     * By default this just sets the collLocations collection to an empty array (like clearcollLocations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLocations($overrideExisting = true)
    {
        if (null !== $this->collLocations && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserLocationTableMap::getTableMap()->getCollectionClassName();

        $this->collLocations = new $collectionClassName;
        $this->collLocations->setModel('\UserLocation');
    }

    /**
     * Gets an array of ChildUserLocation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserLocation[] List of ChildUserLocation objects
     * @throws PropelException
     */
    public function getLocations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLocationsPartial && !$this->isNew();
        if (null === $this->collLocations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLocations) {
                // return empty collection
                $this->initLocations();
            } else {
                $collLocations = ChildUserLocationQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLocationsPartial && count($collLocations)) {
                        $this->initLocations(false);

                        foreach ($collLocations as $obj) {
                            if (false == $this->collLocations->contains($obj)) {
                                $this->collLocations->append($obj);
                            }
                        }

                        $this->collLocationsPartial = true;
                    }

                    return $collLocations;
                }

                if ($partial && $this->collLocations) {
                    foreach ($this->collLocations as $obj) {
                        if ($obj->isNew()) {
                            $collLocations[] = $obj;
                        }
                    }
                }

                $this->collLocations = $collLocations;
                $this->collLocationsPartial = false;
            }
        }

        return $this->collLocations;
    }

    /**
     * Sets a collection of ChildUserLocation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $locations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setLocations(Collection $locations, ConnectionInterface $con = null)
    {
        /** @var ChildUserLocation[] $locationsToDelete */
        $locationsToDelete = $this->getLocations(new Criteria(), $con)->diff($locations);


        $this->locationsScheduledForDeletion = $locationsToDelete;

        foreach ($locationsToDelete as $locationRemoved) {
            $locationRemoved->setUser(null);
        }

        $this->collLocations = null;
        foreach ($locations as $location) {
            $this->addLocation($location);
        }

        $this->collLocations = $locations;
        $this->collLocationsPartial = false;

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
    public function countLocations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLocationsPartial && !$this->isNew();
        if (null === $this->collLocations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLocations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLocations());
            }

            $query = ChildUserLocationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collLocations);
    }

    /**
     * Method called to associate a ChildUserLocation object to this object
     * through the ChildUserLocation foreign key attribute.
     *
     * @param  ChildUserLocation $l ChildUserLocation
     * @return $this|\User The current object (for fluent API support)
     */
    public function addLocation(ChildUserLocation $l)
    {
        if ($this->collLocations === null) {
            $this->initLocations();
            $this->collLocationsPartial = true;
        }

        if (!$this->collLocations->contains($l)) {
            $this->doAddLocation($l);

            if ($this->locationsScheduledForDeletion and $this->locationsScheduledForDeletion->contains($l)) {
                $this->locationsScheduledForDeletion->remove($this->locationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserLocation $location The ChildUserLocation object to add.
     */
    protected function doAddLocation(ChildUserLocation $location)
    {
        $this->collLocations[]= $location;
        $location->setUser($this);
    }

    /**
     * @param  ChildUserLocation $location The ChildUserLocation object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeLocation(ChildUserLocation $location)
    {
        if ($this->getLocations()->contains($location)) {
            $pos = $this->collLocations->search($location);
            $this->collLocations->remove($pos);
            if (null === $this->locationsScheduledForDeletion) {
                $this->locationsScheduledForDeletion = clone $this->collLocations;
                $this->locationsScheduledForDeletion->clear();
            }
            $this->locationsScheduledForDeletion[]= clone $location;
            $location->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Locations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserLocation[] List of ChildUserLocation objects
     */
    public function getLocationsJoinLocation(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserLocationQuery::create(null, $criteria);
        $query->joinWith('Location', $joinBehavior);

        return $this->getLocations($query, $con);
    }

    /**
     * Clears out the collExpiredLocations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addExpiredLocations()
     */
    public function clearExpiredLocations()
    {
        $this->collExpiredLocations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collExpiredLocations collection loaded partially.
     */
    public function resetPartialExpiredLocations($v = true)
    {
        $this->collExpiredLocationsPartial = $v;
    }

    /**
     * Initializes the collExpiredLocations collection.
     *
     * By default this just sets the collExpiredLocations collection to an empty array (like clearcollExpiredLocations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initExpiredLocations($overrideExisting = true)
    {
        if (null !== $this->collExpiredLocations && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserLocationExpiredTableMap::getTableMap()->getCollectionClassName();

        $this->collExpiredLocations = new $collectionClassName;
        $this->collExpiredLocations->setModel('\UserLocationExpired');
    }

    /**
     * Gets an array of ChildUserLocationExpired objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserLocationExpired[] List of ChildUserLocationExpired objects
     * @throws PropelException
     */
    public function getExpiredLocations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collExpiredLocationsPartial && !$this->isNew();
        if (null === $this->collExpiredLocations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collExpiredLocations) {
                // return empty collection
                $this->initExpiredLocations();
            } else {
                $collExpiredLocations = ChildUserLocationExpiredQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collExpiredLocationsPartial && count($collExpiredLocations)) {
                        $this->initExpiredLocations(false);

                        foreach ($collExpiredLocations as $obj) {
                            if (false == $this->collExpiredLocations->contains($obj)) {
                                $this->collExpiredLocations->append($obj);
                            }
                        }

                        $this->collExpiredLocationsPartial = true;
                    }

                    return $collExpiredLocations;
                }

                if ($partial && $this->collExpiredLocations) {
                    foreach ($this->collExpiredLocations as $obj) {
                        if ($obj->isNew()) {
                            $collExpiredLocations[] = $obj;
                        }
                    }
                }

                $this->collExpiredLocations = $collExpiredLocations;
                $this->collExpiredLocationsPartial = false;
            }
        }

        return $this->collExpiredLocations;
    }

    /**
     * Sets a collection of ChildUserLocationExpired objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $expiredLocations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setExpiredLocations(Collection $expiredLocations, ConnectionInterface $con = null)
    {
        /** @var ChildUserLocationExpired[] $expiredLocationsToDelete */
        $expiredLocationsToDelete = $this->getExpiredLocations(new Criteria(), $con)->diff($expiredLocations);


        $this->expiredLocationsScheduledForDeletion = $expiredLocationsToDelete;

        foreach ($expiredLocationsToDelete as $expiredLocationRemoved) {
            $expiredLocationRemoved->setUser(null);
        }

        $this->collExpiredLocations = null;
        foreach ($expiredLocations as $expiredLocation) {
            $this->addExpiredLocation($expiredLocation);
        }

        $this->collExpiredLocations = $expiredLocations;
        $this->collExpiredLocationsPartial = false;

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
    public function countExpiredLocations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collExpiredLocationsPartial && !$this->isNew();
        if (null === $this->collExpiredLocations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collExpiredLocations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getExpiredLocations());
            }

            $query = ChildUserLocationExpiredQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collExpiredLocations);
    }

    /**
     * Method called to associate a ChildUserLocationExpired object to this object
     * through the ChildUserLocationExpired foreign key attribute.
     *
     * @param  ChildUserLocationExpired $l ChildUserLocationExpired
     * @return $this|\User The current object (for fluent API support)
     */
    public function addExpiredLocation(ChildUserLocationExpired $l)
    {
        if ($this->collExpiredLocations === null) {
            $this->initExpiredLocations();
            $this->collExpiredLocationsPartial = true;
        }

        if (!$this->collExpiredLocations->contains($l)) {
            $this->doAddExpiredLocation($l);

            if ($this->expiredLocationsScheduledForDeletion and $this->expiredLocationsScheduledForDeletion->contains($l)) {
                $this->expiredLocationsScheduledForDeletion->remove($this->expiredLocationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserLocationExpired $expiredLocation The ChildUserLocationExpired object to add.
     */
    protected function doAddExpiredLocation(ChildUserLocationExpired $expiredLocation)
    {
        $this->collExpiredLocations[]= $expiredLocation;
        $expiredLocation->setUser($this);
    }

    /**
     * @param  ChildUserLocationExpired $expiredLocation The ChildUserLocationExpired object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeExpiredLocation(ChildUserLocationExpired $expiredLocation)
    {
        if ($this->getExpiredLocations()->contains($expiredLocation)) {
            $pos = $this->collExpiredLocations->search($expiredLocation);
            $this->collExpiredLocations->remove($pos);
            if (null === $this->expiredLocationsScheduledForDeletion) {
                $this->expiredLocationsScheduledForDeletion = clone $this->collExpiredLocations;
                $this->expiredLocationsScheduledForDeletion->clear();
            }
            $this->expiredLocationsScheduledForDeletion[]= clone $expiredLocation;
            $expiredLocation->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ExpiredLocations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserLocationExpired[] List of ChildUserLocationExpired objects
     */
    public function getExpiredLocationsJoinLocation(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserLocationExpiredQuery::create(null, $criteria);
        $query->joinWith('Location', $joinBehavior);

        return $this->getExpiredLocations($query, $con);
    }

    /**
     * Clears out the collInsertedImages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInsertedImages()
     */
    public function clearInsertedImages()
    {
        $this->collInsertedImages = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInsertedImages collection loaded partially.
     */
    public function resetPartialInsertedImages($v = true)
    {
        $this->collInsertedImagesPartial = $v;
    }

    /**
     * Initializes the collInsertedImages collection.
     *
     * By default this just sets the collInsertedImages collection to an empty array (like clearcollInsertedImages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInsertedImages($overrideExisting = true)
    {
        if (null !== $this->collInsertedImages && !$overrideExisting) {
            return;
        }

        $collectionClassName = LocationImageTableMap::getTableMap()->getCollectionClassName();

        $this->collInsertedImages = new $collectionClassName;
        $this->collInsertedImages->setModel('\LocationImage');
    }

    /**
     * Gets an array of ChildLocationImage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLocationImage[] List of ChildLocationImage objects
     * @throws PropelException
     */
    public function getInsertedImages(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInsertedImagesPartial && !$this->isNew();
        if (null === $this->collInsertedImages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInsertedImages) {
                // return empty collection
                $this->initInsertedImages();
            } else {
                $collInsertedImages = ChildLocationImageQuery::create(null, $criteria)
                    ->filterByInserter($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInsertedImagesPartial && count($collInsertedImages)) {
                        $this->initInsertedImages(false);

                        foreach ($collInsertedImages as $obj) {
                            if (false == $this->collInsertedImages->contains($obj)) {
                                $this->collInsertedImages->append($obj);
                            }
                        }

                        $this->collInsertedImagesPartial = true;
                    }

                    return $collInsertedImages;
                }

                if ($partial && $this->collInsertedImages) {
                    foreach ($this->collInsertedImages as $obj) {
                        if ($obj->isNew()) {
                            $collInsertedImages[] = $obj;
                        }
                    }
                }

                $this->collInsertedImages = $collInsertedImages;
                $this->collInsertedImagesPartial = false;
            }
        }

        return $this->collInsertedImages;
    }

    /**
     * Sets a collection of ChildLocationImage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $insertedImages A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setInsertedImages(Collection $insertedImages, ConnectionInterface $con = null)
    {
        /** @var ChildLocationImage[] $insertedImagesToDelete */
        $insertedImagesToDelete = $this->getInsertedImages(new Criteria(), $con)->diff($insertedImages);


        $this->insertedImagesScheduledForDeletion = $insertedImagesToDelete;

        foreach ($insertedImagesToDelete as $insertedImageRemoved) {
            $insertedImageRemoved->setInserter(null);
        }

        $this->collInsertedImages = null;
        foreach ($insertedImages as $insertedImage) {
            $this->addInsertedImage($insertedImage);
        }

        $this->collInsertedImages = $insertedImages;
        $this->collInsertedImagesPartial = false;

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
    public function countInsertedImages(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInsertedImagesPartial && !$this->isNew();
        if (null === $this->collInsertedImages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInsertedImages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInsertedImages());
            }

            $query = ChildLocationImageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInserter($this)
                ->count($con);
        }

        return count($this->collInsertedImages);
    }

    /**
     * Method called to associate a ChildLocationImage object to this object
     * through the ChildLocationImage foreign key attribute.
     *
     * @param  ChildLocationImage $l ChildLocationImage
     * @return $this|\User The current object (for fluent API support)
     */
    public function addInsertedImage(ChildLocationImage $l)
    {
        if ($this->collInsertedImages === null) {
            $this->initInsertedImages();
            $this->collInsertedImagesPartial = true;
        }

        if (!$this->collInsertedImages->contains($l)) {
            $this->doAddInsertedImage($l);

            if ($this->insertedImagesScheduledForDeletion and $this->insertedImagesScheduledForDeletion->contains($l)) {
                $this->insertedImagesScheduledForDeletion->remove($this->insertedImagesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLocationImage $insertedImage The ChildLocationImage object to add.
     */
    protected function doAddInsertedImage(ChildLocationImage $insertedImage)
    {
        $this->collInsertedImages[]= $insertedImage;
        $insertedImage->setInserter($this);
    }

    /**
     * @param  ChildLocationImage $insertedImage The ChildLocationImage object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeInsertedImage(ChildLocationImage $insertedImage)
    {
        if ($this->getInsertedImages()->contains($insertedImage)) {
            $pos = $this->collInsertedImages->search($insertedImage);
            $this->collInsertedImages->remove($pos);
            if (null === $this->insertedImagesScheduledForDeletion) {
                $this->insertedImagesScheduledForDeletion = clone $this->collInsertedImages;
                $this->insertedImagesScheduledForDeletion->clear();
            }
            $this->insertedImagesScheduledForDeletion[]= clone $insertedImage;
            $insertedImage->setInserter(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related InsertedImages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLocationImage[] List of ChildLocationImage objects
     */
    public function getInsertedImagesJoinLocation(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLocationImageQuery::create(null, $criteria);
        $query->joinWith('Location', $joinBehavior);

        return $this->getInsertedImages($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->email = null;
        $this->api_key = null;
        $this->pass_sha256 = null;
        $this->pass_salt = null;
        $this->ban = null;
        $this->signup_ts = null;
        $this->gender = null;
        $this->reputation = null;
        $this->setting_privacy = null;
        $this->setting_notifications = null;
        $this->phone = null;
        $this->public_message = null;
        $this->picture_url = null;
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
            if ($this->singleSocial) {
                $this->singleSocial->clearAllReferences($deep);
            }
            if ($this->collInsertedLocations) {
                foreach ($this->collInsertedLocations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->singleSearchString) {
                $this->singleSearchString->clearAllReferences($deep);
            }
            if ($this->collFavoriteLocations) {
                foreach ($this->collFavoriteLocations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAddedConnections) {
                foreach ($this->collAddedConnections as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRequestedConnections) {
                foreach ($this->collRequestedConnections as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLocations) {
                foreach ($this->collLocations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collExpiredLocations) {
                foreach ($this->collExpiredLocations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInsertedImages) {
                foreach ($this->collInsertedImages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->singleSocial = null;
        $this->collInsertedLocations = null;
        $this->singleSearchString = null;
        $this->collFavoriteLocations = null;
        $this->collAddedConnections = null;
        $this->collRequestedConnections = null;
        $this->collLocations = null;
        $this->collExpiredLocations = null;
        $this->collInsertedImages = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
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
