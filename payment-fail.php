<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/order.css">
</head>
<body class="payment-failed-body">
<div class="payment-failed-container">
    <h2 class="payment-failed-title">Payment Failed</h2>
    <p class="payment-failed-message">We're sorry, but your payment could not be processed. Please try again later or <a href="contact.php" class="payment-failed-link">contact us</a></p>
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($_GET['order_id']); ?></p>
    
    <div class="payment-failed-gif">
        <img src="assets/images/payment_error.gif" alt="error GIF">
    </div>
    
    <a href="index.php" class="payment-failed-btn">Return to Home</a>
</div>
</body>
</html>
