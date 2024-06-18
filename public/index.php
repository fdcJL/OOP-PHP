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

$router->dispatch($requestUri, $requestMethod);