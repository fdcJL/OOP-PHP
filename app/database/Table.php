<?php

namespace App\Database;

use PDO;

class Table {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create($tableName, $callback) {
        $columns = [];
        $columnBuilder = new Column($columns);
        $callback($columnBuilder);

        $columnsSql = implode(", ", $columns);
        $sql = "CREATE TABLE $tableName ($columnsSql)";
        $this->pdo->exec($sql);
    }

    public function dropIfExists($tableName) {
        $sql = "DROP TABLE IF EXISTS $tableName";
        $this->pdo->exec($sql);
    }
}
