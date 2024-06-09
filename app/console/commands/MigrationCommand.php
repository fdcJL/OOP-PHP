<?php

namespace App\Console\Commands;

use App\Database\Table;
use PDO;

class MigrationCommand {

    private static $pdo;
    private static $table;

    public static function setDatabase($dsn, $username, $password, $options = []) {
        self::$pdo = new PDO($dsn, $username, $password, $options);
        self::$table = new Table(self::$pdo);
    }

    public static function run($sql) {
        self::$pdo->exec($sql);
    }

    public static function migrate() {
        $migrationFiles = glob(__DIR__ . '/../../../src/migrations/*.php');
    
        foreach ($migrationFiles as $migrationFile) {
            require_once $migrationFile;
            $migration = require $migrationFile;
    
            if (!is_object($migration)) {
                echo "Error: Invalid migration file: $migrationFile\n";
                continue;
            }
    
            if (!method_exists($migration, 'up')) {
                echo "Error: Missing 'up' method in migration file: $migrationFile\n";
                continue;
            }

            $migration->down(self::$table);
            $migration->up(self::$table);
        }
    
        echo "Migrations completed successfully.\n";
    }

    public static function rollback() {
        $migrationFiles = array_reverse(glob(__DIR__ . '/../../../src/migrations/*.php'));

        foreach ($migrationFiles as $migrationFile) {
            $migration = require $migrationFile;
    
            if (!is_object($migration)) {
                echo "Error: Invalid migration file: $migrationFile\n";
                continue;
            }
    
            if (!method_exists($migration, 'up')) {
                echo "Error: Missing 'up' method in migration file: $migrationFile\n";
                continue;
            }
    
            $migration->down(self::$table);
        }

        echo "Rollback completed successfully.\n";
    }
}