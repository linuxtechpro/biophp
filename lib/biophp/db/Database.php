<?php

namespace biophp\db;

/**
 * Primary front-controller for the database system.
 *
 * This class is uninstantiatable and un-extendable. It acts to encapsulate
 * all control and shepherding of database connections into a single location
 * without the use of globals.
 *
 * Ideas borrowed Drupal 7 database API...thanks!!
 * 
 */

abstract class Database {

  /**
   * Flag to indicate a query call should simply return NULL.
   *
   * This is used for queries that have no reasonable return value anyway, such
   * as INSERT statements to a table without a serial primary key.
   */
  const RETURN_NULL = 0;

  /**
   * Flag to indicate a query call should return the prepared statement.
   */
  const RETURN_STATEMENT = 1;

  /**
   * Flag to indicate a query call should return the number of affected rows.
   */
  const RETURN_AFFECTED = 2;

  /**
   * Flag to indicate a query call should return the "last insert id".
   */
  const RETURN_INSERT_ID = 3;

  /**
   * An nested array of all active connections. It is keyed by database name
   * and target.
   *
   * @var array
   */
    
  static protected $connection=NULL;
    
  /**
   * A processed copy of the database connection from the configuration
   *
   * @var String
   */
  static protected $dsn = NULL;
  
  
  final static public function parseConnectionInfo() {
    
    // Get the instance of the class 
    $config = \biophp\Config::getInstance();
    
    $driver_options = NULL;
    
    // Get the dsn for the current profile that should have
    // been set when the configuration was initialized.  
    self::$dsn = $config->getDSNforProfile($config->dbprofile,NULL,NULL,$driver_options);
    
  }
  
  
  /**
   * Gets the connection object for the specified database key and target.
   *
   * @param $target
   *   The database target name.
   * @param $key
   *   The database connection key. Defaults to NULL which means the active key.
   *
   * @return DatabaseConnection
   *   The corresponding connection object.
   */
  final static public function getConnection() {
    
    if (empty(self::$dsn)) {
      self::parseConnectionInfo();
    }
  
    // Open the connection  
    self::openConnection();
    
    return self::$connection;
       
  }
  
  
  /**
   * Opens a new connection from self::$dsn
   */
  final protected static function openConnection() {
    
    // If the connection is empty, then we open the connection.
    if(empty(self::$connection)) {
      
      self::$connection = new Connection(self::$dsn);
      
    }
    
  }
  
  /**
   * Closes the connection
   */
  final static public function closeConnection() {
    
    unset(self::$connection);
    
  }
  
}