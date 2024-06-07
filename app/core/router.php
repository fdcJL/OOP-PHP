<?php
namespace App\Core;

class Router {
    private $routes = [];

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
        if (is_callable($action)) {
            call_user_func_array($action, $params);
        } elseif (is_array($action)) {
            list($controller, $method) = $action;
            if (class_exists($controller) && method_exists($controller, $method)) {
                call_user_func_array([new $controller, $method], $params);
            } else {
                echo "404 Not Found";
            }
        } else {
            echo "404 Not Found";
        }
    }
}
