<?php

$host = "sql113.infinityfree.com";
$username = "if0_42590968";
$password = "YOUR_INFINITYFREE_MYSQL_PASSWORD";
$database = "if0_42590968_jobconnect";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");

?>