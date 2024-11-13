<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../connection.php';

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

    // Fetch document names for the order
    $stmt = $conn->prepare('
        SELECT id, document_upload 
        FROM order_documents
        WHERE order_id = ?
    ');
    $stmt->bind_param('s', $order_id);
    $stmt->execute();
    $doc_result = $stmt->get_result();
    $documents = [];
    while ($doc = $doc_result->fetch_assoc()) {
        $documents[$doc['id']] = basename($doc['document_upload']);
    }
    $stmt->close();

    // Fetch order details
    $stmt = $conn->prepare('
        SELECT DISTINCT sn.spec_name, s.spec_type, od.document_id
        FROM order_details od
        JOIN specification s ON od.specification_id = s.id
        JOIN spec_names sn ON s.spec_name_id = sn.id
        WHERE od.order_id = ?
    ');
    $stmt->bind_param('s', $order_id);
    $stmt->execute();
    $details_result = $stmt->get_result();
    $stmt->close();

    // Group specifications by document
    $specifications = [];
    while ($detail = $details_result->fetch_assoc()) {
        $doc_id = $detail['document_id'];
        $specifications[$doc_id][] = [
            'spec_name' => $detail['spec_name'],
            'spec_type' => $detail['spec_type'],
        ];
    }
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
    <link rel="stylesheet" href="../assets/order.css">
</head>
<body class="order-status-body">
    <div class="order-status-container">
        <h2 class="order-status-title">Order Status</h2>
        <div class="order-status-timeline">
            <div class="timeline-step <?php echo ($status == 'pending' || $status == 'in_progress' || $status == 'completed') ? 'active' : ''; ?>">
                <div class="timeline-circle <?php echo $status == 'pending' ? 'active' : ''; ?>"></div>
                <div>Pending</div>
            </div>
            <div class="timeline-step <?php echo ($status == 'in_progress' || $status == 'completed') ? 'active' : ''; ?>">
                <div class="timeline-circle <?php echo $status == 'in_progress' ? 'active' : ''; ?>"></div>
                <div>In Progress</div>
            </div>
            <div class="timeline-step <?php echo $status == 'completed' ? 'active' : ''; ?>">
                <div class="timeline-circle <?php echo $status == 'completed' ? 'active' : ''; ?>"></div>
                <div>Completed</div>
            </div>
        </div>

        <h4 class="order-details-title">Order Documents</h4>
        <?php if (!empty($documents)) { ?>
            <ul class="order-documents-list">
                <?php foreach ($documents as $doc_id => $doc_name) { ?>
                    <li><?php echo htmlspecialchars($doc_name); ?></li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p class="order-status-no-documents">No documents uploaded.</p>
        <?php } ?>

        <h4 class="order-details-title">Order Specifications</h4>
        <?php if (!empty($specifications)) { ?>
            <table class="order-details-table">
                <thead>
                    <tr>
                        <th>Document Name</th>
                        <th>Specification Name</th>
                        <th>Specification Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($specifications as $doc_id => $specs) { ?>
                        <tr>
                            <td rowspan="<?php echo count($specs); ?>"><?php echo htmlspecialchars($documents[$doc_id] ?? 'Unknown Document'); ?></td>
                            <td><?php echo htmlspecialchars($specs[0]['spec_name']); ?></td>
                            <td><?php echo htmlspecialchars($specs[0]['spec_type']); ?></td>
                        </tr>
                        <?php for ($i = 1; $i < count($specs); ++$i) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($specs[$i]['spec_name']); ?></td>
                                <td><?php echo htmlspecialchars($specs[$i]['spec_type']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="order-status-no-details">No order details found.</p>
        <?php } ?>
        <a href="order_history.php?view=view" class="order-status-back-btn">Back to Order History</a>
    </div>
</body>
</html>