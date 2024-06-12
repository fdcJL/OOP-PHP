<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Console\Commands\MigrationCommand;

class DB {
    private static $con = null;

    public static function init($conn, $dsn) {
        try {
            self::$con = new PDO($dsn, $conn['username'], $conn['password'], $conn['options']);
            self::$con->exec("SET NAMES {$conn['charset']} COLLATE {$conn['collation']}");
            MigrationCommand::setDatabase(self::$con, $conn['engine']);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public static function connection() {
        return self::$con;
    }

    public static function table($table) {
        return new static($table);
    }

    private $table;

    private function __construct($table) {
        $this->table = $table;
    }

    public function get() {
        $stmt = self::$con->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(array $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        
        $stmt = self::$con->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        return $stmt->execute(array_values($data));
    }
}
