<?php

class Response {
    protected $data;

    public function __construct() {
        $this->data = null;
    }
    public function json($data = null) {
        if (!is_null($data)) {
            $this->data = $data;
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($this->data);
            exit;
        }
        return $this;
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

function auth(){
    print_r('Hello');
}