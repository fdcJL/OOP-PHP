<?php
use Dotenv\Dotenv;
use App\Database\DB;
use App\Console\Commands\MigrationCommand;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$dsn = "".$_ENV['DB_CONNECTION'].":host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_DATABASE']."";
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

DB::init($dsn, $username, $password, $options);

MigrationCommand::setDatabase($dsn, $username, $password, $options);