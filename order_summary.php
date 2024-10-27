<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

$order_id = $_POST['order_id'] ?? null;
$total_price = 0;
$specification_data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $specification_types = $_POST['specification_id'] ?? [];
    $quantity = $_POST['quantity'] ?? 1;
    $page_count = $_POST['page_count'] ?? 0;

    $checkOrderStmt = $conn->prepare('SELECT COUNT(*) FROM orders WHERE order_id = ?');
    $checkOrderStmt->bind_param('s', $order_id);
    $checkOrderStmt->execute();
    $checkOrderStmt->bind_result($count);
    $checkOrderStmt->fetch();
    $checkOrderStmt->close();

    if ($count > 0) {
        foreach ($specification_types as $specification_type) {
            $stmt = $conn->prepare('SELECT id, price FROM specification WHERE spec_type = ?');
            $stmt->bind_param('s', $specification_type);
            $stmt->execute();
            $stmt->bind_result($specification_id, $spec_price);

            if ($stmt->fetch()) {
                $total_doc_price = $spec_price * $page_count;

                $stmt->close();

                $stmt = $conn->prepare('INSERT INTO order_details (order_id, specification_id, price, quantity, page_count) VALUES (?, ?, ?, ?, ?)');
                $stmt->bind_param('sidii', $order_id, $specification_id, $spec_price, $quantity, $page_count);

                if ($stmt->execute()) {
                    $total_price += $total_doc_price * $quantity;
                } else {
                    echo 'Error inserting specification: '.$stmt->error.'<br>';
                }

                $stmt->close();

                $specification_data[] = [
                    'id' => $specification_id,
                    'price' => $spec_price,
                ];
            } else {
                echo 'Specification type not found: '.htmlspecialchars($specification_type).'<br>';
            }
        }
    } else {
        echo 'Invalid Order ID: Order does not exist.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Order Summary</h2>
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>

    <?php if (!empty($specification_data)) { ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Specification Name</th>
                    <th>Specification Type</th>
                    <th>Price (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0;

        foreach ($specification_data as $spec) {
            $specification_id = $spec['id'];
            $spec_price = $spec['price'];

            $query = '
                        SELECT sn.spec_name AS spec_name, s.spec_type AS spec_type 
                        FROM spec_names sn 
                        JOIN specification s ON s.spec_name_id = sn.id 
                        WHERE s.id = ? 
                    ';

            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $specification_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                $spec_details = $result->fetch_assoc();

                if ($spec_details) {
                    $spec_name = $spec_details['spec_name'];
                    $spec_type = $spec_details['spec_type'];
                } else {
                    $spec_name = 'Unknown';
                    $spec_type = 'Unknown';
                }
            } else {
                $spec_name = 'Error';
                $spec_type = 'Error';
            }

            $total_price_for_spec = $spec_price * $page_count * $quantity;

            $total_price += $total_price_for_spec;
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($spec_name); ?></td>
                        <td><?php echo htmlspecialchars($spec_type); ?></td>
                        <td><?php echo htmlspecialchars(number_format($total_price_for_spec, 2)); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <h4>Total Price: RM <?php echo number_format($total_price, 2); ?></h4>
        <h4>Total Quantity: <?php echo htmlspecialchars($quantity); ?></h4>
        
    <?php } else { ?>
        <p>No specifications were added to the order.</p>
    <?php } ?>

    <button onclick="window.location.href='index.php'" class="btn btn-primary">Back to Home</button>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
