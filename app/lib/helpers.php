<?php
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