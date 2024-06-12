<?php

namespace App\Console\Commands;

class MakeTableCommand {
    public static function run($argv) {
        if (count($argv) < 1) { // Check if enough arguments are provided
            echo "Usage: php console make:table <TableName>\n";
            exit(1);
        }

        $tableName = strtolower($argv[0]); // Use $argv[1] for the table name
        $timestamp = date('Y_m_d_His');
        $migrationsDir = realpath(__DIR__ . "/../../../src/migrations");

        if (!$migrationsDir) { // Check if the migrations directory exists
            echo "Table directory not found.\n";
            exit(1);
        }

        $filename = "{$migrationsDir}/{$timestamp}_create_{$tableName}_table.php";

        if (file_exists($filename)) {
            echo "Table already exists: {$filename}\n";
            exit(1);
        }

        $content = "<?php\n\n";
        $content .= "namespace Src\Migrations;\n\n";
        $content .= "return new class {\n";
        $content .= "    public function up(\$table) {\n";
        $content .= "        \$table->create('$tableName', function(\$column) {\n";
        $content .= "            \$column->id();\n";
        $content .= "            // Add your table columns here\n";
        $content .= "            \$column->timestamps();\n";
        $content .= "        });\n";
        $content .= "    }\n\n";
        $content .= "    public function down(\$table) {\n";
        $content .= "        \$table->dropIfExists('$tableName');\n";
        $content .= "    }\n";
        $content .= "};\n";

        file_put_contents($filename, $content);

        echo "Table created successfully: {$filename}\n";
    }
}