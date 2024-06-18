<?php

namespace App\Database;

class Column {
    public $columns;

    public function __construct(&$columns) {
        $this->columns = &$columns;
    }

    public function id() {
        $this->columns[] = "id INT AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function int($name) {
        $this->columns[] = "$name INT(11)";
        return $this;
    }

    public function string($name, $length = 255) {
        $this->columns[] = "$name VARCHAR($length)";
        return $this;
    }

    public function text($name) {
        $this->columns[] = "$name TEXT";
        return $this;
    }

    public function time($name) {
        $this->columns[] = "$name TIME";
        return $this;
    }

    public function date($name) {
        $this->columns[] = "$name DATE";
        return $this;
    }

    public function year($name) {
        $this->columns[] = "$name YEAR";
        return $this;
    }

    public function double($name, $precision = null, $scale = null) {
        if(is_null($precision) && is_null($scale)){
            $this->columns[] = "$name DOUBLE";
        }else{
            $this->columns[] = "$name DOUBLE($precision,$scale)";
        }
        return $this;
    }

    public function decimal($name, $precision = null, $scale = null) {
        if (is_null($precision) && is_null($scale)) {
            $this->columns[] = "$name DECIMAL";
        } else {
            $this->columns[] = "$name DECIMAL($precision,$scale)";
        }
        return $this;
    }

    public function datetime($name) {
        $this->columns[] = "$name DATETIME";
        return $this;
    }

    public function timestamp($name) {
        $this->columns[] = "$name TIMESTAMP";
        return $this;
    }

    public function index($column, $name = null) {
        $this->columns[] = "INDEX $name ($column)";
        return $this;
    }

    public function unique() {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = "$lastColumn UNIQUE";
        return $this;
    }

    public function nullable() {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = "$lastColumn NOT NULL";
        return $this;
    }

    public function foreignId($name, $referenceTable) {
        $this->columns[] = "FOREIGN KEY ($name) REFERENCES $referenceTable(id)";
        return $this;
    }

    public function onDelete($action) {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = "$lastColumn ON DELETE $action";
        return $this;
    }

    public function onUpdate($action) {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = "$lastColumn ON UPDATE $action";
        return $this;
    }

    public function timestamps() {
        $this->columns[] = "created_at TIMESTAMP";
        $this->columns[] = "updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }
}
