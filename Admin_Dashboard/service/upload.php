<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if a file was uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDir = 'document_upload/'; // Directory to save uploaded files
    $uploadFile = $uploadDir.basename($_FILES['file']['name']);

    // Check for file upload errors
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo 'File upload error: '.$_FILES['file']['error'];
        exit;
    }

    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            echo 'Failed to create upload directory.';
            exit;
        }
    }

    // Move uploaded file to the specified directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        echo 'File is valid, and was successfully uploaded.<br>';
        echo 'File name: '.htmlspecialchars(basename($_FILES['file']['name']));
    } else {
        // Debugging: Print the current permissions of the upload directory
        $perms = substr(sprintf('%o', fileperms($uploadDir)), -4);
        echo "An error occurred during file upload. Directory permissions: $perms";
    }
}
