<?php

namespace biophp\db;

/** 
 * Database connection object
 * 
 * Provides connection and logging frame work for classes
 * that inherit from it.
 * 
 * @author Randall Svancara
 */

class Connection extends \PDO{

    /**
   * The name of the Statement class for this connection.
   *
   * @var string
   */
  protected $statementClass = 'biophp\db\DatabaseStatementBase';
  
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
  function __construct($dsn){
    
    // Get the instance of the class 
    //$config = \biophp\Config::getInstance();
    
    //$driver_options = NULL;
    
    // Get the dsn for the current profile that should have
    // been set when the configuration was initialized.  
    //$this->dsn = $config->getDSNforProfile($config->dbprofile,NULL,NULL,$driver_options);
    
    // Create the database handle
    try {
     
      // Because the other methods don't seem to work right.
      $driver_options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
      $driver_options[\PDO::ATTR_STRINGIFY_FETCHES] = TRUE;
      $driver_options[\PDO::ATTR_CASE] = \PDO::CASE_LOWER;
        
      parent::__construct($dsn,NULL,NULL,$driver_options);
      
      // Force PostgreSQL to use the UTF-8 character set by default.
      $this->exec("SET NAMES 'UTF8'");
      
      // Set a specific PDOStatement class if the driver requires that.
      if (!empty($this->statementClass)) {
        $this->setAttribute(\PDO::ATTR_STATEMENT_CLASS, array($this->statementClass, array($this)));
      }
      
      
    } catch ( \PDOException $pe) {
        
        throw new \Exception("Error connecting to the database: $pe");
      
    } catch (Exception $e) {
        
        throw new \Exception("Error connecting to the database: $e");
        
    } 

  }

  /**
   * Prepares a query string and returns the prepared statement.
   *
   * This method caches prepared statements, reusing them when
   * possible. It also prefixes tables names enclosed in curly-braces.
   *
   * @param $query
   *   The query string as SQL, with curly-braces surrounding the
   *   table names.
   *
   * @return DatabaseStatementInterface
   *   A PDO prepared statement ready for its execute() method.
   */
  public function prepareQuery($query) {
    //$query = $this->prefixTables($query);

    // Call PDO::prepare.
    return parent::prepare($query);
  }

  
  /**
   * Returns the default query options for any given query.
   *
   * A given query can be customized with a number of option flags in an
   * associative array:
   * - target: The database "target" against which to execute a query. Valid
   *   values are "default" or "slave". The system will first try to open a
   *   connection to a database specified with the user-supplied key. If one
   *   is not available, it will silently fall back to the "default" target.
   *   If multiple databases connections are specified with the same target,
   *   one will be selected at random for the duration of the request.
   * - fetch: This element controls how rows from a result set will be
   *   returned. Legal values include PDO::FETCH_ASSOC, PDO::FETCH_BOTH,
   *   PDO::FETCH_OBJ, PDO::FETCH_NUM, or a string representing the name of a
   *   class. If a string is specified, each record will be fetched into a new
   *   object of that class. The behavior of all other values is defined by PDO.
   *   See http://www.php.net/PDOStatement-fetch
   * - return: Depending on the type of query, different return values may be
   *   meaningful. This directive instructs the system which type of return
   *   value is desired. The system will generally set the correct value
   *   automatically, so it is extremely rare that a module developer will ever
   *   need to specify this value. Setting it incorrectly will likely lead to
   *   unpredictable results or fatal errors. Legal values include:
   *   - Database::RETURN_STATEMENT: Return the prepared statement object for
   *     the query. This is usually only meaningful for SELECT queries, where
   *     the statement object is how one accesses the result set returned by the
   *     query.
   *   - Database::RETURN_AFFECTED: Return the number of rows affected by an
   *     UPDATE or DELETE query. Be aware that means the number of rows actually
   *     changed, not the number of rows matched by the WHERE clause.
   *   - Database::RETURN_INSERT_ID: Return the sequence ID (primary key)
   *     created by an INSERT statement on a table that contains a serial
   *     column.
   *   - Database::RETURN_NULL: Do not return anything, as there is no
   *     meaningful value to return. That is the case for INSERT queries on
   *     tables that do not contain a serial column.
   * - throw_exception: By default, the database system will catch any errors
   *   on a query as an Exception, log it, and then rethrow it so that code
   *   further up the call chain can take an appropriate action. To suppress
   *   that behavior and simply return NULL on failure, set this option to
   *   FALSE.
   *
   * @return
   *   An array of default query options.
   */
  protected function defaultOptions() {
    return array(
      'target' => 'default',
      'fetch' => \PDO::FETCH_OBJ,
      'return' => Database::RETURN_STATEMENT,
      'throw_exception' => TRUE,
    );
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
      if ($query instanceof biophp\db\DatabaseStatementInterface) {
        $stmt = $query;
        $stmt->execute(NULL, $options);
      }
      else {
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
