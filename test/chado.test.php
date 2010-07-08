<?php
/**
 * Chado database tests
 */

// Load Bootstrap
require_once "../lib/Bootstrap.php";

Bootstrap::Initialize(0,"../etc/config.ini");

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

$pubdb = new biophp\db\chado\PublicationDAO();



?>