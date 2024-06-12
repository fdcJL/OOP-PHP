<?php

namespace App\Console\Commands;

use App\Database\Table;

require_once __DIR__ . '/../../config/bootstrap.php';

class MigrationCommand {
    private static $table;

    public static function setDatabase($conn, $engine) {
        self::$table = new Table($conn, $engine);
    }

    public static function migrate() {
        $migrationFiles = glob(__DIR__ . '/../../../src/migrations/*.php');
    
        $fileList = [];
        foreach ($migrationFiles as $migrationFile) {
            require_once $migrationFile;
            $migration = require $migrationFile;
            
            if(!self::$table){
                echo "Database is off";
                return;
            }else{
                if (!is_object($migration)) {
                    echo "Error: Invalid migration file: $migrationFile\n";
                    continue;
                }
        
                if (!method_exists($migration, 'up')) {
                    echo "Error: Missing 'up' method in migration file: $migrationFile\n";
                    continue;
                }

                $filename = basename($migrationFile);
                preg_match('/create_(.*?)_table\.php/', $filename, $matches);
                $tableName = $matches[1];

                if (self::tableExists($tableName)) {
                    continue;
                }

                $fileList[] = array(
                    'filename' => $filename,
                );

                $migration->up(self::$table);

            }
        }
        
        if(!empty($fileList)){
            foreach ($fileList as $file) {
                echo $file['filename'] . PHP_EOL;
            }
            echo "\nMigrations completed successfully.\n";
        }else{
            echo "\nNothing to Migrate.\n";
        }
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

    private static function tableExists($tableName) {
        $query = "SHOW TABLES LIKE '$tableName'";
        $stmt = self::$table->getConnection()->query($query);
        $result = $stmt->fetch();
    
        return $result !== false;
    }
}