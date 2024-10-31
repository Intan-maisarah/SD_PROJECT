<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];

    $deleteDetailsStmt = $conn->prepare('DELETE FROM order_details WHERE order_id = ?');
    $deleteDetailsStmt->bind_param('s', $order_id);
    if ($deleteDetailsStmt->execute()) {
        $deleteOrderStmt = $conn->prepare('DELETE FROM orders WHERE order_id = ?');
        $deleteOrderStmt->bind_param('s', $order_id);
        if ($deleteOrderStmt->execute()) {
            header('Location: index.php');
            exit;
        }
        $deleteOrderStmt->close();
    }
    $deleteDetailsStmt->close();
}

$order_id = $_POST['order_id'] ?? $_SESSION['order_id'];
$total_price = 0;
$email = $_SESSION['email'] ?? '';
$name = $_SESSION['name'] ?? '';
$contact = $_SESSION['contact'] ?? '';
$specification_data = [];
$delivery_method = $_POST['delivery_method'] ?? '';
$pickup_appointment = $_POST['pickup_appointment'] ?? '';
$delivery_time = $_POST['delivery_time'] ?? '';
$delivery_location_id = $_POST['delivery_location'] ?? '';

$toyyibpayApiKey = 'dn9jqdur-tzqt-pztk-6qgm-9xa4jg7m57qx';
$toyyibpayCategoryCode = 'ltceill4';

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
                    $stmt->execute();
                    $specification_data[] = [
                        'id' => $specification_id,
                        'price' => $spec_price,
                        'total_price' => $total_doc_price,
                    ];
                    $stmt->close();
                }
            }

            if ($delivery_method === 'pickup') {
                $insertPickupStmt = $conn->prepare('UPDATE orders SET delivery_method = ?, pickup_appointment = ? WHERE order_id = ?');
                $insertPickupStmt->bind_param('sss', $delivery_method, $pickup_appointment, $order_id);
                $insertPickupStmt->execute();
                $insertPickupStmt->close();
            } elseif ($delivery_method === 'delivery') {
                $total_price += 2;

                if (!$delivery_location_id) {
                    echo 'No delivery location ID provided.';
                } else {
                    $stmt = $conn->prepare('SELECT location_name FROM delivery_locations WHERE id = ?');
                    $stmt->bind_param('i', $delivery_location_id);
                    $stmt->execute();
                    $stmt->bind_result($location_name);

                    if ($stmt->fetch()) {
                        $stmt->close();

                        $delivery_time = $_POST['delivery_time'] ?? '';

                        $updateOrderStmt = $conn->prepare('UPDATE orders SET delivery_method = ?, delivery_location_id = ?, delivery_time = ? WHERE order_id = ?');
                        $updateOrderStmt->bind_param('siss', $delivery_method, $delivery_location_id, $delivery_time, $order_id);
                        $updateOrderStmt->execute();
                        $updateOrderStmt->close();
                    } else {
                        echo 'Invalid delivery location ID.';
                        $stmt->close();
                    }
                }
            } else {
                echo 'Invalid delivery method.';
            }

            if ($total_price > 0) {
                $updateOrderStmt = $conn->prepare('UPDATE orders SET total_order_price = ? WHERE order_id = ?');
                $updateOrderStmt->bind_param('ds', $total_price, $order_id);
                $updateOrderStmt->execute();
                $_SESSION['total_order_price'] = $total_price;
                $_SESSION['specification_data'] = $specification_data;
                $updateOrderStmt->close();
            } else {
                echo 'Error: total_price is zero, check specification and pricing data.<br>';
            }
        }
    } else {
        echo 'Error: No specifications selected.<br>';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['proceed_to_payment'])) {
    $total_price = $_SESSION['total_order_price'] ?? 0;
    $specification_data = $_SESSION['specification_data'] ?? [];

    if ($total_price > 0) {
        $billUrl = createToyyibPayBill($order_id, $email, $total_price, $name, $contact);
        if ($billUrl) {
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
        'billReturnUrl' => 'https://localhost/SD_PROJECT/payment-success.php?order_id='.$order_id,
        'billCallbackUrl' => 'https://localhost/SD_PROJECT/payment-callback.php',
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

        $stmt = $conn->prepare('UPDATE orders SET BillCode = ? WHERE order_id = ?');
        $stmt->bind_param('ss', $billCode, $order_id);
        if ($stmt->execute()) {
        }
        $stmt->close();

        return 'https://dev.toyyibpay.com/'.$billCode;
    } else {
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
    <div class="container mt-4">
        <h1 class="text-center">Order Summary</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Order ID: <?php echo htmlspecialchars($order_id); ?></h5>
                <p>Email: <?php echo htmlspecialchars($email); ?></p>
                <p>Name: <?php echo htmlspecialchars($name); ?></p>
                <p>Contact: <?php echo htmlspecialchars($contact); ?></p>
                <h6>Specifications:</h6>
                <ul>
                    <?php foreach ($specification_data as $data) { ?>
                        <li>Specification ID: <?php echo htmlspecialchars($data['id']); ?>, Price: RM<?php echo number_format($data['price'], 2); ?>, Total Price: RM<?php echo number_format($data['total_price'], 2); ?></li>
                    <?php } ?>
                </ul>
                <h5>Total Price: RM<?php echo number_format($total_price, 2); ?></h5>
                <form method="POST" action="">
                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                    <button type="submit" name="proceed_to_payment" class="btn btn-primary">Proceed to Payment</button>
                    <button type="submit" name="delete_order" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this order?');">Delete Order</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
