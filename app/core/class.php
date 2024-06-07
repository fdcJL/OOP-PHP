<?php

class Query{
		
    public $table;	
    public $fields;
    public $values;
    public $selected;
    public $delimeter;

    /**************************************************************************************
    ***************************************************************************************
    *********************************INSERT QUERY STATEMENT*******************************/	
    public function exe_INSERT($con){
        $quer_y = "INSERT INTO ".$this->table." (".$this->selected.") VALUES (".$this->fields.")";
        return mysqli_query($con, $quer_y);
    }
    /**************************************************************************************
    ***************************************************************************************
    *********************************SELECT QUERY STATEMENT*******************************/	
    public function exe_SELECT($con){
        $quer_y = "SELECT ".$this->selected." FROM ".$this->table." WHERE ".$this->fields;
        return mysqli_query($con, $quer_y);
    }
    /**************************************************************************************
    ***************************************************************************************
    *********************************UPDATE QUERY STATEMENT*******************************/	
    public function exe_UPDATE($con){
        $quer_y = "UPDATE ".$this->table." SET ".$this->selected." WHERE ".$this->fields;
        return mysqli_query($con, $quer_y);
    }
    /**************************************************************************************
    ***************************************************************************************
    *********************************DELETE QUERY STATEMENT*******************************/	
    public function exe_DELETE($con){
        $quer_y = "DELETE FROM ".$this->table." WHERE ".$this->fields;
        return mysqli_query($con, $quer_y);
    }
    /**************************************************************************************
    ***************************************************************************************
    *********************************TRUNCATE QUERY STATEMENT*******************************/	
    public function exe_TRUNCATE($con){
        $quer_y = "TRUNCATE TABLE ".$this->table;
        return mysqli_query($con, $quer_y);
    }			
}

?>