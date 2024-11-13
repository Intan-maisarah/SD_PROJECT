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

// Ensure POST data is available
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $order_id) {
    $document_paths = $_POST['document_path'] ?? []; // Array of document paths
    $specification_types_flat = $_POST['specification_id'] ?? []; // Flat array of specification types for all documents
    $quantities = $_POST['quantity'] ?? []; // Array of quantities for each document
    $page_counts = $_POST['page_count'] ?? []; // Array of page counts for each document

    if (!empty($document_paths) && !empty($specification_types_flat)) {
        $checkOrderStmt = $conn->prepare('SELECT COUNT(*) FROM orders WHERE order_id = ?');
        $checkOrderStmt->bind_param('s', $order_id);
        $checkOrderStmt->execute();
        $checkOrderStmt->bind_result($count);
        $checkOrderStmt->fetch();
        $checkOrderStmt->close();

        if ($count > 0) {
            $total_price = 0;
            $specification_data = [];

            $num_documents = count($document_paths);
            $num_specifications = count($specification_types_flat);

            // Calculate specifications per document dynamically
            if ($num_documents > 0 && $num_specifications > 0) {
                $specification_types_per_document = intval($num_specifications / $num_documents);

                // Ensure specifications are evenly distributed across documents
                if ($specification_types_per_document * $num_documents !== $num_specifications) {
                    echo 'Error: Specification count does not match the number of documents evenly.<br>';
                    error_log("Error: Specification count does not match the number of documents for order_id {$order_id}");

                    return;
                }

                foreach ($document_paths as $index => $document_path) {
                    // Fetch the document ID from the order_documents table
                    $stmt = $conn->prepare('SELECT id FROM order_documents WHERE document_upload = ? AND order_id = ?');
                    $stmt->bind_param('ss', $document_path, $order_id);
                    $stmt->execute();
                    $stmt->bind_result($document_id);
                    $stmt->fetch();
                    $stmt->close();

                    if ($document_id) {
                        // Extract the specification types for this document
                        $start = $index * $specification_types_per_document;
                        $document_spec_types = array_slice($specification_types_flat, $start, $specification_types_per_document);

                        $quantity = $quantities[$index] ?? 1;
                        $page_count = $page_counts[$index] ?? 1;

                        foreach ($document_spec_types as $specification_type) {
                            // Fetch specification ID and price from the database
                            $stmt = $conn->prepare('SELECT id, price FROM specification WHERE spec_type = ?');
                            $stmt->bind_param('s', $specification_type);
                            $stmt->execute();
                            $stmt->bind_result($specification_id, $spec_price);

                            if ($stmt->fetch()) {
                                $total_doc_price = $spec_price * $page_count * $quantity;
                                $total_price += $total_doc_price;

                                // Debugging: Log specification details
                                error_log("Specification ID: {$specification_id}, Price: {$spec_price}, Total Price for Document: {$total_doc_price}");

                                $stmt->close();

                                // Insert order details linked to the document ID
                                $stmt = $conn->prepare('INSERT INTO order_details (order_id, specification_id, price, quantity, page_count, total_price, document_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
                                $stmt->bind_param('sidiidi', $order_id, $specification_id, $spec_price, $quantity, $page_count, $total_doc_price, $document_id);
                                if (!$stmt->execute()) {
                                    error_log("Failed to insert order details for Document ID: {$document_id}, Error: ".$stmt->error);
                                } else {
                                    error_log("Order Details Inserted for Document ID: {$document_id}");
                                }
                                $specification_data[] = [
                                    'id' => $specification_id,
                                    'price' => $spec_price,
                                    'total_price' => $total_doc_price,
                                    'document_id' => $document_id,
                                ];
                                $stmt->close();
                            } else {
                                error_log("Failed to fetch specification for type: {$specification_type}");
                            }
                        }
                    } else {
                        error_log("Document ID not found for document path: {$document_path}");
                    }
                }

                if ($delivery_method === 'pickup') {
                    // Updating the order for pickup method
                    if (!empty($pickup_appointment)) {
                        $insertPickupStmt = $conn->prepare('UPDATE orders SET delivery_method = ?, pickup_appointment = ? WHERE order_id = ?');
                        if ($insertPickupStmt) {
                            $insertPickupStmt->bind_param('sss', $delivery_method, $pickup_appointment, $order_id);
                            if (!$insertPickupStmt->execute()) {
                                error_log("Failed to update pickup appointment for order_id {$order_id}, Error: ".$insertPickupStmt->error);
                            }
                            $insertPickupStmt->close();
                        } else {
                            error_log("Failed to prepare statement for updating pickup appointment for order_id {$order_id}");
                        }
                    } else {
                        echo 'No pickup appointment provided.';
                    }
                } elseif ($delivery_method === 'delivery') {
                    // Updating the order for delivery method
                    $total_price += 2; // Adding delivery fee to total price

                    if (!$delivery_location_id) {
                        echo 'No delivery location ID provided.';
                    } else {
                        // Fetch the location name to validate it exists
                        $stmt = $conn->prepare('SELECT location_name FROM delivery_locations WHERE id = ?');
                        if ($stmt) {
                            $stmt->bind_param('i', $delivery_location_id);
                            $stmt->execute();
                            $stmt->bind_result($location_name);

                            if ($stmt->fetch()) {
                                $stmt->close();

                                // Update order with delivery details
                                $delivery_time = $_POST['delivery_time'] ?? '';
                                $updateOrderStmt = $conn->prepare('UPDATE orders SET delivery_method = ?, delivery_location_id = ?, delivery_time = ? WHERE order_id = ?');
                                if ($updateOrderStmt) {
                                    $updateOrderStmt->bind_param('siss', $delivery_method, $delivery_location_id, $delivery_time, $order_id);
                                    if (!$updateOrderStmt->execute()) {
                                        error_log("Failed to update delivery details for order_id {$order_id}, Error: ".$updateOrderStmt->error);
                                    }
                                    $updateOrderStmt->close();
                                } else {
                                    error_log("Failed to prepare statement for updating delivery details for order_id {$order_id}");
                                }
                            } else {
                                echo 'Invalid delivery location ID.';
                                $stmt->close();
                            }
                        } else {
                            error_log("Failed to prepare statement to fetch delivery location for ID {$delivery_location_id}");
                        }
                    }
                } else {
                    echo 'Invalid delivery method.';
                }

                // Update total order price in the orders table
                if ($total_price > 0) {
                    $updateOrderStmt = $conn->prepare('UPDATE orders SET total_order_price = ? WHERE order_id = ?');
                    $updateOrderStmt->bind_param('ds', $total_price, $order_id);
                    if (!$updateOrderStmt->execute()) {
                        error_log("Failed to update total order price for order_id {$order_id}, Error: ".$updateOrderStmt->error);
                    } else {
                        error_log("Total order price updated for order_id {$order_id}, Total Price: {$total_price}");
                    }
                    $_SESSION['total_order_price'] = $total_price;
                    $_SESSION['specification_data'] = $specification_data;
                    $updateOrderStmt->close();
                } else {
                    echo 'Error: total_price is zero, check specification and pricing data.<br>';
                    error_log("Error: total_price is zero for order_id {$order_id}. Check specification and pricing data.");
                }
            } else {
                echo 'Error: No documents or specifications available for processing.<br>';
                error_log("Error: No documents or specifications available for processing for order_id {$order_id}");
            }
        } else {
            echo 'Error: Order not found.<br>';
            error_log("Error: No matching order found for order_id {$order_id}");
        }
    } else {
        echo 'Error: No specifications or documents selected.<br>';
        error_log("Error: No specifications or documents selected for order_id {$order_id}");
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
                $billUrl = createToyyibPayBill($order_id, $email, $total_price, $name, $contact);

                if ($billUrl && strpos($billUrl, 'toyyibpay.com') !== false) {
                    // Output JavaScript to redirect if header() fails
                    echo '<script type="text/javascript">';
                    echo 'window.location.href = "'.htmlspecialchars($billUrl).'";';
                    echo '</script>';
                    echo '<p>Redirecting to payment... (If nothing happens, click <a href="'.htmlspecialchars($billUrl).'">here</a>)</p>';
                    error_log("Redirecting to: $billUrl");
                    exit;
                } else {
                    // Log an error if the generated bill URL is incorrect
                    error_log("Error: Generated URL is incorrect or missing. Bill URL: $billUrl");
                    echo '<p>Error creating payment bill. Please try again later or contact support. Check logs for more details.</p>';
                }
            }
        } elseif ($payment_method === 'offline') {
            // Handle offline payment
            if (handleOfflinePayment($order_id, $total_price, $email, $name, $contact)) {
                unset($_SESSION['total_order_price'], $_SESSION['specification_data'], $_SESSION['order_id']);
                header('Location: customer/order-success.php?order_id='.urlencode($order_id));
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
        'billReturnUrl' => 'https://palegreen-buffalo-300863.hostingersite.com/customer/payment-success.php?order_id='.$order_id,
        'billCallbackUrl' => 'https://palegreen-buffalo-300863.hostingersite.com/customer/payment-callback.php',
        'billPriceSetting' => '1',
        'billPayorInfo' => '1',
    ];

    // Log request data for debugging
    error_log('ToyyibPay Request Data: '.print_r($data, true));

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        // Log any cURL errors
        error_log("CURL Error: $error");

        return false;
    }

    // Log API response for debugging
    error_log("ToyyibPay Response: $response");

    $responseArray = json_decode($response, true);

    // Log if JSON decoding fails
    if ($responseArray === null && json_last_error() !== JSON_ERROR_NONE) {
        error_log('JSON Decode Error: '.json_last_error_msg());

        return false;
    }

    if (isset($responseArray[0]['BillCode'])) {
        $billCode = $responseArray[0]['BillCode'];

        // Update the database with the BillCode
        $stmt = $conn->prepare('UPDATE orders SET BillCode = ? WHERE order_id = ?');
        if ($stmt) {
            $stmt->bind_param('ss', $billCode, $order_id);
            if (!$stmt->execute()) {
                error_log('Database Error (Updating BillCode): '.$stmt->error);
            }
            $stmt->close();
        } else {
            error_log('Database Statement Preparation Error: '.$conn->error);
        }

        // Correctly return the ToyyibPay URL
        $paymentUrl = 'https://dev.toyyibpay.com/'.$billCode;
        error_log("Generated Payment URL: $paymentUrl");

        return $paymentUrl;
    } else {
        // Log the error if BillCode is missing
        error_log('ToyyibPay API Error: Invalid response - '.print_r($responseArray, true));

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
    <link rel="stylesheet" href="assets/order.css">
</head>
<body class="order-summary-body">
<div class="order-summary-container">
    <h2 class="order-summary-title">Order Summary</h2>
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>

    <h4 class="order-summary-delivery">Delivery Method: <?php echo htmlspecialchars($delivery_method); ?></h4>

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
    <?php } ?>

    <?php if (!empty($_SESSION['specification_data'])) { ?>
        <table class="order-summary-table table table-bordered">
            <thead>
                <tr>
                    <th>Document Path</th>
                    <th>Specifications</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $groupedSpecs = [];
        foreach ($_SESSION['specification_data'] as $spec) {
            $document_id = $spec['document_id'];

            // Fetch document path
            $stmt = $conn->prepare('SELECT document_upload FROM order_documents WHERE id = ?');
            $stmt->bind_param('i', $document_id);
            $stmt->execute();
            $stmt->bind_result($document_path);
            $stmt->fetch();
            $stmt->close();

            // Fetch specification details
            $stmt = $conn->prepare('SELECT sn.spec_name AS spec_name, s.spec_type AS spec_type FROM specification s JOIN spec_names sn ON s.spec_name_id = sn.id WHERE s.id = ?');
            $stmt->bind_param('i', $spec['id']);
            $stmt->execute();
            $stmt->bind_result($spec_name, $spec_type);
            $stmt->fetch();
            $stmt->close();

            // Group specifications by document path
            $cleaned_file_name = basename($document_path);
            $groupedSpecs[$cleaned_file_name][] = [
                'spec_name' => $spec_name,
                'spec_type' => $spec_type,
            ];
        }

        // Display grouped specifications
        foreach ($groupedSpecs as $document => $specs) {
            echo '<tr>';
            echo '<td>'.htmlspecialchars($document).'</td>';
            echo '<td>';
            foreach ($specs as $spec) {
                echo '<strong>'.htmlspecialchars($spec['spec_name']).':</strong> '.htmlspecialchars($spec['spec_type']).'<br>';
            }
            echo '</td>';
            echo '</tr>';
        }
        ?>
            </tbody>
        </table>

        <h4 class="order-summary-total-price">Total Price: RM <?php echo number_format($total_price, 2); ?></h4>

        <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">

            <div class="form-group">
                <label for="payment_method">Select Payment Method:</label>
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="online">Online Banking</option>
                    <option value="offline">Cash</option>
                </select>
            </div>

            <button type="submit" name="proceed_to_payment" class="order-summary-btn-primary" onclick="return checkPaymentMethod();">Proceed to Payment</button>
            <button type="submit" name="delete_order" class="order-summary-btn-danger" onclick="return confirmDelete();">Cancel Order</button>
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
</script>

</body>
</html>
