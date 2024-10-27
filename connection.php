<?php

$servername = 'localhost';
$username = 'u686273261_project';
$password = 'G03_01ipss';
$db = 'u686273261_ipss';

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    exit('Connection failed: '.$conn->connect_error);
}
