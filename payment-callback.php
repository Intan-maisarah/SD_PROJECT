<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

$billCode = $_POST['billCode'] ?? '';
$paymentStatus = $_POST['status'] ?? '';

$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'billCode' => $billCode,
    'paymentStatus' => $paymentStatus,
    'fullPostData' => $_POST,
];

file_put_contents('callback_log.txt', json_encode($logData).PHP_EOL, FILE_APPEND);

if (empty($billCode) || empty($paymentStatus)) {
    echo 'Error: Missing required data. <br> BillCode: '.htmlspecialchars($billCode).', Payment Status: '.htmlspecialchars($paymentStatus);
    exit;
}

$payment_status = ($paymentStatus == '1') ? 'PAID' : 'UNPAID';

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

$stmt = $conn->prepare('UPDATE orders SET payment_status = ? WHERE BillCode = ?');
$stmt->bind_param('ss', $payment_status, $billCode);

if ($stmt->execute()) {
    file_put_contents('callback_log.txt', 'Payment status updated successfully for BillCode: '.$billCode.PHP_EOL, FILE_APPEND);
} else {
    echo 'Payment status could not be confirmed. Please try again later. Error: '.htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();
