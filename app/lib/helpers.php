<?php

class Response {
    protected $data;
    protected $statusCode;

    public function __construct() {
        $this->data = null;
        $this->statusCode = 200;
    }
    public function json($data = null, $statusCode = null) {
        if ($data !== null) {
            $this->data = $data;
        }
        if ($statusCode !== null) {
            $this->statusCode = $statusCode;
        }
        return $this;
    }

    public function send() {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        echo json_encode($this->data);
        exit;
    }
}

function response() {
    return new Response();
}

function view($name, $data = []) {
    $path = __DIR__ . '/../../src/views/' . $name . '.view.php';

    if (file_exists($path)) {
        extract($data);
        require $path;
        exit;
    } else {
        http_response_code(404);
        echo "View not found: " . htmlspecialchars($name);
        exit;
    }
}