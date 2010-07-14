<?php
/**
 * Chado database tests
 */

// Load Bootstrap
require_once "../lib/Bootstrap.php";

Bootstrap::Initialize(0,"/etc/biophp/config.ini");

$config = biophp\Config::getInstance();

// Get a list of database profiles
$config_array = $config->getDatabaseProfilesList();
echo var_dump($config_array);

// Get information about a profile
$config_array = $config->getDatabaseProfile("database_profile");
echo var_dump($config_array);

// Set the default profile to use.
$config->dbprofile = "database_profile";

print "\nThe default profile is: " .  $config->dbprofile . "\n";

$result = biophp\db\Database::getConnection()
  ->query("select * from pub",array(),array());


while($row = $result->fetchObject()) {
  print $row->pub_id . "\n";
}

//$statement = new biophp\db\DatabaseStatementBase($conn);

//$statement->execute();

//$conn->query("select * from pub");


//$pub = new biophp\db\chado\model\Pub();



?>