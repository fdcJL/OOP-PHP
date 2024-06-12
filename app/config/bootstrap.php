<?php
use Dotenv\Dotenv;
use App\Database\DB;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$dbconfig = include 'database.php';
$default = $dbconfig['default'];
$connection = $dbconfig['connections'][$default];

$dsn = "{$connection['driver']}:host={$connection['host']};
        dbname={$connection['database']};charset={$connection['charset']};
        unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock";
$db_status = DB::init($connection, $dsn);
?>