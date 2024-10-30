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
</head>
<body>
<div class="container mt-5">
    <h2>Payment Failed</h2>
    <p>We're sorry, but your payment could not be processed. Please try again later or contact support.</p>
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($_GET['order_id']); ?></p>
    <a href="index.php" class="btn btn-primary">Return to Home</a>
</div>
</body>
</html>
