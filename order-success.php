<?php
session_start();
include 'connection.php'; // Include your database connection

// Assuming order_id is passed via GET request
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    // Redirect to homepage or show an error if no order ID is provided
    header('Location: index.php');
    exit;
}

// Fetch order details from the database
$stmt = $conn->prepare('SELECT * FROM orders WHERE order_id = ?');
$stmt->bind_param('s', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    // Order not found, redirect or show error
    echo 'Order not found.';
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Thank You for Your Order!</h1>
        <p>Your order has been successfully processed.</p>
        <h2>Order Details:</h2>
        <ul>
            <li><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></li>
            <li><strong>Total Price:</strong> RM <?php echo number_format($order['total_order_price'], 2); ?></li>
            <li><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></li>
            <li><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></li>
        </ul>

        


       

        <p>If you have any questions, please <a href="contact.php">contact us</a>.</p>
        <p><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>
