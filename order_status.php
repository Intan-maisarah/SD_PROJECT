<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? null;

if ($order_id) {
    // Fetch order status and payment status
    $stmt = $conn->prepare('
        SELECT o.payment_status, o.status 
        FROM orders o
        WHERE o.order_id = ? AND o.user_id = ?
    ');
    $stmt->bind_param('si', $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    if ($order) {
        $payment_status = $order['payment_status'];
        $status = $order['status'];
    } else {
        echo "<p class='no-orders'>Order not found.</p>";
        exit;
    }

    // Fetch order details
    $stmt = $conn->prepare('
        SELECT DISTINCT sn.spec_name, s.spec_type 
        FROM order_details od
        JOIN specification s ON od.specification_id = s.id
        JOIN spec_names sn ON s.spec_name_id = sn.id
        WHERE od.order_id = ?
    ');
    $stmt->bind_param('s', $order_id);
    $stmt->execute();
    $details_result = $stmt->get_result();
    $stmt->close();
} else {
    echo "<p class='no-orders'>Invalid order ID.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Status</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #d6a1ed;
            color: #333;
            padding-top: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h2 {
            font-size: 1.8em;
            color: #444;
        }
        .timeline-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        .timeline-step {
            text-align: center;
            flex: 1;
        }
        .payment-status-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #ddd;
            margin: 0 auto 10px;
            transition: background-color 0.3s ease;
        }
        .payment-status-circle.active {
            background-color: #6c63ff;
        }
        .timeline-step.active div {
            color: #6c63ff;
            font-weight: bold;
        }
        .order-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .order-details-table th, .order-details-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .order-details-table th {
            background-color: #6c63ff;
            color: white;
            font-weight: 600;
        }
        .btn-secondary {
            margin-top: 20px;
            background-color: #6c63ff;
            color: white;
            border: none;
            padding: 8px 20px;
        }
        .btn-secondary:hover {
            background-color: #5a53c1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Order Status</h2>
        <div class="timeline-container">
            <div class="timeline-step <?php echo ($status == 'pending' || $status == 'in_progress' || $status == 'completed') ? 'active' : ''; ?>">
                <div class="payment-status-circle <?php echo $status == 'pending' ? 'active' : ''; ?>"></div>
                <div>Pending</div>
            </div>
            <div class="timeline-step <?php echo ($status == 'in_progress' || $status == 'completed') ? 'active' : ''; ?>">
                <div class="payment-status-circle <?php echo $status == 'in_progress' ? 'active' : ''; ?>"></div>
                <div>In Progress</div>
            </div>
            <div class="timeline-step <?php echo $status == 'completed' ? 'active' : ''; ?>">
                <div class="payment-status-circle <?php echo $status == 'completed' ? 'active' : ''; ?>"></div>
                <div>Completed</div>
            </div>
        </div>

        <h4>Order Details</h4>
        <?php if ($details_result->num_rows > 0) { ?>
            <table class="order-details-table">
                <thead>
                    <tr>
                        <th>Specification Name</th>
                        <th>Specification Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($detail = $details_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['spec_name']); ?></td>
                            <td><?php echo htmlspecialchars($detail['spec_type']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="no-orders">No order details found.</p>
        <?php } ?>
        <a href="order_history.php?view=view" class="btn btn-secondary">Back to Order History</a>
    </div>
</body>
</html>
