<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "connection.php";

// Check if form is submitted
if (isset($_POST['InsertButton'])) {
    // Get form input data
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM customer WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists
        echo '<script>alert("This username is already in use, try another username");</script>';
        header("Location: view_profile.html");
        exit();
    } else {
        // Insert the data into the customer table
        $stmt = $conn->prepare("INSERT INTO customer (username, name, email, contact, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $name, $email, $contact, $address);
        $result = $stmt->execute();

        if ($result) {
            echo '<script>alert("Data successfully inserted!");</script>';
            header("Location: view_profile.html");
            exit();
        } else {
            // Display SQL error for debugging
            die("Error inserting data: " . $stmt->error);
        }
    }

    $stmt->close();
}

$conn->close();
?>
