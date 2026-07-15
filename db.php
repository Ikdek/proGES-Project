<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'progesdb';
$conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
?>