<?php

require_once 'vendor/autoload.php';

if(file_exists(__DIR__.'/.env')) {
    $dotenv = Dotenv\Dotenv::create(__DIR__);
    $dotenv->overload();
}

$conn = new \PDO('mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));