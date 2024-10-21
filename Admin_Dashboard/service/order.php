<?php
ob_start();
?>

<?php

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
        $query = 'SELECT order_id, document_upload, status FROM `order`';
        $result = mysqli_query($conn, $query);

        echo '<h2>Order List</h2>';

        if (isset($_SESSION['message'])) { ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
            </div>
        <?php
        unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
        }

        echo "<div class='table-container'>";
        echo '<table>';
        echo '<tr><th>ID</th><th>Document</th><th>Status</th><th>Action</th></tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>'.$row['order_id'].'</td>';
            echo '<td>'.$row['document_upload'].'</td>';
            echo '<td>'.$row['status'].'</td>';
            echo "<td>
                  <a href='order.php?action=viewall&id=".$row['order_id']."' class='button button-add'>View</a> |
                <a href='order.php?action=update&id=".$row['order_id']."' class='button button-edit'>Update</a>
                </td>";
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
        break;

    case 'viewall':
        if (isset($_GET['id'])) {
            $order_id = $_GET['id'];
            $query = "SELECT * FROM `order` WHERE order_id = $order_id";
            $result = mysqli_query($conn, $query);
            $order = mysqli_fetch_assoc($result);

            if ($order) {
                echo '<h2>Order Details</h2>';
                echo '<table>';
                echo '<tr><th>Order ID</th><td>'.$order['order_id'].'</td></tr>';
                echo '<tr><th>Customer Name</th><td>'.$order['customer_name'].'</td></tr>';
                echo '<tr><th>Document Uploaded</th><td>'.$order['document_upload'].'</td></tr>';
                echo '<tr><th>Order Date</th><td>'.$order['order_date'].'</td></tr>';
                echo '<tr><th>Status</th><td>'.$order['status'].'</td></tr>';
                echo '<tr><th>Total Price</th><td>RM'.$order['total_price'].'</td></tr>';
                echo '</table>';

                echo "<div style='text-align: center; margin-top: 20px;'>";
                echo "<button onclick=\"history.go(-1);\" class='button button-back'>Back</button>";
                echo '</div>';
            } else {
                echo '<p>Order not found.</p>';
            }
        } else {
            echo '<p>No order ID provided.</p>';
        }
        break;

    case 'update':
        if (isset($_GET['id'])) {
            $order_id = $_GET['id'];
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $status = $_POST['status'];

                $updateQuery = 'UPDATE `order` SET status=? WHERE order_id=?';
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('si', $status, $order_id);

                if ($updateStmt->execute()) {
                    $_SESSION['message'] = $updateStmt->affected_rows > 0 ? 'Order updated successfully!' : 'No changes made.';
                    $_SESSION['msg_type'] = 'success';
                    header('Location: order.php?action=view');
                    exit;
                }
            } else {
                $fetchQuery = 'SELECT * FROM `order` WHERE order_id = ?';
                $fetchStmt = $conn->prepare($fetchQuery);
                $fetchStmt->bind_param('i', $order_id);
                $fetchStmt->execute();
                $result = $fetchStmt->get_result();
                $order = $result->fetch_assoc();

                if (!$order) {
                    $_SESSION['message'] = 'Order not found.';
                    $_SESSION['msg_type'] = 'danger';
                    header('Location: order.php?action=view');
                    exit;
                }
                ?>
        
                        <h2>Update Order</h2>
                        <form method="POST" action="">
                            <table>
                                <tr>
                                    <th>Order ID</th>
                                    <td><input type="text" name="orderID" value="<?php echo $order['order_id']; ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>Customer Name</th>
                                    <td><input type="text" name="name" value="<?php echo $order['customer_name']; ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>Document</th>
                                    <td><input type="text" name="document" value="<?php echo $order['document_upload']; ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>Order Date</th>
                                    <td><input type="text" name="date" value="<?php echo $order['order_date']; ?>" readonly></td>
                                </tr>
                                <tr>
                                <th>Status</th>
                                <td>
                                    <select name="status" required>
                                        <option value="done" <?php if (isset($order) && $order['status'] == 'DONE') {
                                            echo 'selected';
                                        } ?>>Done</option>
                                        <option value="in_progress" <?php if (isset($order) && $order['status'] == 'IN PROGRESS') {
                                            echo 'selected';
                                        } ?>>In Progress</option>
                                        <option value="pending" <?php if (isset($order) && $order['status'] == 'PENDING') {
                                            echo 'selected';
                                        } ?>>Pending</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Order Price</th>
                                <td>
                                    <div class="currency-input">
                                        <span class="currency-label">RM</span>
                                        <input type="text" name="price" value="<?php echo htmlspecialchars($order['total_price']); ?>" readonly>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <input type="submit" value="Update">
                                    <button onclick="history.go(-1);" class="button button-back">Back</button>
                                </td>
                            </tr>
                            </table>
                        </form>
        
                        <?php
            }
        }
        break;
}

?>
  <!-- ============================================================== -->
    <!-- Footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
      All Rights Reserved by Infinity Printing. Designed and Developed by <a href="https://www.wrappixel.com">WrapPixel</a>.
    </footer>
  </div>
  </div>

  <!-- ============================================================== -->
  <!-- All Jquery -->
  <!-- ============================================================== -->
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap tether Core JavaScript -->
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../dist/js/app-style-switcher.js"></script>
  <script src="../dist/js/waves.js"></script>
  <script src="../dist/js/sidebarmenu.js"></script>
  <script src="../dist/js/custom.js"></script>
</body>

</html>

<?php
ob_end_flush();
?>
