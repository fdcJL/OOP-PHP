<?php

namespace App\Database;

class Column {
    private $columns;

    public function __construct(&$columns) {
        $this->columns = &$columns;
    }

    public function id() {
        $this->columns[] = "id INT AUTO_INCREMENT PRIMARY KEY";
    }

    public function string($name) {
        $this->columns[] = "$name VARCHAR(255)";
    }

    public function unique() {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = "$lastColumn UNIQUE";
    }

    public function timestamp($name) {
        $this->columns[] = "$name TIMESTAMP";
    }

    public function nullable() {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = str_replace(' NOT NULL', '', $lastColumn);
    }

    public function rememberToken() {
        $this->columns[] = "remember_token VARCHAR(100)";
    }

    public function timestamps() {
        $this->columns[] = "created_at TIMESTAMP";
        $this->columns[] = "updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    }
}
