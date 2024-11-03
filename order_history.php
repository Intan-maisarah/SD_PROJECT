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

$view = $_GET['view'] ?? 'view';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            padding-top: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .order-history-table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-history-table th, .order-history-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .order-history-table th {
            background-color: #4CAF50;
            color: white;
        }
        .no-orders {
            color: #666;
            text-align: center;
            font-size: 1.2em;
            margin-top: 20px;
        }
        /* Style for the timeline */
        .timeline-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 600px;
            margin: 20px auto;
        }
        .timeline-step {
            text-align: center;
            flex: 1;
        }
        .payment-status-circle {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background-color: #ddd;
            margin: 0 auto 10px;
        }
        .payment-status-circle.active {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-4">Order History</h2>
        <?php
        switch ($view) {
            case 'view':
                $stmt = $conn->prepare('
                  SELECT o.order_id, o.payment_status, o.status, o.total_order_price, 
                    o.document_upload
                    FROM orders o
                    WHERE o.user_id = ? AND o.payment_status = "paid" OR o.payment_status =  "pending"
                    ORDER BY o.order_id DESC
                ');
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>
                <!-- Order History Table -->
                <?php if ($result->num_rows > 0) { ?>
                    <table class="order-history-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Total Price</th>
                                <th>Document Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) {
                                $full_document_upload = $row['document_upload'];
                                $file_name_with_ext = basename($full_document_upload);
                                $cleaned_file_name = preg_replace('/^.*?_(.*)$/', '$1', $file_name_with_ext);
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                                    <td>RM <?php echo htmlspecialchars(number_format($row['total_order_price'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($cleaned_file_name); ?></td>
                                    <td><a href="order_history.php?view=viewstatus&order_id=<?php echo $row['order_id']; ?>" class="btn btn-primary btn-sm">View Status</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p class="no-orders">No orders found in your history.</p>
                <?php } ?>
                <?php $stmt->close();
                break;

            case 'viewstatus':
                $order_id = $_GET['order_id'] ?? null;
                if ($order_id) {
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
                    ?>
                    <h3>Order Status</h3>
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
                
                    <h4 class="my-4">Order Details</h4>
                    <?php if ($details_result->num_rows > 0) { ?>
                        <table class="order-history-table">
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
                    <?php
                } else {
                    echo "<p class='no-orders'>Invalid order ID.</p>";
                }
                break;

            default:
                echo "<p class='no-orders'>Invalid view.</p>";
                break;
        }
?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
