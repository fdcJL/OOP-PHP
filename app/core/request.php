<?php

namespace App\Core;

class Request {

    private $data;

    public function __construct() {
        $this->data = $_POST + $_GET + $this->getJsonData();
    }

    public static function uri() {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($uri, '?');
        if($position === false){
            return $uri;
        }
        return substr($uri, 0, $position);
    }

    public static function method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function input($key = null, $default = null) {
        if ($key === null) {
            return $this->data;
        }

        return $this->data[$key] ?? $default;
    }

    private function getJsonData() {
        $rawData = file_get_contents('php://input');
        $jsonData = json_decode($rawData, true);

        return is_array($jsonData) ? $jsonData : [];
    }
}
