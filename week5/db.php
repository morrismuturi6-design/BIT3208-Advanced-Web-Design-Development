<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "la_maison_luxe";

$serverConnection = new mysqli($host, $user, $password);

if ($serverConnection->connect_error) {
    die("Database server connection failed: " . $serverConnection->connect_error);
}

$serverConnection->query("CREATE DATABASE IF NOT EXISTS `$database`");
$serverConnection->close();

$connection = new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    die("Database connection failed: " . $connection->connect_error);
}

$connection->query(
    "CREATE TABLE IF NOT EXISTS customers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(80) NOT NULL,
        phone VARCHAR(30) NOT NULL,
        email VARCHAR(120) NOT NULL,
        gender VARCHAR(30) NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
);

$passwordColumn = $connection->query("SHOW COLUMNS FROM customers LIKE 'password_hash'");
if ($passwordColumn && $passwordColumn->num_rows === 0) {
    $connection->query("ALTER TABLE customers ADD password_hash VARCHAR(255) NOT NULL DEFAULT '' AFTER gender");
}

$connection->query(
    "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_id INT NOT NULL,
        meal_name VARCHAR(120) NOT NULL,
        order_comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
    )"
);
?>
