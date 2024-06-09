<?php

namespace App\Core;

class Router {
    private static $instance = null;
    private $routes = [];

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

    private function addRoute($method, $uri, $action) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
        ];
    }

    public function dispatch($requestUri, $requestMethod) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && preg_match($this->convertToRegex($route['uri']), $requestUri, $matches)) {
                array_shift($matches);
                $this->callAction($route['action'], $matches);
                return;
            }
        }
        echo "404 Not Found";
    }

    private function convertToRegex($uri) {
        return '/^' . str_replace(['*', '/'], ['([a-zA-Z0-9_-]+)', '\/'], $uri) . '$/';
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
