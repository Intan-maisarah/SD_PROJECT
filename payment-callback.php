<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection file
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

// Display incoming data for debugging
echo '<h3>Incoming POST Data:</h3>';
echo '<pre>'.htmlspecialchars(json_encode($_POST, JSON_PRETTY_PRINT)).'</pre>';

// Check that required parameters are provided
if (empty($billCode) || empty($paymentStatus)) {
    echo 'Error: Missing required data. <br> BillCode: '.htmlspecialchars($billCode).', Payment Status: '.htmlspecialchars($paymentStatus);
    exit;
}

// Map ToyyibPay status to your database payment_status
$payment_status = ($paymentStatus == '1') ? 'paid' : 'unpaid';

// Check if the BillCode exists in the orders table
$checkStmt = $conn->prepare('SELECT COUNT(*) FROM orders WHERE BillCode = ?');
$checkStmt->bind_param('s', $billCode);
$checkStmt->execute();
$checkStmt->bind_result($count);
$checkStmt->fetch();
$checkStmt->close();

// Display the count result for debugging
echo '<h3>BillCode Check:</h3>';
echo 'BillCode: '.htmlspecialchars($billCode).', Count: '.htmlspecialchars($count).'<br>';

if ($count == 0) {
    echo 'Error: BillCode not found in the database.';
    exit;
}

// Prepare to update the order's payment status
$stmt = $conn->prepare('UPDATE orders SET payment_status = ? WHERE BillCode = ?');
if (!$stmt) {
    echo 'SQL Error: '.htmlspecialchars($conn->error);
    exit;
}
$stmt->bind_param('ss', $payment_status, $billCode);

// Debugging output before executing
echo 'Updating payment status to: '.htmlspecialchars($payment_status).', for BillCode: '.htmlspecialchars($billCode).'<br>';

// Attempt to execute the update statement
if ($stmt->execute()) {
    echo 'Payment status updated successfully.';
} else {
    echo 'Payment status could not be confirmed. Please try again later. Error: '.htmlspecialchars($stmt->error);
}

// Close the statement and connection
$stmt->close();
$conn->close();
