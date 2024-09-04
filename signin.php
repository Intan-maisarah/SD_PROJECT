<?php
session_start();
include "connection.php"; // Ensure this file contains the database connection setup

if (isset($_POST['signin'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query to get the user with the provided username
    $sql = "SELECT * FROM users WHERE username='$username'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $hashed_password = $row['password'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Correct password, set session variables
            $_SESSION['signin'] = true;
            $_SESSION['username'] = $row['username'];
            header("Location: index.php"); // Redirect to the home page or dashboard
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
