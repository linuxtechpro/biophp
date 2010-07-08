<?php
namespace biophp;

/**
 * Configuration class that is responsible for reading the configuration file and loading
 * it into a singleton object for other classes to use.  We want to use a singleton
 * pattern to avoid parsing and reading the configuration file everytime we need to
 * obtain information.
 * @author Randall Svancara
 */

class Config {

  /**
   * Contains instance of Config object to return to the
   * calling function
   * @static
   */
  private static $bioconfig_instance;
  
  /**
   * database profile to use
   */
  
  private $dbprofile;
  
  
  private $config_array;

  /**
   * Create a private constructor
   * 
   */
  private function __construct() {
    // Do nothing
  }


  /**
   * Get the current instance of bioconfig
   * @return Config A bioconfig object 
   */
  public static function getInstance() {
  
    if (!self::$bioconfig_instance) {
      
      self::$bioconfig_instance = new Config();
      
    } 

    return self::$bioconfig_instance; 

  }

  /**
   * Initialize the configuration.
   * Throws an exception if there is a problem.  Up to caller
   * to catch it.  
   * @param String path to the configuration file
   */
  public function initialize($path="/etc/biophp/config.ini") {
    
    $config = array();
    
    if(file_exists($path)){
      
      try {
      
        $this->config_array = parse_ini_file($path, TRUE);
    
      }catch(Exception $E) {
        
        throw new \Exception("Error parsing INI file located at $path with the error: " . $E);
        
      }
      
    } else {
      
      throw new \Exception("Could not locate configuration file at $path");
      
    }

  }
  
  /**
   * Obtain a list of database profiles from the configuration ini file
   * @return Array Returns an array of strings containing all the database profiles
   */
  public function getDatabaseProfilesList() {
    
    $ret_array = array();
    
    $i = 0;
    
    foreach($this->config_array as $key => $value) {
      
      if ($this->config_array[$key]["type"]) {
        
        if ($this->config_array[$key]["type"] == "db") {
          
          $ret_array[$i] = $key;
          
          $i++;
          
        }
        
      }
      
    }
    
    return $ret_array;
    
  }
  
  /**
   * Get a database profile from the ini file
   * @param String $profile The profile name, [profile name]
   * @return Array An array of the database configuration options
   */
  public function getDatabaseProfile($profile) {

    $ret_array = array();

    foreach($this->config_array as $key => $value) {
      
      if($key == $profile) {

        if ($this->config_array[$key]["type"]) {
        
          if ($this->config_array[$key]["type"] == "db") {
          
            $ret_array[$key] = $value;
          
          } else {
            
            throw new \Exception("This profile is not a database profile");
            
          }
        
        }        

      }

    }   
    
    return $ret_array;

  }
  
  /**
   * Get the DSN for the provided profile
   * @param String $profile Profile name
   * @return String DSN
   */
  public function getDSNforProfile ($profile) {
    
    $dsn = null;
    
    $config_array = $this->getDatabaseProfile($profile);
    
    if (is_array($config_array)) {
      
      $dsn = $config_array[$profile]["prefix"] . ":" .
             "host=" . $config_array[$profile]["host"] . ";" .
             "port=" . $config_array[$profile]["port"] . ";" .
             "dbname=" . $config_array[$profile]["database"] . ";" .
             "user=" . $config_array[$profile]["user"] . ";" .
             "password=" . $config_array[$profile]["pass"];
             
    }
    
    return $dsn;
        
  }
  
  
  
  /**
   * Magic setter
   */
  public function __set($name, $value) {

    $this->$name = $value;
    
  }
  
  /**
   * Magic getter
   */
  public function __get($name) {
    
    if(isset($this->$name)) {
      
      return $this->$name;
    
    } else {
      
      throw new \Exception("Unable to find property $name in Config class.");
      
    }
    
  
  }

}
