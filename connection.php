<?php
$servername = "localhost";
$username = "project";
$password = "project1";
$db = "ipss";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
