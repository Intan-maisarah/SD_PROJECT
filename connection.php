<?php

$servername = 'localhost';
$username = 'project';
$password = 'G03_01ipss';
$db = 'ipss';

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    exit('Connection failed: '.$conn->connect_error);
}
