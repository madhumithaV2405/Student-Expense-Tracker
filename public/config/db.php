<?php

$host = "db01.dbhost.dev";
$username = "user_44zuwfc5n";
$password = "p44zuwfc5n";
$database = "db_44zuwfc5n";
$port = 5051;

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database,
    $port
);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>