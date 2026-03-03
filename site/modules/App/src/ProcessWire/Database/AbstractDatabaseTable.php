<?php namespace App\ProcessWire\Database;
// Base PHP
use Exception;
use PDO;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\DatabaseQuerySelect;
// Pauldro ProcessWire
use App\ProcessWire\Database\DatabaseQueryCreateTable;
use App\ProcessWire\Database\DatabaseQueryInsert;
use App\ProcessWire\Database\DatabaseQueryUpdate;
use App\ProcessWire\Database\DatabaseQueryDelete;
use App\ProcessWire\Database\DatabaseRecord;

/**
 * DatabaseTable
 * Handles CRUD actions for a Custom ProcessWire Database Table
 */
abstract class AbstractDatabaseTable extends WireData {
    const TABLE = '';
    const FORMAT_DATETIME = 'Y-m-d H:i:s';
    const COLUMNS = [];
    const PRIMARYKEY = [];
    const RECORDKEY  = [];
    const RECORD_CLASS = DatabaseRecord::class;
    const KEY_GLUE    = '|';

    /** @var static */
    protected static $instance;

    /** @return static */
    public static function instance() {
        if (empty(static::$instance)) {
            $instance = new static();
            static::$instance = $instance;
            static::$instance->init();
        }
        return static::$instance;
    }

    public function init() : bool
    {
        return $this->install();
    }

    /**
     * Install Table in Database
     * @return bool
     */
    public function install() : bool
    {
        if ($this->database->tableExists(static::TABLE)) {
            return true;
        }
        return $this->createTable();
    }

    /**
     * Create Table in database
     * @return bool
     */
    public function createTable() {
        $q = new DatabaseQueryCreateTable();
        $q->table(static::TABLE);
        $q->columns(static::COLUMNS);
        $q->primarykey(static::PRIMARYKEY);

        $success = false;

        try {
            $success = boolval($q->execute()->rowCount());
        } catch (Exception $e) {
            throw new Exception("Unable to create table " . static::TABLE . " " . $e->getMessage());
        }
        return $success;
    }

/* =============================================================
    Query Functions
============================================================= */
    public function tableExists() : bool
    {
        return $this->database->tableExists(static::TABLE);
    }

    	
    protected function query() : DatabaseQuerySelect
    {
        $q = new DatabaseQuerySelect();
        $q->from(static::TABLE);
        return $q;
    }

    protected function queryKey(array $key) : DatabaseQuerySelect
    {
        $data = $this->new();
        $data->setArray($key);
        
        $where  = $this->getParamsForQuery(static::RECORDKEY, ' AND ');
        $params = $this->getParamKeyValues($data, static::RECORDKEY);

        $q = $this->query();
        $q->where($where, $params);
        return $q;
    }

    protected function queryPrimaryKey(array $key) : DatabaseQuerySelect
    {
        $data = $this->new();
        $data->setData($key);
        
        $where  = $this->getParamsForQuery(static::PRIMARYKEY, ' AND ');
        $params = $this->getParamKeyValues($data, static::PRIMARYKEY);

        $q = $this->query();
        $q->where($where, $params);
        return $q;
    }

    public function new() : DatabaseRecord
    {
        $class = static::RECORD_CLASS;
        return new $class();
    }

/* =============================================================
    Read Functions
============================================================= */
    public function countAll() : int
    {
        $q = $this->query();
        $q->select('COUNT(*)');
        return intval($q->execute()->fetchColumn());
    }
    
    public function existsByPrimaryKey(array $key) : bool
    {
        $q = $this->queryPrimaryKey($key);
        $q->select('COUNT(*)');
        return boolval($q->execute()->fetchColumn());
    }

    public function existsByKey(array $key) : bool
    {
        $q = $this->queryKey($key);
        $q->select('COUNT(*)');
        return boolval($q->execute()->fetchColumn());
    }

    /**
     * @return array[DatabaseRecord]
     */
    public function findAll() : array
    {
        $q = $this->query();
        $q->select('*');
        return $q->execute()->fetchAll(PDO::FETCH_CLASS, static::DatabaseRecord_CLASS);
    }

    abstract public function exists($key) : bool;

    abstract public function existsArray(array $record) : bool;

/* =============================================================
    Create, Update, Delete Functions
============================================================= */
    public function insert(DatabaseRecord $data) : bool
    {
        $q = new DatabaseQueryInsert();
        $q->insertInto(static::TABLE);
        $q->columns(array_keys($data->data));
        $q->values($this->getParamKeysString($data), $this->getParamKeyValues($data));
        return boolval($q->execute()->rowCount());
    }

