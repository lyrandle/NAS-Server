<?php
$host = '127.0.0.1';
$db = 'nas_credentials';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>

