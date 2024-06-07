<?php
// public/index.php

require_once '../autoload.php';

use App\Core\Request;
use App\Core\Router;

require_once '../src/routes/web.php';

$router->dispatch(Request::uri(), Request::method());