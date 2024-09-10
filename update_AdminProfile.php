<?php

ini_set('display_errors', 1);
ini_set('display_startup errors', 1);
error_reporting(E_ALL);

ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\htdocs\PROJECT_TRY\error_log.txt');

// Include the database connection file
include 'C:/xampp/htdocs/PROJECT_TRY/connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    // Validate input
    if (empty($username) || empty($name) || empty($email) || empty($contact)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if the database connection was successful
    if ($conn->connect_error) {
        echo "Database connection failed: " . $conn->connect_error;
        exit;
    }

    // Prepare SQL statement
    $sql = "UPDATE admin SET name=?, email=?, contact=?, address=? WHERE username=?";
    $stmt = $conn->prepare($sql);

    // Print error details if prepare fails
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    // Bind parameters and execute
    $stmt->bind_param("sssss", $name, $email, $contact, $username);

    // Debugging - print statements
    if ($stmt->execute()) {
        echo '<script>alert("Profile updated successfully.");</script>';
        echo "Updated rows: " . $stmt->affected_rows; // To check how many rows were updated
        header("Location: pages_profile.html");
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

// Close connection
$conn->close();
?>
