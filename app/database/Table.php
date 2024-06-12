<?php

namespace App\Database;

use PDO;

class Table {
    private $pdo;
    private $engine;

    public function __construct(PDO $pdo, $engine) {
        $this->pdo = $pdo;
        $this->engine = $engine;
    }
    
    public function getConnection() {
        return $this->pdo;
    }

    public function create($tableName, $callback) {
        $columns = [];
        $columnBuilder = new Column($columns);
        $callback($columnBuilder);

        $columnsSql = implode(", ", $columns);
        $sql = "CREATE TABLE $tableName ($columnsSql) ENGINE='{$this->engine}'";
        $this->pdo->exec($sql);
    }

    public function dropIfExists($tableName) {
        $sql = "DROP TABLE IF EXISTS $tableName";
        $this->pdo->exec($sql);
    }
}
