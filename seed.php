<?php
require_once 'vendor/autoload.php';
require_once 'config/config.php';

$seeder = new \Ecoride\Ecoride\database\Seeder();
$seeder->run();