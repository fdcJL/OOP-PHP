<?php
use App\Core\Router;
use Src\Controller\RegisteredController;

$router = Router::route();

$router->get('/', function() use ($db_status) {
    return view('index', ['db_status' => $db_status]);
});

$router->get('/store', [RegisteredController::class, 'index']);
$router->post('/register', [RegisteredController::class, 'store']);