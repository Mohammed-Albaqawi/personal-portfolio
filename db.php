<?php
declare(strict_types=1);

// Change these values to match your local MySQL setup.
$dbHost = 'localhost';
$dbName = 'personal_portfolio';
$dbUser = 'root';
$dbPassword = '';

$dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
$pdo = new PDO($dsn, $dbUser, $dbPassword, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
