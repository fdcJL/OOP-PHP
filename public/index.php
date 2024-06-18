<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/core/bootstrap.php';
require_once __DIR__ . '/../app/lib/helpers.php';
require_once __DIR__ . '/../src/routes/web.php';

use App\Core\Router;
use App\Core\Request;

$router = Router::route();
$requestUri = Request::uri();
$requestMethod = Request::method();

if (strpos($requestUri, '/api') === 0) {
    $router->prefix('/api');
    require_once __DIR__ . '/../src/routes/api.php';
}

$router->dispatch($requestUri, $requestMethod);