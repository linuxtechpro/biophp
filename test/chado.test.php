<?php
/**
 * Chado database tests
 */

// Load Bootstrap
require_once "../lib/Bootstrap.php";


Bootstrap::Initialize(0,"../etc/config.ini");

$config = biophp\Config::getInstance();

$config->getDatabaseProfilesList();




?>