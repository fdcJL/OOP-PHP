<?php
class connector{
    public $host = "localhost";					
    public $dbname = "database1";			
    public $name = "root";						
    public $pass = "root";
    function connect(){
        $conn = mysqli_connect("$this->host", "$this->name", "$this->pass","$this->dbname");
        if (!$conn)
        {
            die('Could not connect: ' . mysqli_connect_error());
        }
        $conn->set_charset("utf8");
        return $conn;
    }
}
?>