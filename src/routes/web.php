<?php
use App\Core\Router;
use Src\Controllers\TestController;

$router = Router::route();

$router->get('/', function() use ($db_status) {
    return view('index', ['db_status' => $db_status]);
});

$router->get('/store', [TestController::class, 'index']);
$router->get('/sample', [TestController::class, 'sample']);