<?php
/**
 * Database helper class for PostgreSQL.
 * 
 * This class makes a lazy connection to the database.  
 *
 * This class sets PDO to throw exceptions.  It is the caller's 
 * responsibility to catch the exceptions and check for any error codes
 * to ignore.  The most common case is catching the duplicate key error
 * to mimic the INSERT IGNORE functionality of MySQL.
 *
 * @package Crimson
 * @copyright 2009 Herman Radtke
 * @author Herman Radtke 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Crimson_Pgsql
{
    /**
     * Database connection handler.
     * 
     * @var object
     * @access private
     */
    private $_dbh;

    /**
     * Database connection information.
     * 
     * @var array
     * @access private
     */
    private $_dsn;

    /**
     * Constructor
     * 
     * Store database connection information.
     *
     * @param mixed $dsn database connection information
     */
    public function __construct(array $info)
    {
        if (!isset($dsn)) {
            throw new Exception('Missing database connection information.');
        }

        $this->_dsn =$this->_createDsn($info);
    }

    /**
     * Create a PDO compatible dsn from array information.
     * 
     * @param array $info Database connection information;
     * @access private
     * @return string dsn connection string
     */
    private function _createDsn(array $info)
    {
        if (!isset($info['host']) || 
            !isset($info['dbname']) || 
            !isset($info['user']) || 
            !isset($info['password'])) {

                throw new Exception('Invalid database connection information');
        }

        $dsn = sprintf('pgsql:host=%s dbname=%s user=%s password=%s', 
            $info['host'], $info['dbname'], $info['user'], $info['password']);

        return $dsn;
    }

    /**
     * Connect to the database.
     *
     * Allows for lazy connections.
     * 
     * @access private
     * @return void
     */
    private function _connect()
    {
        if (isset($this->_dbh)) {
            return;
        }

        $this->_dbh = New PDO($this->_dsn);
        $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Prepare and execute a sql statement.
     *
     * All query helper functions should use this function to make
     * the actual query to the database.
     *  
     * The sql statement must use named parameters.
     * 
     * Good: SELECT * FROM foo WHERE bar = :bar
     * Bad: SELECT * FROM foo WHERE bar = ?
     * 
     * @param string $sql Query string.
     * @param array @bindings Values to bind to the query string.
     * @return PDOStatement A statement handle with a result set
     */
    private function _pquery($sql, $bindings)
    {
        $this->_connect();
        $stmt = $this->_dbh->prepare($sql);
        $stmt->execute($bindings);

        return $stmt;
    }

    /**
     * Execute an sql statement and return the number of affected rows.
     *
     * @param string $sql Query string.
     * @param array $bindings Values to bind to the query string.
     * @return integer The number of rows.
     */
    final public function query($sql, $bindings=array())
    {
        $stmt = $this->_pquery($sql, $bindings);
        return $stmt->rowCount();    	
    }

    /**
     * Execute a sql statement and return all results.  
     * 
     * The row data is indexed by column name.
     * 
     * @param string $sql Query string.
     * @param array	$bindings Values to bind to the query string.
     * @final
     * @access public
     * @return array Associative array of results
     */
    final public function getAssocAll($sql, $bindings=array())
    {	
        $stmt = $this->_pquery($sql, $bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Execute a sql statement and return one result.
     * 
     * The row data is indexed by column name.
     * 
     * @param string $sql Query string.
     * @param array	$bindings Values to bind to the query string.
     * @final
     * @access public
     * @return array Associative array of results
     */
    final public function getAssocRow($sql, $bindings=array())
    {
        $stmt = $this->_pquery($sql, $bindings);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Determine if error code is the ANSI/SQL-92 error code for duplicate 
     * record
     * 
     * @param integer $code error code from a sql query
     * @final
     * @access public
     * @return boolean true if the code is a duplicate error code, else false 
     */
    final public function isDuplicateKeyError($code)
    {
        return $code == 23505 ? true : false;
    }
}
