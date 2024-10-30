<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

// Verify session variables and fetch user info if not set
if (!isset($_SESSION['email']) || !isset($_SESSION['name']) || !isset($_SESSION['contact'])) {
    $userId = $_SESSION['user_id'] ?? 0;
    if ($userId) {
        $stmt = $conn->prepare('SELECT email, name, contact FROM users WHERE id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($email, $name, $contact);
        if ($stmt->fetch()) {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['contact'] = $contact;
        }
        $stmt->close();
    }
}

// Handle delete order request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];

    $deleteDetailsStmt = $conn->prepare('DELETE FROM order_details WHERE order_id = ?');
    $deleteDetailsStmt->bind_param('s', $order_id);
    if ($deleteDetailsStmt->execute()) {
        $deleteOrderStmt = $conn->prepare('DELETE FROM orders WHERE order_id = ?');
        $deleteOrderStmt->bind_param('s', $order_id);
        if ($deleteOrderStmt->execute()) {
            echo 'Order deleted successfully.';
            header('Location: index.php');
            exit;
        } else {
            echo 'Error deleting order: '.$deleteOrderStmt->error;
        }
        $deleteOrderStmt->close();
    } else {
        echo 'Error deleting order details: '.$deleteDetailsStmt->error;
    }
    $deleteDetailsStmt->close();
}

// Initialize variables
$order_id = $_POST['order_id'] ?? $_SESSION['order_id'];
$total_price = 0;
$email = $_SESSION['email'] ?? '';
$name = $_SESSION['name'] ?? '';
$contact = $_SESSION['contact'] ?? '';
$specification_data = [];

$toyyibpayApiKey = 'dn9jqdur-tzqt-pztk-6qgm-9xa4jg7m57qx';
$toyyibpayCategoryCode = 'ltceill4';

// Calculate order price and handle specifications
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $order_id) {
    $specification_types = $_POST['specification_id'] ?? [];
    $quantity = $_POST['quantity'] ?? 1;
    $page_count = $_POST['page_count'] ?? 1;

    if (!empty($specification_types)) {
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
                    $total_doc_price = $spec_price * $page_count * $quantity;
                    $total_price += $total_doc_price;

                    $stmt->close();

                    $stmt = $conn->prepare('INSERT INTO order_details (order_id, specification_id, price, quantity, page_count, total_price) VALUES (?, ?, ?, ?, ?, ?)');
                    $stmt->bind_param('sidiid', $order_id, $specification_id, $spec_price, $quantity, $page_count, $total_doc_price);
                    if ($stmt->execute()) {
                        $specification_data[] = [
                            'id' => $specification_id,
                            'price' => $spec_price,
                            'total_price' => $total_doc_price,
                        ];
                    }
                    $stmt->close();
                }
            }

            if ($total_price > 0) {
                $updateOrderStmt = $conn->prepare('UPDATE orders SET total_order_price = ? WHERE order_id = ?');
                $updateOrderStmt->bind_param('ds', $total_price, $order_id);
                if ($updateOrderStmt->execute()) {
                    $_SESSION['total_order_price'] = $total_price;
                    $_SESSION['specification_data'] = $specification_data;
                } else {
                    echo 'Error updating total_order_price: '.$updateOrderStmt->error.'<br>';
                }
                $updateOrderStmt->close();
            } else {
                echo 'Error: total_price is zero, check specification and pricing data.<br>';
            }
        }
    } else {
        echo 'Error: No specifications selected.<br>';
    }
}

// Handle payment request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['proceed_to_payment'])) {
    $total_price = $_SESSION['total_order_price'] ?? 0;
    $specification_data = $_SESSION['specification_data'] ?? [];

    if ($total_price > 0) {
        $billUrl = createToyyibPayBill($order_id, $email, $total_price, $name, $contact);
        if ($billUrl) {
            // Clear session data after successful payment initiation
            unset($_SESSION['total_order_price'], $_SESSION['specification_data']);
            header("Location: $billUrl");
            exit;
        } else {
            echo 'Error creating payment bill.';
        }
    } else {
        echo 'Error: total_order_price is not set or is zero.';
    }
}

function createToyyibPayBill($order_id, $email, $total_order_price, $name, $contact)
{
    global $toyyibpayApiKey, $toyyibpayCategoryCode, $conn;

    $url = 'https://dev.toyyibpay.com/index.php/api/createBill';
    $data = [
        'userSecretKey' => $toyyibpayApiKey,
        'categoryCode' => $toyyibpayCategoryCode,
        'billName' => $order_id,
        'billDescription' => 'Payment for '.$order_id,
        'billAmount' => $total_order_price * 100,
        'billExternalReferenceNo' => $order_id,
        'billTo' => $name,
        'billEmail' => $email,
        'billPhone' => $contact,
        'billReturnUrl' => 'https://palegreen-buffalo-300863.hostingersite.com/payment-success.php?order_id='.$order_id,
        'billCallbackUrl' => 'https://palegreen-buffalo-300863.hostingersite.com/payment-callback.php',
        'billPriceSetting' => '1',
        'billPayorInfo' => '1',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseArray = json_decode($response, true);
    if (isset($responseArray[0]['BillCode'])) {
        $billCode = $responseArray[0]['BillCode'];

        // Store BillCode in the database
        $stmt = $conn->prepare('UPDATE orders SET BillCode = ? WHERE order_id = ?');
        $stmt->bind_param('ss', $billCode, $order_id);
        if ($stmt->execute()) {
            echo 'ToyyibPay Bill Code saved: '.$billCode.'<br>';
        } else {
            echo 'Error saving BillCode: '.$stmt->error.'<br>';
        }
        $stmt->close();

        return 'https://dev.toyyibpay.com/'.$billCode;
    } else {
        echo 'ToyyibPay API Error: ';
        print_r($responseArray);

        return false;
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
                <?php foreach ($specification_data as $spec) {
                    $specification_id = $spec['id'];
                    $spec_price = $spec['price'];
                    $total_price_for_spec = $spec['total_price'];

                    $query = 'SELECT sn.spec_name AS spec_name, s.spec_type AS spec_type FROM spec_names sn JOIN specification s ON s.spec_name_id = sn.id WHERE s.id = ?';
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('i', $specification_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        $spec_details = $result->fetch_assoc();
                        $spec_name = $spec_details['spec_name'] ?? 'Unknown';
                        $spec_type = $spec_details['spec_type'] ?? 'Unknown';
                    }
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

        <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <button type="submit" name="proceed_to_payment" class="btn btn-primary">Proceed to Payment</button>
            <button type="submit" name="delete_order" class="btn btn-danger" onclick="return confirmDelete();">Delete Order</button>
            </form>
    <?php } else { ?>
        <p>No specifications found for this order.</p>
    <?php } ?>
</div>

<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this order?");
}
</script>

</body>
</html>
