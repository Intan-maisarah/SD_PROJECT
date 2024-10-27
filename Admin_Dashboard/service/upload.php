<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
include '../../connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch user ID from session (ensure the user is logged in)
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo 'User not logged in.';
        exit;
    }

    // Set the relative directory for file storage
    $uploadDir = 'document_upload/';
    $fullPathForDatabase = 'Admin_Dashboard/service/'.$uploadDir; // Ensure this is the correct path for the database

    // Use the original filename from the uploaded file
    $fileName = basename($_FILES['file']['name']);
    $uploadFile = $uploadDir.$fileName; // Actual file path for moving
    $dbFilePath = $fullPathForDatabase.$fileName; // Correct path to store in the database

    // Handle file upload errors
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo 'File upload error: '.$_FILES['file']['error'];
        exit;
    }

    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            echo 'Failed to create upload directory.';
            exit;
        }
    }

    // Move the uploaded file
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        // Generate a new order ID
        $orderId = uniqid('order_', true);

        // Prepare the SQL statement to insert order details
        $stmt = $conn->prepare('INSERT INTO orders (order_id, user_id, document_upload) VALUES (?, ?, ?)');
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param('sis', $orderId, $userId, $dbFilePath); // Ensure $dbFilePath is correct

            // Execute the statement
            if ($stmt->execute()) {
                // Store the uploaded file information in session to pass it to the next page
                $_SESSION['document_upload'] = $dbFilePath;
                $_SESSION['order_id'] = $orderId;

                // Redirect to the confirmation page immediately
                header('Location: ../../add_services.php?order_id='.urlencode($orderId));
                exit;
            } else {
                echo 'Database error: '.$stmt->error; // Display any database error
            }
            $stmt->close(); // Close the prepared statement
        } else {
            echo 'Failed to prepare the SQL statement: '.$conn->error; // Display error preparing statement
        }
    } else {
        echo 'An error occurred during file upload.'; // General error for file upload failure
    }
}

// Close the database connection
$conn->close();
