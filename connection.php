<?php

//connection.php

$servername = "localhost";
$username = "project";
$password = "project1";
$db = "ipss";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