    public function insertArray(array $record) : bool
    {
        if ($this->validateRecordKeys($record) === false) {
            return false;
        }
        $DatabaseRecord = $this->new();
        $DatabaseRecord->setArray($record);
        return $this->insert($DatabaseRecord);
    }

    public function update(DatabaseRecord $data) : bool {
        $values = $this->getParamKeyValues($data);
        $q = new DatabaseQueryUpdate();
        $q->update(static::TABLE);

        $q->values($this->getParamsForQuery(array_keys($data->data)), $values);
        $where  = $this->getParamsForQuery(static::PRIMARYKEY, ' AND ');
        $params = $this->getParamKeyValues($data, static::PRIMARYKEY);
        $q->where($where, $params);
        return boolval($q->execute()->rowCount());
    }


    public function delete(DatabaseRecord $data) : bool
    {
        $q = new DatabaseQueryDelete();
        $q->table(static::TABLE);
        $where  = $this->getParamsForQuery(static::PRIMARYKEY, ' AND ');
        $params = $this->getParamKeyValues($data, static::PRIMARYKEY);
        $q->where($where, $params);
        return boolval($q->execute()->rowCount());
    }

    public function deleteByRecordKey(array $keys) : bool
    {
        if ($this->arrayHasRecordKeys($keys) === false) {
            return true;
        }

        $q = new DatabaseQueryDelete();
        $q->table(static::TABLE);
        $where  = $this->getParamsForQuery(static::RECORDKEY, ' AND ');
        $params = $this->getParamKeyValuesArray($keys, static::RECORDKEY);
        $q->where($where, $params);
        return boolval($q->execute()->rowCount());
    }

/* =============================================================
    Param, Key Functions
============================================================= */
    /**
     * Return Parameters for Set
     * @param  array $cols
     * @return string		column=:column
     */
    protected function getParamsForQuery($cols, $glue = ',') : string
    {
        $data = [];
        foreach ($cols as $col) {
            $data[] = "$col=:$col";
        }
        return implode($glue, $data);
    }

    /**
     * Return Params from Associative array
     * @param  array $cols
     * @param  string $glue
     * @return string
     */
    protected function getParamsFromAssocArray($cols, $glue = ',') : string
    {
        $data = [];
        foreach ($cols as $col => $value) {
            $data[] = $value === null ? "$col is :$col" : "$col=:$col";
        }
        return implode($glue, $data);
    }

    /**
     * Return Array of Columns for set
     * @param  array $cols
     * @return array		column=:column
     */
    protected function getColParamSetArray($cols) : array
    {
        $data = [];
        foreach ($cols as $col) {
            $data[] = "$col=:$col";
        }
        return $data;
    }

    /**
     * Return Parameters Key Arrays
     * @param  DatabaseRecord    $data
     * @return string		     :col1,:col2
     */
    protected function getParamKeysArray(DatabaseRecord $data) : string
    {
        return  array_keys($data->data);
    }

    /**
     * Return Parameters Key String
     * @param  DatabaseRecord    $data
     * @return string		     :col1,:col2
     */
    protected function getParamKeysString(DatabaseRecord $data) : string
    {
        return ':' . implode(',:', array_keys($data->data));
    }

    /**
     * Return Parameters Keyed by Param Key
     * @param  DatabaseRecord $data
     * @param  array           $keys	
     * @return array		   [':key' => $value]
     */
    protected function getParamKeyValues(DatabaseRecord $data, $keys = []) : array
    {
        $params = [];

        foreach ($data->data as $key => $value) {
            if (in_array($key, $keys) || empty($keys)) {
                $params[':' . $key] = $value;
            }
        }
        return $params;
    }

    /**
     * Return Parameters Keyed by Param Key
     * @param  array $data
     * @param  array $keys	
     * @return array		   [':key' => $value]
     */
    protected function getParamKeyValuesArray(array $data, $keys = []) : array
    {
        $params = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $keys) || empty($keys)) {
                $params[':' . $key] = $value;
            }
        }
        return $params;
    }

    protected function validateArrayKeys(array $record) : bool
    {
        foreach (array_keys(static::COLUMNS) as $col) {
            if (array_key_exists($col, $record) === false) {
                return false;
            }
        }
        return true;
    }

    protected function arrayHasRecordKeys(array $r) : bool
    {
        foreach (static::RECORDKEY as $col) {
            if (array_key_exists($col, $r) === false) {
                return false;
            }
        }
        return true;
    }
}