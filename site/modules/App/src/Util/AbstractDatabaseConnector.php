<?php namespace App\Util;
// ProcessWire
use ProcessWire\Config;
use ProcessWire\WireData;
use ProcessWire\WireDatabasePDO;
// Pauldro ProcessWire
use App\ProcessWire\WireDatabasePDOConnector;

/**
 * Service for Connecting MySQL database with ProcessWire / Propel ORM
 * 
 * @property Config                    $dbconfig
 * @property WireDatabasePDOConnector  $pw
 */
abstract class AbstractDatabaseConnector extends WireData {
    const NAME_PW	  = '';

    protected static $instance;

    public static function instance() {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function __construct() {
        $this->dbconfig = $this->dbconfig();
        $this->pw       = new WireDatabasePDOConnector();
    }

/* =============================================================
    DB Credentials
============================================================= */
    /**
     * Returns Config to connect to Database
     * @return Config
     */
    abstract protected function dbconfig();

/* =============================================================
    DB Connect
============================================================= */
    public function connect() : bool
    {
        if ($this->connectProcessWire() === false) {
            return false;
        }
        $this->wire('db-' . static::NAME_PW , $this, true);
        return true;
    }

    /**
     * Return if Connection was made via ProcessWire
     * @return bool
     */
    public function connectProcessWire() {
        $this->pw->wirename = static::NAME_PW;
        $this->pw->setDbconfig($this->dbconfig);
        return $this->pw->connect();
    }

/* =============================================================
    Database Accessors
============================================================= */
    /**
     * Return WireDatabasePDO
     * @return WireDatabasePDO
     */
    public function getWireDatabasePDO() {
        if ($this->pw->pdo) {
            return $this->pw->pdo;
        }
        $this->connectProcessWire();
        return $this->pw->pdo;
    }

/* =============================================================
    Logging
============================================================= */
    /**
     * Writes Error Message to Database Error Log
     * @param  string $message Error Message
     * @return void
     */
    public function logError($message) {
        $date = date("Y-m-d h:m:s");
        $class = static::NAME_PW;
        $message = "[{$date}] [{$class}] $message";
        $this->log->save('db-errors', $message);
    }
}