<?php
//require_once 'vendor/autoload.php';
//require_once 'config/config.php';
//
//$seeder = new \Ecoride\Ecoride\database\Seeder();
//$seeder->run();

$path = "/eciride/profile";
echo $path . '?' . http_build_query(['nom' => 'OWE', 'prenom' => 'Stephane', 'pseudo'=> 'Coding237']);