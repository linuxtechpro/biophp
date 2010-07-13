<?php

/**
 * Imports pubmed publication information into a chado database.
 *
 */

// Get the options from the command line:

// -c Path to configuration file
$path = "c";

// -s Search string 
$search_string = "";

// -p Database profile from ini file
$profile = "";

// -l Library path to biophp
$lib_path = "";


$options = getopt("p:c::s:l::");

$required=TRUE;

if (isset($options['c'])) {

  $path = $options['c'];

} else {

  $path = "/etc/biophp/config.ini";

}

if (isset($options['s'])) {
  
  $search_string = $options['s'];

} else {

  $required = FALSE;

}

if (isset($options['p'])) {
  
  $profile = $options['p'];
  
} else {
  
  $required = FALSE;

}

if (isset($options['l'])) {
  
  $lib_path = $options['l'];
  
}

if($required==FALSE){
  echo "\nCommand Line Parameters, -l -c -s -p\n";
  echo "\n\n";
  echo "\t-p The required database profile to use from ini file\n";
  echo "\t-l The path to biophp\n";
  echo "\t-c Optional path to configuration file, defaults /etc/biophp/config.ini\n";
  echo "\t-s Required search entrez search string.\n";
  echo  "\t   See pubmed for more details on how to format you search strings\n\n\n";
  exit(0);
}

print $search_string . " \n";
print $profile . "\n";
print $path . "\n";
print $lib_path . "\n";

// Perform some magic and load the bootstrap.php file
if(isset($options['l'])) {
  if(file_exists($lib_path."/Bootstrap.php")) {
    
    require_once($lib_path."/Bootstrap.php");
    
  }
  
} else {
  
  if (file_exists("../lib/Bootstrap.php")) {
    
    require_once("../lib/Bootstrap.php");
    
  }
}

// 


