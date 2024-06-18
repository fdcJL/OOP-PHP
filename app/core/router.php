<?php

namespace App\Core;

class Router {
    private static $instance = null;
    private $routes = [];
    private $prefix = '';

    private function __construct() {}

    public static function route() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($uri, $action) {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action) {
        $this->addRoute('POST', $uri, $action);
    }

    public function prefix($prefix) {
        $this->prefix = trim($prefix, '/');
    }

    private function addRoute($method, $uri, $action) {
        $uri = '/' . trim($this->prefix . '/' . trim($uri, '/'), '/');
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
        ];
    }

    public function dispatch($requestUri, $requestMethod) {
        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['uri']);
            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);
                $this->callAction($route['action'], $matches);
                return;
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }

    private function convertToRegex($uri) {
        return '#^' . preg_replace('#\(/\)#', '/?', preg_quote($uri, '#')) . '$#';
    }

    private function callAction($action, $params = []) {
        $request = new Request();

        if (is_callable($action)) {
            call_user_func_array($action, array_merge([$request], $params));
        } elseif (is_array($action)) {
            list($controller, $method) = $action;
            if (class_exists($controller)) {
                $controllerInstance = new $controller;
                if (method_exists($controllerInstance, $method)) {
                    call_user_func_array([$controllerInstance, $method], array_merge([$request], $params));
                } else {
                    echo "404 Not Found - Method $method does not exist in controller $controller";
                }
            } else {
                echo "404 Not Found - Controller $controller does not exist";
            }
        } else {
            echo "404 Not Found - Action is not callable";
        }
    }
}
