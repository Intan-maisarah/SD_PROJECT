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

        // Base query to fetch orders
        $query = 'SELECT order_id, document_upload, status, payment_status, delivery_method FROM orders';

        // Apply filters if selected
        if ($statusFilter) {
            $queryConditions = [];

            if ($statusFilter === 'unpaid') {
                $queryConditions[] = 'payment_status = "UNPAID"';
            } elseif ($statusFilter === 'paid') {
                $queryConditions[] = 'payment_status = "PAID"';
            } elseif ($statusFilter === 'pendingpayment') {
                $queryConditions[] = 'payment_status = "PENDING"';
            } elseif ($statusFilter === 'pending') {
                $queryConditions[] = 'status = "pending"';
            } elseif ($statusFilter === 'in_progress') {
                $queryConditions[] = 'status = "in_progress"';
            } elseif ($statusFilter === 'completed') {
                $queryConditions[] = 'status = "completed"';
            } elseif ($statusFilter === 'pickup') {
                $queryConditions[] = 'delivery_method = "pickup"';
            } elseif ($statusFilter === 'delivery') {
                $queryConditions[] = 'delivery_method = "delivery"';
            }

            // Add conditions to the query
            if (!empty($queryConditions)) {
                $query .= ' WHERE '.implode(' AND ', $queryConditions);
            }

            $filterApplied = true;
        }

        // Add sorting by creation date
        $query .= ' ORDER BY created_at DESC';

        // Prepare and execute query
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<h2>Order List</h2>';

        echo '<form method="POST" action="order.php?action=view" style="margin-bottom: 20px;">';
        echo '<label for="status_filter">Filter by Status:</label>';
        echo '<select name="status_filter" id="status_filter">';
        echo '<option value="">All</option>';
        echo '<option value="paid" '.($statusFilter == 'paid' ? 'selected' : '').'>Paid</option>';
        echo '<option value="unpaid" '.($statusFilter == 'unpaid' ? 'selected' : '').'>Unpaid</option>';
        echo '<option value="pendingpayment" '.($statusFilter == 'pendingpayment' ? 'selected' : '').'>Payment Pending (Cash)</option>';
        echo '<option value="pending" '.($statusFilter == 'pending' ? 'selected' : '').'>Pending</option>';
        echo '<option value="in_progress" '.($statusFilter == 'in_progress' ? 'selected' : '').'>In Progress</option>';
        echo '<option value="completed" '.($statusFilter == 'completed' ? 'selected' : '').'>Completed</option>';
        echo '<option value="pickup" '.($statusFilter == 'pickup' ? 'selected' : '').'>Pickup</option>';
        echo '<option value="delivery" '.($statusFilter == 'delivery' ? 'selected' : '').'>Delivery</option>';
        echo '</select>';
        echo '<button type="submit" class="btn btn-primary">Filter</button>';
        echo '</form>';

        // Delete all unpaid orders form
        if ($statusFilter === 'unpaid') {
            echo '<form method="POST" action="order.php?action=delete_all_unpaid" style="margin-bottom: 20px;">';
            echo '<button type="submit" class="btn btn-danger">Delete All Unpaid Orders</button>';
            echo '</form>';
        }

        // Display messages
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-'.$_SESSION['msg_type'].' alert-dismissible fade show" role="alert">';
            echo $_SESSION['message'];
            echo '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
        }

        echo "<div class='table-container'>";
        echo '<table>';
        echo '<tr><th>ID</th><th>Document</th><th>Status</th><th>Payment Status</th><th>Action</th></tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row['order_id'].'</td>';
            $full_document_upload = $row['document_upload'];
            $file_name_with_ext = basename($full_document_upload);
            $cleaned_file_name = preg_replace('/^.*?_(.*)$/', '$1', $file_name_with_ext);
            echo '<td>'.$cleaned_file_name.'</td>';

            // Status dropdown
            echo '<td>
                <form method="POST" action="order.php?action=update_status" style="display:inline;">
                    <input type="hidden" name="order_id" value="'.$row['order_id'].'">
                    <select name="status" onchange="this.form.submit()">
                        <option value="pending" '.($row['status'] == 'pending' ? 'selected' : '').'>Pending</option>
                        <option value="in_progress" '.($row['status'] == 'in_progress' ? 'selected' : '').'>In Progress</option>
                        <option value="completed" '.($row['status'] == 'completed' ? 'selected' : '').'>Completed</option>
                    </select>
                </form>
              </td>';

            // Payment status
            echo '<td>'.$row['payment_status'].'</td>';

            // Action buttons based on payment status
            if ($row['payment_status'] == 'UNPAID') {
                echo "<td>
                        <form method='POST' action='order.php?action=delete' style='display:inline;'>
                            <input type='hidden' name='order_id' value='".$row['order_id']."'>
                            <button type='submit' class='btn btn-danger'>Delete</button>
                        </form>
                      </td>";
            } else {
                echo "<td>
                      <a href='order.php?action=viewall&id=".$row['order_id']."' class='button button-edit'>View Detail</a>
                      </td>";
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';

        // Delete a single order
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && $_GET['action'] === 'delete') {
            $orderIdToDelete = $_POST['order_id'];
            $deleteQuery = 'DELETE FROM orders WHERE order_id = ?';
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param('s', $orderIdToDelete);
            if ($deleteStmt->execute()) {
                $_SESSION['message'] = 'Order deleted successfully.';
                $_SESSION['msg_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to delete order.';
                $_SESSION['msg_type'] = 'danger';
            }
            $deleteStmt->close();
            header('Location: order.php?action=view');
            exit;
        }

        // Delete all unpaid orders
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] === 'delete_all_unpaid') {
            $deleteAllQuery = 'DELETE FROM orders WHERE payment_status = "UNPAID"';
            if ($conn->query($deleteAllQuery) === true) {
                $_SESSION['message'] = 'All unpaid orders deleted successfully.';
                $_SESSION['msg_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Failed to delete unpaid orders.';
                $_SESSION['msg_type'] = 'danger';
            }
            header('Location: order.php?action=view&status_filter=unpaid');
            exit;
        }

        $stmt->close();
        break;

    case 'viewall':
        if (isset($_GET['id'])) {
            $order_id = $_GET['id'];

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
                echo '<h2>Order Details</h2>';
                echo '<table>';
                echo '<tr><th>Order ID</th><td>'.$order['order_id'].'</td></tr>';

                $customer_name = getCustomerName($order['user_id']);
                echo '<tr><th>Customer Name</th><td>'.$customer_name.'</td></tr>';

                $full_document_upload = $order['document_upload'];
                $file_name_with_ext = basename($full_document_upload);
                $cleaned_file_name = preg_replace('/^.*?_(.*)$/', '$1', $file_name_with_ext);
                $document_path = 'document_upload/'.$file_name_with_ext;
                echo '<tr><th>Document Uploaded</th><td>'.$cleaned_file_name.'
                        <button class="button button-edit" data-toggle="modal" data-target="#viewDocumentModal" data-document="'.$document_path.'" style="margin-left: 10px;">View Document</button>
                        </td></tr>';

                echo '<tr><th>Created At</th><td>'.$order['created_at'].'</td></tr>';
                echo '<tr><th>Payment Status</th><td>'.$order['payment_status'].'</td></tr>';
                echo '<tr><th>Bill Code</th><td>'.$order['BillCode'].'</td></tr>';
                echo '<tr><th>Total Order Price</th><td>RM'.$order['total_order_price'].'</td></tr>';
                echo '<tr><th>Status</th><td>'.$order['status'].'</td></tr>';

                if ($order['delivery_method'] == 'pickup') {
                    echo '<tr><th>Pickup Appointment</th><td>'.$order['pickup_appointment'].'</td></tr>';
                } elseif ($order['delivery_method'] == 'delivery') {
                    $location_name = getLocationName($order['delivery_location_id']);
                    echo '<tr><th>Delivery Location</th><td>'.$location_name.'</td></tr>';
                    echo '<tr><th>Delivery Time</th><td>'.$order['delivery_time'].'</td></tr>';
                }

                echo '</table>';

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

                // Display specifications
                $displayedSpecifications = [];
                $query_specs = 'SELECT spec_names.spec_name, specification.spec_type 
                        FROM order_details 
                        INNER JOIN specification ON order_details.specification_id = specification.id 
                        INNER JOIN spec_names ON specification.spec_name_id = spec_names.id 
                        WHERE order_details.order_id = ?';

                $stmt_specs = $conn->prepare($query_specs);
                $stmt_specs->bind_param('s', $order_id);
                $stmt_specs->execute();
                $result_specs = $stmt_specs->get_result();

                if ($result_specs->num_rows > 0) {
                    echo '<h3>Specifications</h3>';
                    echo '<table>';
                    echo '<tr><th>Specification Name</th><th>Specification Type</th></tr>';
                    while ($spec_row = $result_specs->fetch_assoc()) {
                        if (!in_array($spec_row['spec_name'], $displayedSpecifications)) {
                            echo '<tr>';
                            echo '<td>'.$spec_row['spec_name'].'</td>';
                            echo '<td>'.$spec_row['spec_type'].'</td>';
                            echo '</tr>';
                            $displayedSpecifications[] = $spec_row['spec_name'];
                        }
                    }
                    echo '</table>';
                }

                echo "<button onclick=\"history.go(-1);\" class='button button-back'>Back</button>";

                $stmt_specs->close();
            } else {
                echo 'Order not found.<br>';
            }
        } else {
            echo 'Invalid order ID.<br>';
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
  $('#viewDocumentModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var documentPath = button.data('document'); 
    var modal = $(this);
    modal.find('#documentIframe').attr('src', documentPath); 
});
</script>

</body>
</html>
