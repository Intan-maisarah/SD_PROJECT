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
    <style>
        body {
            background-color: #ff4d4d; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #d9534f;
        }
        .gif-container {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Payment Failed</h2>
    <p>We're sorry, but your payment could not be processed. Please try again later or <a href="contact.php">contact us</a></p>
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($_GET['order_id']); ?></p>
    
    <!-- Add GIF here -->
    <div class="gif-container">
    <img src="assets/images/payment_error.gif" alt="error GIF" width="200">
    </div>
    
    <a href="index.php" class="btn btn-primary">Return to Home</a>
</div>
</body>
</html>
