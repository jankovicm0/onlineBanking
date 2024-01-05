<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "online_banking";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>