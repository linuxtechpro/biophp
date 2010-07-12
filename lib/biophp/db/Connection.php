<?php

namespace biophp\db;

/** 
 * Base database object
 * 
 * Provides connection and logging frame work for classes
 * that inherit from it.
 * 
 * 
 * @author Randall Svancara
 */

class Connection extends \PDO{

  protected $dsn = NULL;
  
  //protected $connection = NULL;
  
  /**
   * The connection information for this connection object.
   *
   * @var array
   */
  protected $connectionOptions = array();
  
  /**
   * The driver options

  /**
   * Default constructor
   */
  function __construct(){
    
    // Get the instance of the class 
    $config = \biophp\Config::getInstance();
    
    $driver_options = NULL;
    
    // Get the dsn for the current profile that should have
    // been set when the configuration was initialized.  
    $this->dsn = $config->getDSNforProfile($config->dbprofile,NULL,NULL,$driver_options);
    
    // Create the database handle
    try {
     
      // Because the other methods don't seem to work right.
      $driver_options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
      $driver_options[\PDO::ATTR_STRINGIFY_FETCHES] = TRUE;
      $driver_options[\PDO::ATTR_CASE] = \PDO::CASE_LOWER;
        
      parent::__construct($this->dsn,NULL,NULL,$driver_options);
      
      // Force PostgreSQL to use the UTF-8 character set by default.
      $this->exec("SET NAMES 'UTF8'");
      
    } catch ( \PDOException $pe) {
        
        throw new \Exception("Error connecting to the database: $pe");
      
    } catch (Exception $e) {
        
        throw new \Exception("Error connecting to the database: $e");
        
    } 

  }
  
  /**
   * Executes a query string against the database.
   *
   * This method provides a central handler for the actual execution of every
   * query. All queries executed by Drupal are executed as PDO prepared
   * statements.
   *
   * @param $query
   *   The query to execute. In most cases this will be a string containing
   *   an SQL query with placeholders. An already-prepared instance of
   *   DatabaseStatementInterface may also be passed in order to allow calling
   *   code to manually bind variables to a query. If a
   *   DatabaseStatementInterface is passed, the $args array will be ignored.
   *   It is extremely rare that module code will need to pass a statement
   *   object to this method. It is used primarily for database drivers for
   *   databases that require special LOB field handling.
   * @param $args
   *   An array of arguments for the prepared statement. If the prepared
   *   statement uses ? placeholders, this array must be an indexed array.
   *   If it contains named placeholders, it must be an associative array.
   * @param $options
   *   An associative array of options to control how the query is run. See
   *   the documentation for DatabaseConnection::defaultOptions() for details.
   *
   * @return DatabaseStatementInterface
   *   This method will return one of: the executed statement, the number of
   *   rows affected by the query (not the number matched), or the generated
   *   insert IT of the last query, depending on the value of
   *   $options['return']. Typically that value will be set by default or a
   *   query builder and should not be set by a user. If there is an error,
   *   this method will return NULL and may throw an exception if
   *   $options['throw_exception'] is TRUE.
   */
  public function query($query, array $args = array(), $options = array()) {

    // Use default values if not already set.
    $options += $this->defaultOptions();

    try {
      // We allow either a pre-bound statement object or a literal string.
      // In either case, we want to end up with an executed statement object,
      // which we pass to PDOStatement::execute.
      if ($query instanceof DatabaseStatementInterface) {
        $stmt = $query;
        $stmt->execute(NULL, $options);
      }
      else {
        $this->expandArguments($query, $args);
        $stmt = $this->prepareQuery($query);
        $stmt->execute($args, $options);
      }

      // Depending on the type of query we may need to return a different value.
      // See DatabaseConnection::defaultOptions() for a description of each
      // value.
      switch ($options['return']) {
        case Database::RETURN_STATEMENT:
          return $stmt;
        case Database::RETURN_AFFECTED:
          return $stmt->rowCount();
        case Database::RETURN_INSERT_ID:
          return $this->lastInsertId();
        case Database::RETURN_NULL:
          return;
        default:
          throw new PDOException('Invalid return directive: ' . $options['return']);
      }
    }
    catch (PDOException $e) {
      if ($options['throw_exception']) {
        // Add additional debug information.
        if ($query instanceof DatabaseStatementInterface) {
          $e->query_string = $stmt->getQueryString();
        }
        else {
          $e->query_string = $query;
        }
        $e->args = $args;
        throw $e;
      }
      return NULL;
    }
  }


}
