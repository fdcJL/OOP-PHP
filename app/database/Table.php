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
        $stmt = $this->pdo->query("SHOW TABLES LIKE '{$tableName}'");
        $result = $stmt->fetch();

        if ($result) {
            // Drop the table if it exists
            $this->pdo->exec("DROP TABLE {$tableName}");
            echo "Dropped table if exists: $tableName\n";
        } else {
            echo "Table $tableName does not exist.\n";
        }
    }
}
