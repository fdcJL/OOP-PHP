<?php
use Dotenv\Dotenv;
use App\Database\DB;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$dbconfig = include 'database.php';
$connection = $dbconfig['connections']['mysql'];

$dsn = "{$connection['driver']}:host={$connection['host']};
        dbname={$connection['database']};
        unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock";

DB::init($dsn, $connection['username'], $connection['password'], $connection['options']);