<?php

namespace App\Console\Commands;

class CreateControllerCommand {
    public static function run($args) {
        if (count($args) < 1) {
            echo "Usage: php console make:controller <ControllerName>\n";
            exit(1);
        }

        $controllerName = ucfirst($args[0]);
        $migrationsDir = realpath(__DIR__ . "/../../../src/controller");
        if (!$migrationsDir) {
            echo "Controller directory not found.\n";
            exit(1);
        }

        $filename = "{$migrationsDir}/{$controllerName}.php";
        
        if (file_exists($filename)) {
            echo "Controller already exists: {$controllerName}\n";
            exit(1);
        }

        $content = "<?php\n\nnamespace Src\Controller;\n\n";
        $content .= "class {$controllerName} {\n";
        $content .= "    // Add your controller methods here\n";
        $content .= "}\n";

        file_put_contents($filename, $content);

        echo "Controller created successfully: {$controllerName}\n";
    }
}
