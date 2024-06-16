<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Console\Commands\MigrationCommand;

class DB {
    private static $con = null;
    private $table;
    private $selected = '*';
    private $fields = '';
    private $joins = '';
    private $groupBy = '';
    private $orderBy = '';

    private function __construct($table = null) {
        $this->table = $table;
    }

    public static function init($conn, $dsn) {
        try {
            self::$con = new PDO($dsn, $conn['username'], $conn['password'], $conn['options']);
            self::$con->exec("SET NAMES {$conn['charset']} COLLATE {$conn['collation']}");
            MigrationCommand::setDatabase(self::$con, $conn);
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
    
    public function select($columns = '*') {
        $this->selected = $columns;
        return $this;
    }

    public function where($condition) {
        $this->fields = $condition;
        return $this;
    }
    
    public function leftJoin($table, $condition) {
        $this->joins .= " LEFT JOIN {$table} ON {$condition}";
        return $this;
    }

    public function rightJoin($table, $condition) {
        $this->joins .= " RIGHT JOIN {$table} ON {$condition}";
        return $this;
    }

    public function innerJoin($table, $condition) {
        $this->joins .= " INNER JOIN {$table} ON {$condition}";
        return $this;
    }
    
    public function groupBy($columns) {
        if (is_array($columns)) {
            $this->groupBy = implode(', ', $columns);
        } else {
            $this->groupBy = $columns;
        }
        return $this;
    }

    public function orderBy($columns) {
        if (is_array($columns)) {
            $this->orderBy = implode(', ', $columns);
        } else {
            $this->orderBy = $columns;
        }
        return $this;
    }

    public function get() {
        $sql = "SELECT {$this->selected} FROM {$this->table}";
        if (!empty($this->joins)) {
            $sql .= $this->joins;
        }
        if(!empty($this->fields)){
            $sql .= " WHERE {$this->fields}";
        }
        if (!empty($this->groupBy)) {
            $sql .= " GROUP BY {$this->groupBy}";
        }
        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY {$this->orderBy}";
        }
        try {
            $stmt = self::$con->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }

    public function insert(array $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        
        $stmt = self::$con->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        return $stmt->execute(array_values($data));
    }
}
