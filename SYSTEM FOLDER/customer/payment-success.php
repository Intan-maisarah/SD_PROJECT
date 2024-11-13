<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../connection.php';

$order_id = $_GET['order_id'] ?? '';
$toyyibpayApiKey = 'dn9jqdur-tzqt-pztk-6qgm-9xa4jg7m57qx';

if (empty($order_id)) {
    echo 'Invalid order ID. Please try again.';
    exit;
}

$stmt = $conn->prepare('SELECT BillCode, total_order_price FROM orders WHERE order_id = ?');
$stmt->bind_param('s', $order_id);
$stmt->execute();
$stmt->bind_result($billCode, $total_order_price);
if (!$stmt->fetch() || empty($billCode)) {
    echo 'Order not found or has already been processed.';
    exit;
}
$stmt->close();

$toyyibpayUrl = 'https://dev.toyyibpay.com/index.php/api/getBillTransactions';
$data = [
    'userSecretKey' => $toyyibpayApiKey,
    'billCode' => $billCode,
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $toyyibpayUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
curl_close($ch);

$responseArray = json_decode($response, true);

if (isset($responseArray[0]['billpaymentStatus'])) {
    if ($responseArray[0]['billpaymentStatus'] === '1') {
        $updateStatusStmt = $conn->prepare('UPDATE orders SET payment_status = ? WHERE order_id = ?');
        $payment_status = 'PAID';
        $updateStatusStmt->bind_param('ss', $payment_status, $order_id);

        if ($updateStatusStmt->execute()) {
            $updateStatusStmt->close();
        } else {
            echo 'Error updating order status: '.$updateStatusStmt->error;
            exit;
        }
    } else {
        header('Location: payment-fail.php?order_id='.urlencode($order_id));
        exit;
    }
} else {
    echo 'Payment status could not be confirmed. Please try again later.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/order.css">
</head>
<body class="payment-page-body">
<div class="payment-success-container">
    <div class="payment-gif-container">
        <img src="../assets/images/success.gif" alt="Success GIF">
    </div>
    <div class="payment-content">
        <h2 class="payment-title">Payment Successful</h2>
        <p class="payment-message">Thank you for your payment!</p>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
        <p><strong>Total Paid:</strong> RM <?php echo htmlspecialchars(number_format($total_order_price, 2)); ?></p>
        <a href="../index.php" class="payment-btn-primary">Return to Home</a>
    </div>
</div>
</body>
</html>

