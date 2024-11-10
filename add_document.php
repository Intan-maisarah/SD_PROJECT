<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;
    $upload_dir = 'Admin_Dashboard/service/document_upload/';
    $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'txt'];

    if ($order_id && isset($_FILES['file'])) {
        $file_name = basename($_FILES['file']['name']);
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $upload_path = $upload_dir.$order_id.'_'.$file_name;

        if (in_array($file_ext, $allowed_extensions)) {
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Change this line to insert into 'order_documents' table
                $stmt = $conn->prepare('INSERT INTO order_documents (order_id, document_upload, user_id) VALUES (?, ?, ?)');
                $stmt->bind_param('sss', $order_id, $upload_path, $user_id); // Bind user_id as well

                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Document uploaded successfully!';
                } else {
                    $_SESSION['error'] = 'Database error: '.$stmt->error;
                }

                $stmt->close();
            } else {
                $_SESSION['error'] = 'Error uploading file.';
            }
        } else {
            $_SESSION['error'] = 'Invalid file type. Allowed: '.implode(', ', $allowed_extensions);
        }
    } else {
        $_SESSION['error'] = 'Order ID or file missing.';
    }

    // Redirect back to add_services.php
    header('Location: add_services.php?order_id='.$order_id);
    exit;
}
