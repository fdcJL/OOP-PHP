<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Console\Commands\MigrationCommand;

class DB {
    private static $connection = null;

    public static function init($dsn, $username, $password, $options = []) {
        try {
            self::$connection = new PDO($dsn, $username, $password, $options);
            // MigrationCommand::setDatabase($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function connection() {
        return self::$connection;
    }

    public static function table($table) {
        return new static($table);
    }

    private $table;

    private function __construct($table) {
        $this->table = $table;
    }

    public function get() {
        $stmt = self::$connection->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(array $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        
        $stmt = self::$connection->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        return $stmt->execute(array_values($data));
    }
}
