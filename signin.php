<?php
session_start();
include "connection.php"; 

if (isset($_POST['signin'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {

        $row = mysqli_fetch_assoc($res);
        $password = $row['password'];

        if (password_verify($pass, $password)) {
            // Correct password, set session variables
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            // Incorrect password
            echo '<script>alert("Wrong Password");window.location.href="signin.html";</script>';
        }

    } else {
        // No user found with the entered username
        echo '<script>alert("Wrong Username or Password");window.location.href="signin.html";</script>';
    }
} else {
    // Redirect if the form was not submitted
    header("Location: signin.html");
    exit();
}
?>
