<?php

require_once '../vendor/autoload.php';
require_once '../config/config.php';
require_once '../src/lib/helpers.php';

use Ecoride\Ecoride\Core\Router;
use Ecoride\Ecoride\Core\Database;
use Ecoride\Ecoride\Core\MongoManager;
use Whoops\run;
use Whoops\Handler\PrettyPageHandler;

$whoops = new Run();
$whoops->pushHandler(new PrettyPageHandler);

$envirenment = $_ENV['APP_ENV'] ?? 'development';
if ($envirenment = 'development') {

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    $whoops->register();
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

$whoops->register();


// Initialisation de la base de donnees
$db = Database::getInstance();
$mongoDB = MongoManager::getInstance();

try {
    // Initialisation du Router
    $router = new Router();

// Creation des Routes
    $router->get('/', 'HomeController@index');
    $router->get('/login', 'AuthController@login');
    $router->post('/login/handle', 'AuthController@handle_login');
    $router->get('/register', 'AuthController@register');
    $router->post('/register/handle', 'AuthController@handle_register');
    $router->get('/logout', 'AuthController@logout');
    $router->get('/profil', 'UserController@profile');

//$router->get('/trajets', 'RideController@index');
//$router->get('/trajets/recherche', 'RideController@search');
//$router->post('/trajets/creer', 'RideController@create');

    $router->dispatch();
} catch (Throwable $e) {
    if ($envirenment = 'development') {
        throw $e;
    } else {
        error_log("Erreur: {$e->getMessage()} dans {$e->getFile()} : {$e->getLine()}");
        http_response_code(500);
        echo "Une erreur s'est produite. Notre equipe y travaille.";
    }
}


