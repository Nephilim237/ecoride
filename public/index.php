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
$whoops->pushHandler(new PrettyPageHandler());

$environment = $_ENV['APP_ENV'] ?? 'developement';
if ($environment === 'developement') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    $whoops->register();
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Initialisation de la base de donnees
$db = Database::getInstance();
$mongoDB = MongoManager::getInstance();


try {
// Initialisation du Router
    $router = new Router();

// Creation des Routes
    $router
        ->get('/', 'HomeController@index')
        ->get('/register', 'AuthController@register')
        ->post('/register/handle', 'AuthController@handle_register')
        ->get('/login', 'AuthController@login')
        ->post('/login/handle', 'AuthController@handle_login')
        ->get('/profile', 'UserController@profile')
        ->get('/become-partner', 'PartnerController@become_partner')
        ->post('/become-partner/handle', 'PartnerController@handle_become_partner')
        ->get('/logout', 'AuthController@logout');

//$router->get('/trajets/recherche', 'RideController@search');
//$router->get('/trajets', 'RideController@index');
//$router->post('/trajets/creer', 'RideController@create');
//$router->get('/profil', 'UserController@profile');

    $router->dispatch();
} catch(Throwable $e) {
    if ($environment === 'developement') {
        throw $e;
    } else {
        error_log("Erreur: {$e->getMessage()} dans {$e->getFile()} : {$e->getLine()}");
        http_response_code(500);
        echo "Une erreur s'est preoduites. Notre equipe y travaille";
    }

}