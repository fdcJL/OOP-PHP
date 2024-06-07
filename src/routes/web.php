<?php
use App\Core\Router;
use App\Controller\RegisteredController;

$router = new Router();

$router->get('/', function() {
    echo "Welcome to the Home Page!";
});

$router->get('/register', [RegisteredController::class, 'store']);
