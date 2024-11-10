<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    echo 'Session user_id not set. Redirecting to signin.php.';
    header('Location: signin.php');
    exit;
}
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare('
    SELECT o.order_id, o.payment_status, o.status, o.total_order_price, GROUP_CONCAT(d.document_upload SEPARATOR " / ") AS document_names
    FROM orders o
    LEFT JOIN order_documents d ON o.order_id = d.order_id
    WHERE o.user_id = ? AND (o.payment_status = "paid" OR o.payment_status = "pending")
    GROUP BY o.order_id
    ORDER BY o.created_at DESC
');

if (!$stmt) {
    exit('Prepare failed: '.$conn->error);
}

$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/order.css">
</head>
<body class="history-body">
<?php include 'navbar.php'; ?>
    <div class="order-container">
        <h2 class="order-title">Order History</h2>
        <?php if ($result->num_rows > 0) { ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total Price</th>
                        <th>Document Names</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) {
                        $document_names = $row['document_names'];
                        if ($document_names !== null) {
                            $documentNamesArray = explode(' / ', $document_names);
                            $formattedDocumentNames = implode('<br>', array_map(function ($doc) {
                                return htmlspecialchars(basename($doc));
                            }, $documentNamesArray));
                        } else {
                            $formattedDocumentNames = 'No documents uploaded';
                        }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td>RM <?php echo htmlspecialchars(number_format($row['total_order_price'], 2)); ?></td>
                            <td><?php echo $formattedDocumentNames; ?></td>
                            <td>
                                <a href="order_status.php?order_id=<?php echo $row['order_id']; ?>" class="order-btn-primary order-btn-sm">View Status</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="order-no-orders">No orders found in your history.</p>
        <?php }
        $stmt->close();
$conn->close();
?>
    </div>

    <!-- Footer -->
    <footer class="text-white" style="background-color: #A7C7E7; padding: 40px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>
                        Our online printing service was inspired by the needs and creativity of students who constantly
                        juggle tight deadlines and demanding schedules. With 24/7 document uploads and a convenient 1km
                        delivery range, we provide the flexibility students need, ensuring their printing requirements are met
                        anytime, anywhere.
                    </p>
                </div>

                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-center mb-2">
                            <img src="assets/images/location.png" alt="Location Icon" style="width: 24px; height: auto; margin-right: 10px;">
                            <span>Gurney Mall, Lot 1-30, Jln Maktab, 54000 Kuala Lumpur</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <img src="assets/images/call.png" alt="Phone Icon" style="width: 24px; height: auto; margin-right: 10px;">
                            <span>+6014 2272-647</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <img src="assets/images/mail.png" alt="Mail Icon" style="width: 24px; height: auto; margin-right: 10px;">
                            <span>infinity.utmkl@gmail.com</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <img src="assets/images/bhours.png" alt="Business Hours Icon" style="width: 24px; height: auto; margin-right: 10px;">
                            <span>Mon-Fri: 9 AM - 6 PM</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
