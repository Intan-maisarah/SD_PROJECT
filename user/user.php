<?php

include "../connection.php"; 

function getUserType($conn, $username) {
    $sql = "SELECT userType FROM users WHERE username='$username'";
    $res = mysqli_query($conn, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        return $row['userType'];
    } else {
        return null; // or handle the error
    }
}


?>

