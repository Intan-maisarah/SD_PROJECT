<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo 'User not logged in.<br>';
    exit;
}

require '../../connection.php';

$user_id = $_SESSION['user_id'];
$adminEmail = '';
$usertype = '';
$profile_pic = '';

$sql = 'SELECT email, usertype, profile_pic FROM users WHERE id = ?';
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo 'Prepare statement failed: '.$conn->error.'<br>';
    exit;
}
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    echo 'Get result failed: '.$stmt->error.'<br>';
    exit;
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $adminEmail = $row['email'];
    $usertype = $row['usertype'];
    $profile_pic = $row['profile_pic'];
} else {
    echo 'User not found.<br>';
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="admin, dashboard, printing service" />
  <meta name="description" content="Admin page for staff management" />
  <meta name="robots" content="noindex,nofollow" />
  <title>Order Management</title>
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />
  <!-- Custom CSS -->
  <link href="../dist/css/style.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link href="style.css" rel="stylesheet">
 
</head>

<body>
  <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-header-position="absolute">
    
  <?php include '../sidebar/header.php'; ?>

    <!-- Sidebar -->
    <?php
    if ($usertype === 'ADMIN') {
        include '../sidebar/sidebarAdmin.php';
    } else {
        include '../sidebar/sidebarStaff.php';
    }
?>
    
    <!-- Page wrapper -->
    <div class="page-wrapper">
   <?php
include '../../connection.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'view';

switch ($action) {
    case 'view':
        $filterApplied = false;
        $statusFilter = isset($_POST['status_filter']) ? $_POST['status_filter'] : '';

        $query = '
            SELECT 
                orders.order_id, 
                GROUP_CONCAT(order_documents.document_upload SEPARATOR " / ") AS document_names,
                orders.status, 
                orders.payment_status, 
                orders.delivery_method,
                orders.created_at
            FROM orders
            LEFT JOIN order_documents ON orders.order_id = order_documents.order_id';

        if ($statusFilter) {
            $query .= ' WHERE ';
            if ($statusFilter === 'unpaid') {
                $query .= 'orders.payment_status = "UNPAID"';
            } elseif ($statusFilter === 'paid') {
                $query .= 'orders.payment_status = "PAID"';
            } elseif ($statusFilter === 'pendingpayment') {
                $query .= 'orders.payment_status = "PENDING"';
            } elseif ($statusFilter === 'pending') {
                $query .= '(orders.payment_status = "PAID" OR orders.payment_status = "PENDING") AND orders.status = "pending"';
            } elseif ($statusFilter === 'in_progress') {
                $query .= '(orders.payment_status = "PAID" OR orders.payment_status = "PENDING") AND orders.status = "in_progress"';
            } elseif ($statusFilter === 'completed') {
                $query .= '(orders.payment_status = "PAID" OR orders.payment_status = "PENDING") AND orders.status = "completed"';
            } elseif ($statusFilter === 'pickup') {
                $query .= '(orders.payment_status = "PAID" OR orders.payment_status = "PENDING") AND orders.delivery_method = "pickup"';
            } elseif ($statusFilter === 'delivery') {
                $query .= '(orders.payment_status = "PAID" OR orders.payment_status = "PENDING") AND orders.delivery_method = "delivery"';
            }
        }

        $query .= ' GROUP BY orders.order_id ORDER BY orders.created_at DESC';

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            echo 'Prepare failed: '.$conn->error;
            exit;
        }
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<h2>Order List</h2>';

        echo '<form method="POST" action="order.php?action=view" style="margin-bottom: 20px;">';
        echo '<label for="status_filter">Filter by Status:</label>';
        echo '<select name="status_filter" id="status_filter">';
        echo '<option value="">All</option>';
        echo '<option value="paid" '.($statusFilter == 'PAID' ? 'selected' : '').'>Paid</option>';
        echo '<option value="unpaid" '.($statusFilter == 'UNPAID' ? 'selected' : '').'>Unpaid</option>';
        echo '<option value="pendingpayment" '.($statusFilter == 'PENDING' ? 'selected' : '').'>Payment Pending (Cash)</option>';
        echo '<option value="pending" '.($statusFilter == 'pending' ? 'selected' : '').'>Pending</option>';
        echo '<option value="in_progress" '.($statusFilter == 'in_progress' ? 'selected' : '').'>In Progress</option>';
        echo '<option value="completed" '.($statusFilter == 'completed' ? 'selected' : '').'>Completed</option>';
        echo '<option value="pickup" '.($statusFilter == 'pickup' ? 'selected' : '').'>Pickup</option>';
        echo '<option value="delivery" '.($statusFilter == 'delivery' ? 'selected' : '').'>Delivery</option>';
        echo '</select>';
        echo '<button type="submit" class="btn btn-primary">Filter</button>';
        echo '</form>';

        if ($statusFilter === 'unpaid') {
            echo '<form method="POST" action="order.php?action=delete_all_unpaid" style="margin-bottom: 20px;">';
            echo '<button type="submit" class="btn btn-danger">Delete All Unpaid Orders</button>';
            echo '</form>';
        }

        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-'.$_SESSION['msg_type'].' alert-dismissible fade show" role="alert">';
            echo $_SESSION['message'];
            echo '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
        }

        echo "<div class='table-container'>";
        echo '<table>';
        echo '<tr><th>ID</th><th>Documents</th><th>Status</th><th>Payment Status</th><th>Action</th></tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.htmlspecialchars($row['order_id']).'</td>';

            // Document names handling
            if ($row['document_names'] !== null) {
                $documentNames = explode(' / ', $row['document_names']);
                $formattedDocumentNames = implode('<br>', array_map(function ($doc) {
                    return htmlspecialchars(basename($doc));
                }, $documentNames));
            } else {
                $formattedDocumentNames = 'No documents uploaded';
            }
            echo '<td>'.$formattedDocumentNames.'</td>';

            // Status dropdown
            echo '<td>
                <form method="POST" action="order.php?action=update_status" style="display:inline;"> 
                    <input type="hidden" name="order_id" value="'.htmlspecialchars($row['order_id']).'">
                    <select name="status" onchange="this.form.submit()">
                        <option value="pending" '.($row['status'] == 'pending' ? 'selected' : '').'>Pending</option>
                        <option value="in_progress" '.($row['status'] == 'in_progress' ? 'selected' : '').'>In Progress</option>
                        <option value="completed" '.($row['status'] == 'completed' ? 'selected' : '').'>Completed</option>
                    </select>
                </form>
              </td>';

            // Payment status
            echo '<td>'.htmlspecialchars($row['payment_status']).'</td>';

            // Action buttons
            if ($row['payment_status'] == 'unpaid') {
                echo "<td>
                        <form method='POST' action='order.php?action=delete' style='display:inline;'>
                            <input type='hidden' name='order_id' value='".htmlspecialchars($row['order_id'])."'>
                            <button type='submit' class='btn btn-danger'>Delete</button>
                        </form>
                      </td>";
            } else {
                echo "<td>
                      <a href='order.php?action=viewall&id=".htmlspecialchars($row['order_id'])."' class='button button-edit'>View Detail</a>
                      </td>";
            }
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';

        $stmt->close();
        break;

    case 'viewall':
        if (isset($_GET['id'])) {
            $order_id = $_GET['id'];

            // Fetch the main order details
            $query_order = 'SELECT * FROM orders WHERE order_id = ?';
            $stmt_order = $conn->prepare($query_order);
            $stmt_order->bind_param('s', $order_id);
            $stmt_order->execute();
            $result_order = $stmt_order->get_result();
            $order = $result_order->fetch_assoc();

            function getLocationName($location_id)
            {
                global $conn;
                $stmt = $conn->prepare('SELECT location_name FROM delivery_locations WHERE id = ?');
                $stmt->bind_param('i', $location_id);
                $stmt->execute();
                $stmt->bind_result($location_name);
                $stmt->fetch();
                $stmt->close();

                return $location_name ? $location_name : 'Unknown Location';
            }

            function getCustomerName($user_id)
            {
                global $conn;
                $stmt = $conn->prepare('SELECT name FROM users WHERE id = ?');
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $stmt->bind_result($customer_name);
                $stmt->fetch();
                $stmt->close();

                return $customer_name ? $customer_name : 'Unknown Customer';
            }

            if ($order) {
                echo "
<div class='d-flex justify-content-start align-items-center'>
    <h2 class='mb-0 me-3'>Order Details</h2>
    <button type='button' onclick=\"window.location.href='order.php?action=view'\" class='btn btn-secondary'>Back</button>
</div>
";

                echo '<form method="POST" action="" id="orderForm">';
                echo '<table>';
                echo '<tr><th>Order ID</th><td>'.htmlspecialchars($order['order_id'] ?? 'N/A').'</td></tr>';

                $customer_name = getCustomerName($order['user_id'] ?? 0);
                echo '<tr><th>Customer Name</th><td>'.htmlspecialchars($customer_name).'</td></tr>';

                echo '<tr><th>Created At</th><td>'.htmlspecialchars($order['created_at'] ?? 'N/A').'</td></tr>';
                echo '<tr><th>Payment Status</th><td>';
                echo '<select name="payment_status" onchange="document.getElementById(\'orderForm\').submit();">';
                echo '<option value="PENDING"'.(($order['payment_status'] ?? '') === 'PENDING' ? ' selected' : '').'>PENDING</option>';
                echo '<option value="PAID"'.(($order['payment_status'] ?? '') === 'PAID' ? ' selected' : '').'>PAID</option>';
                echo '<option value="UNPAID"'.(($order['payment_status'] ?? '') === 'UNPAID' ? ' selected' : '').'>UNPAID</option>';
                echo '</select>';
                echo '</td></tr>';
                echo '<tr><th>Bill Code</th><td>'.htmlspecialchars($order['BillCode'] ?? 'N/A').'</td></tr>';
                echo '<tr><th>Total Order Price</th><td>RM'.htmlspecialchars($order['total_order_price'] ?? '0.00').'</td></tr>';
                echo '<tr><th>Status</th><td>';
                echo '<select name="status" onchange="document.getElementById(\'orderForm\').submit();">';
                echo '<option value="pending"'.(($order['status'] ?? '') === 'pending' ? ' selected' : '').'>Pending</option>';
                echo '<option value="in_progress"'.(($order['status'] ?? '') === 'in_progress' ? ' selected' : '').'>In Progress</option>';
                echo '<option value="completed"'.(($order['status'] ?? '') === 'completed' ? ' selected' : '').'>Completed</option>';
                echo '</select>';
                echo '</td></tr>';

                if (($order['delivery_method'] ?? '') == 'pickup') {
                    echo '<tr><th>Pickup Appointment</th><td>'.(!empty($order['pickup_appointment']) ? htmlspecialchars($order['pickup_appointment']) : 'N/A').'</td></tr>';
                } elseif (($order['delivery_method'] ?? '') == 'delivery') {
                    echo '<tr><th>Delivery Location</th><td>'.htmlspecialchars(getLocationName($order['delivery_location_id'] ?? 0)).'</td></tr>';
                    echo '<tr><th>Delivery Time</th><td>'.htmlspecialchars($order['delivery_time'] ?? 'N/A').'</td></tr>';
                }

                echo '</table>';
                echo '<input type="hidden" name="order_id" value="'.htmlspecialchars($order['order_id'] ?? '').'">';
                echo '<input type="hidden" name="update_status" value="1">';
                echo '</form>';

                // Fetch and display all documents related to this order
                $query_docs = 'SELECT * FROM order_documents WHERE order_id = ?';
                $stmt_docs = $conn->prepare($query_docs);
                $stmt_docs->bind_param('s', $order_id);
                $stmt_docs->execute();
                $result_docs = $stmt_docs->get_result();

                if ($result_docs->num_rows > 0) {
                    echo '<h3>Uploaded Documents</h3>';
                    echo '<div class="documents-container">';
                    while ($doc_row = $result_docs->fetch_assoc()) {
                        $document_path = htmlspecialchars($doc_row['document_upload'] ?? '');
                        $relative_path = str_replace('Admin_Dashboard/service/', '', $document_path);
                        $file_name = basename($document_path);

                        echo '<div class="document-box" style="margin-bottom: 20px;">';
                        echo '<h4>'.htmlspecialchars($file_name).'</h4>';
                        echo '<button class="btn btn-primary view-document-btn" data-document="'.$relative_path.'" onclick="openDocumentModal(\''.$relative_path.'\')">View Document</button>';
                        echo '<a href="'.$document_path.'" class="btn btn-secondary" download>Download Document</a>';

                        // Fetch specifications for this document
                        $query_specs = 'SELECT spec_names.spec_name, specification.spec_type 
                                                    FROM order_details 
                                                    INNER JOIN specification ON order_details.specification_id = specification.id 
                                                    INNER JOIN spec_names ON specification.spec_name_id = spec_names.id 
                                                    WHERE order_details.document_id = ?';
                        $stmt_specs = $conn->prepare($query_specs);
                        $stmt_specs->bind_param('i', $doc_row['id']);
                        $stmt_specs->execute();
                        $result_specs = $stmt_specs->get_result();

                        if ($result_specs->num_rows > 0) {
                            echo '<table class="table mt-2">';
                            echo '<tr><th>Specification Name</th><th>Specification Type</th></tr>';
                            while ($spec_row = $result_specs->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>'.htmlspecialchars($spec_row['spec_name'] ?? 'N/A').'</td>';
                                echo '<td>'.htmlspecialchars($spec_row['spec_type'] ?? 'N/A').'</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                        }

                        echo '</div>'; // End document box
                        $stmt_specs->close();
                    }
                    echo '</div>'; // End documents container
                } else {
                    echo '<p>No documents found for this order.</p>';
                }

                // Modal to view documents
                echo '
                                <div class="modal fade" id="viewDocumentModal" tabindex="-1" role="dialog" aria-labelledby="viewDocumentModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewDocumentModalLabel">View Document</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <iframe id="documentIframe" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
            } else {
                echo '<p>Order not found.</p>';
            }
        } else {
            echo '<p>Invalid order ID.</p>';
        }
        break;

    case 'delete_all_unpaid':
        $deleteDetailsQuery = 'DELETE FROM order_details WHERE order_id IN (SELECT order_id FROM orders WHERE payment_status = "unpaid")';
        $conn->query($deleteDetailsQuery);

        $deleteAllQuery = 'DELETE FROM orders WHERE payment_status = "unpaid"';
        if ($conn->query($deleteAllQuery) === true) {
            $_SESSION['message'] = 'All unpaid orders deleted successfully.';
            $_SESSION['msg_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Failed to delete unpaid orders.';
            $_SESSION['msg_type'] = 'danger';
        }
        header('Location: order.php?action=view&status_filter=unpaid');
        exit;

    case 'update_status':
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
            $orderIdToUpdate = $_POST['order_id'];
            $newStatus = $_POST['status'];

            $updateQuery = 'UPDATE orders SET status = ? WHERE order_id = ?';
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('ss', $newStatus, $orderIdToUpdate);

            if ($updateStmt->execute()) {
                $_SESSION['message'] = 'Order status updated successfully.';
                $_SESSION['msg_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to update order status.';
                $_SESSION['msg_type'] = 'danger';
            }
            $updateStmt->close();
        }
        header('Location: order.php?action=view');
        exit;

    default:
        echo 'Invalid action.';
        break;
}

?>

        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../dist/js/app-style-switcher.js"></script>
  <script src="../dist/js/waves.js"></script>
  <script src="../dist/js/sidebarmenu.js"></script>
  <script src="../dist/js/custom.js"></script>
  <script>
  function openDocumentModal(documentPath) {
    $('#documentIframe').attr('src', documentPath + '?t=' + new Date().getTime());
    $('#viewDocumentModal').modal('show');
  }
</script>

</body>
</html>
