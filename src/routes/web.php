<?php

use App\Core\Router;
use Src\Controller\RegisteredController;

$router = Router::route();

$router->get('/', function() {
    echo "Welcome to the Home Page!";
});
$router->get('/store', [RegisteredController::class, 'index']);
$router->post('/register', [RegisteredController::class, 'store']);