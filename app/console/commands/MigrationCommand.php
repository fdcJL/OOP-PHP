<?php

namespace App\Console\Commands;

use App\Database\Table;

require_once __DIR__ . '/../../config/bootstrap.php';

class MigrationCommand {
    private static $table;

    public static function setDatabase($conn, $engine) {
        self::$table = new Table($conn, $engine);
        self::createMigrationsTable();
    }

    private static function createMigrationsTable() {
        $query = "
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                created_at TIMESTAMP
            );
        ";
        self::$table->getConnection()->exec($query);
    }

    public static function migrate() {
        $migrationFiles = glob(__DIR__ . '/../../../src/migrations/*.php');
        $batch = self::getCurrentBatch() + 1;
    
        $fileList = [];
        foreach ($migrationFiles as $migrationFile) {
            require_once $migrationFile;
            $migration = require $migrationFile;
            
            if(!self::$table){
                echo "Database is off";
                return;
            }else{
                if (is_object($migration) && method_exists($migration, 'down')) {
                    $filename = basename($migrationFile);
                    // preg_match('/create_(.*?)_table\.php/', $filename, $matches);
                    // $tableName = $matches[1];
                    if (self::tableExists($filename)) {
                        continue;
                    }
                    $migration->up(self::$table);
                    self::recordMigration($filename, $batch);
                    $fileList[] = $filename;
                }
            }
        }
        
        if(!empty($fileList)){
            foreach ($fileList as $file) {
                echo $file . PHP_EOL;
            }
            echo "\nMigrations completed successfully.\n";
        }else{
            echo "\nNothing to Migrate.\n";
        }
    }

    public static function rollback() {
        $migrationFiles = array_reverse(glob(__DIR__ . '/../../../src/migrations/*.php'));
        
        $lastBatch = self::getCurrentBatch();
        
        if ($lastBatch === 0) {
            echo "No migrations to rollback.\n";
            return;
        }
        
        // Get migration files for the last batch
        $query = "SELECT migration FROM migrations WHERE batch = :batch ORDER BY id DESC";
        $stmt = self::$table->getConnection()->prepare($query);
        $stmt->execute(['batch' => $lastBatch]);
        $migrations = $stmt->fetchAll();

        if (!$migrations) {
            echo "No migrations found for the last batch.\n";
            return;
        }
    
        foreach ($migrationFiles as $migrationFile) {
            require_once $migrationFile;
            $migration = require $migrationFile;
      
            if (is_object($migration) && method_exists($migration, 'down')) {
                $filename = basename($migrationFile);
                preg_match('/create_(.*?)_table\.php/', $filename, $matches);
                $tableName = $matches[1];
                
                $query = "DELETE FROM migrations WHERE batch = :batch";
                $stmt = self::$table->getConnection()->prepare($query);
                $stmt->execute(['batch' => $lastBatch]);
        
                if (empty($tableName)) {
                    echo "Error: Unable to extract table name from filename: $filename\n";
                    continue;
                }
        
                if (self::tableExists($filename)) {
                    $migration->down(self::$table);
                    echo "Rolled back migration for table: $tableName\n";
                } else {
                    echo "Table '{$tableName}' does not exist. Skipping rollback for {$migrationFile}.\n";
                }
            }
        }


        echo "Rollback of batch $lastBatch completed successfully.\n";
    }

    private static function getCurrentBatch() {
        $query = "SELECT MAX(batch) as batch FROM migrations";
        $stmt = self::$table->getConnection()->query($query);
        $result = $stmt->fetch();
        return $result ? $result['batch'] : 0;
    }

    private static function recordMigration($filename, $batch) {
        $query = "INSERT INTO migrations (migration, batch, created_at) VALUES (:migration, :batch, NOW())";
        $stmt = self::$table->getConnection()->prepare($query);
        $stmt->execute(['migration' => $filename, 'batch' => $batch]);
    }
    
    private static function tableExists($filename) {
        $query = "SELECT * FROM migrations WHERE migration = :migration";
        $stmt = self::$table->getConnection()->prepare($query);
        $stmt->execute(['migration' => $filename]);
        $result = $stmt->fetch();
        return $result !== false;
    }
}