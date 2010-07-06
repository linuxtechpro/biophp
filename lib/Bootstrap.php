<?php

// Use php for the default extension
spl_autoload_extensions(".php");
// What function to call to load classess auto-magically
spl_autoload_register(__NAMESPACE__ .'\Bootstrap::getClass');

/**
 *  A class that initializes this framework.  It must
 *  be called prior to using any of the classes.
 * 
 *  This class will start the php auto loader which allows
 *  PHP to obtain a class without typing require_once for ever
 *  class file you want to load.  You simply call the class
 *  with the appropriate namespace and PHP will include the file
 *  at runtime.  
 *
 */
class Bootstrap {
  
  /**
   * Private constructor, prevents the class
   * from being instantiated
   */
  private function __construct() {
    
  }
  
  
  /**
   *  Special static function that initializes the framework.  Allways
   *  call this class first.  Lots of good things will happen.  
   */
  public static function Initialize($debuglevel=0,$configpath='/etc/biophp/config.ini') {

    $config = null;
    try{
      $config = biophp\Config::singleton();
    }catch(Exception $e) {
      print "Error loading $e";
    }   
    $config->initialize($configpath); 
  }
  
  
  /**
   * This is a special magical function that is
   * defined by spl_autoload_register to load
   * classes at run time.  It works by simply taking
   * the namespace of the class, which must correspond to
   * the directory structure of this library and converting it
   * to a file system path that can then be used in require_once
   *
   * The beauty of this function is that classes (files) are only loaded as
   * needed.  
   */
  public static function getClass($classname) {  
    $classfile = __DIR__."/".str_replace('\\','/',$classname).".php";
    if (is_file("$classfile")) {
      require_once($classfile);
    } else {
      throw new \Exception("Unable to load class $classname in file $classfile");
    }
  }
}

?>
