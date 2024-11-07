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
    $payment_method = $_POST['payment_method'] ?? '';

    if ($total_price > 0) {
        if ($payment_method === 'online') {
            if (!isset($_SESSION['specification_data']) || empty($_SESSION['specification_data'])) {
                echo 'Error: No specifications selected.';
            } else {
                // Create payment bill for online payment
                $billUrl = createToyyibPayBill($order_id, $email, $total_price, $name, $contact);
                if ($billUrl) {
                    unset($_SESSION['total_order_price'], $_SESSION['specification_data']);
                    header("Location: $billUrl");
                    exit;
                } else {
                    echo 'Error creating payment bill.';
                }
            }
        } elseif ($payment_method === 'offline') {
            // Handle offline payment
            if (handleOfflinePayment($order_id, $total_price, $email, $name, $contact)) {
                unset($_SESSION['total_order_price'], $_SESSION['specification_data'], $_SESSION['order_id']);
                header('Location: order-success.php?order_id='.urlencode($order_id));
                exit;
            } else {
                echo 'Error processing offline payment. Please try again.';
            }
        } else {
            echo 'Error: Unsupported payment method.';
        }
    } else {
        echo 'Error: total_order_price is not set or is zero.';
    }
}

function handleOfflinePayment($order_id, $total_price, $email, $name, $contact)
{
    global $conn;

    $stmt = $conn->prepare('UPDATE orders SET payment_status = ?, total_order_price = ?, payment_method = ? WHERE order_id = ?');
    $status = 'pending';
    $payment_method = 'offline';
    $stmt->bind_param('sdss', $status, $total_price, $payment_method, $order_id);

    if ($stmt->execute()) {
        $stmt->close();

        return true;
    } else {
        $stmt->close();

        return false;
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

    <style>
        /* Main container styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #94bdff;
            color: #333;
            padding-top: 20px;
        }
        .container {
            max-width: 800px;
            background-color: #f9f9f9;
            padding: 30px;
            margin-top: 50px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        /* Header styling */
        h2 {
            font-size: 2em;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Order ID and Delivery Method styling */
        p strong {
            font-size: 1.1em;
            color: #444;
        }

        h4 {
            color: #555;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Table styling */
        .table {
            margin-top: 20px;
            background-color: #fff;
        }

        .table th {
            background-color: #94bdff;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .table td {
            text-align: center;
            font-size: 0.95em;
            color: #555;
        }

        /* Total Price styling */
        h4.total-price {
            text-align: right;
            font-size: 1.5em;
            color: #28a745;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Payment and Delete buttons */
        .btn-primary {
            background-color: #7be07b;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 1.1em;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #29e329;
        }

        .btn-danger {
            background-color: #e07b7b;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 1.1em;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Payment method dropdown */
        .form-group label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
        }

        /* Footer message styling */
        .footer-message {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Order Summary</h2>
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>

    <h4>Delivery Method: <?php echo htmlspecialchars($delivery_method); ?></h4>

<?php if ($delivery_method === 'pickup') { ?>
    <p>Pickup Appointment: <?php echo htmlspecialchars($pickup_appointment); ?></p>
<?php } elseif ($delivery_method === 'delivery') { ?>
    <?php if (isset($location_name)) { ?>
        <p>Delivery Location: <?php echo htmlspecialchars($location_name); ?></p>
    <?php } else { ?>
        <p>Delivery Location: Not available</p>
    <?php } ?>
    <p>Delivery Time: <?php echo htmlspecialchars($delivery_time); ?></p>
    <p>Delivery Fee: RM 2.00</p>
<?php }

if (!empty($specification_data)) { ?>
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

            <!-- Payment Method Selection -->
            <div class="form-group">
                <label for="payment_method">Select Payment Method:</label>
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="online">Online Banking</option>
                    <option value="offline">Cash</option>
                </select>
            </div>

            <button type="submit" name="proceed_to_payment" class="btn btn-primary" onclick="return checkPaymentMethod();">Proceed to Payment</button>
            <button type="submit" name="delete_order" class="btn btn-danger" onclick="return confirmDelete();">Cancel Order</button>
        </form>
    <?php } else { ?>
        <p>No specifications found for this order.</p>
    <?php } ?>
</div>


<script>
function confirmDelete() {
    return confirm("Are you sure you want to cancel this order?");
}

function checkPaymentMethod() {
    var paymentMethod = document.getElementById('payment_method').value;
    var totalPrice = <?php echo $total_price; ?>; 

    if (paymentMethod === "") {
        alert("Please select a payment method."); 
        return false; 
    }

    if (paymentMethod === 'online' && totalPrice < 1) {
        alert("For online payments, the total must be greater than zero.");
        return false; 
    }

    return true; 
}


function showCashOnlyAlert() {
    alert("For online payments, the total must be greater than zero.");
}


</script>


</body>
</html>
