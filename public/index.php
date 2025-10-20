<?php

require_once '../vendor/autoload.php';
require_once '../config/config.php';

use Ecoride\Ecoride\Core\Router;
use Ecoride\Ecoride\Core\Database;
use Ecoride\Ecoride\Core\MongoManager;

// Initialisation de la base de donnees
$db = Database::getInstance();
$mongoDB = MongoManager::getInstance();

// Initialisation du Router
$router = new Router();

// Creation des Routes
$router->get('/', 'HomeController@index');
$router->get('/trajets', 'RideController@index');
$router->get('/trajets/recherche', 'RideController@search');
$router->post('/trajets/creer', 'RideController@create');
$router->get('/profil', 'UserController@profile');

$router->dispatch();
