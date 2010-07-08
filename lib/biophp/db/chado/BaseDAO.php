<?php

namespace biophp\db\chado;

/** 
 * Base data access object
 * All Chado data access objects must inherit from this class
 * @abstract
 * 
 * @author Randall Svancara
 */

abstract class BaseDAO {

  protected $dsn = NULL;
  
  protected $handle = NULL;

  /**
   * Default constructor
   */
  function __construct(){
    
    // Get the instance of the class 
    $config = \biophp\Config::getInstance();
    
    // Get the dsn for the current profile that should have
    // been set when the configuration was initialized.  
    $this->dsn = $config->getDSNforProfile($config->dbprofile);
    
    print $this->dsn . "\n";
    
    // Create the database handle
    try {
        
      $this->handle = new \PDO($this->dsn);
      
    } catch ( \PDOException $pe) {
        
        throw new \Exception("Error connecting to the database: $pe");
      
    } catch (Exception $e) {
        
        throw new \Exception("Error connecting to the database: $e");
        
    } 

  }
  
  function __destruct () {
    
    // This is how we close the database handle in PDO...
    $this->handle = NULL;
    
  }


}
