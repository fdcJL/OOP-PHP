<?php

namespace App\Console\Commands;

use App\Database\Table;

require_once __DIR__ . '/../../config/bootstrap.php';

class MigrationCommand {
    private static $table;
    private static $con;

    public static function setDatabase($conn, $con) {
        self::$con = $con;
        self::$table = new Table($conn, $con['engine']);
        self::createMigrationsTable(self::$con);
    }

    private static function createMigrationsTable($con) {
        $query = "
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                created_at TIMESTAMP
            ) ENGINE='{$con['engine']}';
        ";
        self::$table->getConnection()->exec($query);
    }

    public static function migrate() {
        $migrationFiles = glob(__DIR__ . '/../../../src/migrations/*.php');
        $batch = self::getCurrentBatch() + 1;
    
        $fileList = [];
        foreach ($migrationFiles as $migrationFile) {
            $migration = require $migrationFile;
            
            if(!self::$table){
                echo "Database is off";
                return;
            }else{
                if (is_object($migration) && method_exists($migration, 'down')) {
                    $filename = basename($migrationFile);
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
        
        $checkLastBatch = self::checkLastBatch($lastBatch);
        
        if (empty($lastBatch)) {
            echo "No migrations to rollback.\n";
            return;
        }else{
            foreach ($migrationFiles as $migrationFile) {
                $migration = require $migrationFile;
                
                $filename = basename($migrationFile);
                preg_match('/create_(.*?)_table\.php/', $filename, $matches);
                $tableName = $matches[1];
                
                if (is_object($migration) && method_exists($migration, 'down')) {
                    foreach($checkLastBatch as $lastBatch){
                        if($lastBatch['migration'] == $filename){
                            
                            $migration->down(self::$table);
                            
                            $query = "DELETE FROM migrations WHERE migration = :migration";
                            $stmt = self::$table->getConnection()->prepare($query);
                            $stmt->execute(['migration' => $filename]);
                            echo "Rollback migration for table: $tableName\n";
                        };
                    }
                }
            }
        }
        echo "\nRollback completed successfully.\n";
    }

    public static function fresh() {
        $query = "TRUNCATE TABLE migrations";
        self::$table->getConnection()->exec($query);

        $batch = self::getCurrentBatch() + 1;

        $migrationFiles = array_reverse(glob(__DIR__ . '/../../../src/migrations/*.php'));

        echo "Dropping All Tables\n\n";

        foreach ($migrationFiles as $migrationFile) {
            $migration = require $migrationFile;

            $filename = basename($migrationFile);
            preg_match('/create_(.*?)_table\.php/', $filename, $matches);
            $tableName = $matches[1];

            if (is_object($migration) && method_exists($migration, 'down')) {
                $migration->down(self::$table);
                $migration->up(self::$table);
                self::recordMigration($filename, $batch);
                echo "run migration for table: $tableName\n";
            }
        }
        echo "\nMigrations completed successfully.";
    }
    
    public static function truncate($tablename) {
        $query = "SHOW TABLES LIKE '{$tablename}'";
        $stmt = self::$table->getConnection()->query($query);
        $result = $stmt->fetch();
        
        if ($result) {
            $truncateTable = "TRUNCATE TABLE `{$tablename}`";
            self::$table->getConnection()->exec($truncateTable);
            echo "Truncate table $tablename completed successfully.";
        } else {
            echo "Table $tablename does not exist.\n";
        }
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
    
    private static function checkLastBatch($lastBatch){
        $query = "SELECT migration FROM migrations WHERE batch = :batch";
        $stmt = self::$table->getConnection()->prepare($query);
        $stmt->execute(['batch' => $lastBatch]);
        $result = $stmt->fetchAll();
        return $result;
    }
    
    private static function tableExists($filename) {
        $query = "SELECT * FROM migrations WHERE migration = :migration";
        $stmt = self::$table->getConnection()->prepare($query);
        $stmt->execute(['migration' => $filename]);
        $result = $stmt->fetch();
        return $result !== false;
    }
}