<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

// Capture ToyyibPay callback data
$billCode = $_POST['billCode'] ?? '';
$paymentStatus = $_POST['status'] ?? '';

// Log incoming data for debugging
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'billCode' => $billCode,
    'paymentStatus' => $paymentStatus,
    'fullPostData' => $_POST,
];

file_put_contents('callback_log.txt', json_encode($logData).PHP_EOL, FILE_APPEND);

// Check that required parameters are provided
if (empty($billCode) || empty($paymentStatus)) {
    echo 'Error: Missing required data. <br> BillCode: '.htmlspecialchars($billCode).', Payment Status: '.htmlspecialchars($paymentStatus);
    exit;
}

// Map ToyyibPay status to your database payment_status
$payment_status = ($paymentStatus == '1') ? 'PAID' : 'UNPAID'; // Ensure consistency in status

// Check if the BillCode exists in the orders table
$checkStmt = $conn->prepare('SELECT COUNT(*) FROM orders WHERE BillCode = ?');
$checkStmt->bind_param('s', $billCode);
$checkStmt->execute();
$checkStmt->bind_result($count);
$checkStmt->fetch();
$checkStmt->close();

if ($count == 0) {
    echo 'Error: BillCode not found in the database.';
    exit;
}

// Prepare to update the order's payment status
$stmt = $conn->prepare('UPDATE orders SET payment_status = ? WHERE BillCode = ?');
$stmt->bind_param('ss', $payment_status, $billCode);

if ($stmt->execute()) {
    // Log success
    file_put_contents('callback_log.txt', 'Payment status updated successfully for BillCode: '.$billCode.PHP_EOL, FILE_APPEND);
} else {
    echo 'Payment status could not be confirmed. Please try again later. Error: '.htmlspecialchars($stmt->error);
}

// Close the statement and connection
$stmt->close();
$conn->close();
