<?php

namespace biophp\db;

/**
 * Primary front-controller for the database system.
 *
 * This class is uninstantiatable and un-extendable. It acts to encapsulate
 * all control and shepherding of database connections into a single location
 * without the use of globals.
 *
 * Ideas borrowed from Drupal 7 database API
 * 
 */


abstract class Database {


  /**
   * An nested array of all active connections. It is keyed by database name
   * and target.
   *
   * @var array
   */
    
  static protected $connections = array();
    
  /**
   * A processed copy of the database connection information from settings.php.
   *
   * @var array
   */
  static protected $databaseInfo = NULL;
  
  
  
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
  public final static function getConnection() {
        
        
        
  }
    
}