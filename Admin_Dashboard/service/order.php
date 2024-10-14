<?php
ob_start();
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the session is started and check if user ID is set
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.<br>";
    exit;
}

// Include the database connection
require '../../connection.php';

// Fetch the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];
$adminEmail = '';
$usertype = '';
$profile_pic = '';

// Prepare and execute a query to get the user's email and usertype
$sql = "SELECT email, usertype, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Prepare statement failed: " . $conn->error . "<br>";
    exit;
}
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    echo "Get result failed: " . $stmt->error . "<br>";
    exit;
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $adminEmail = $row['email'];
    $usertype = $row['usertype'];
    $profile_pic = $row['profile_pic'];
} else {
    echo "User not found.<br>";
    exit;
}

// Close statement and connection
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
   <!-- PHP coding for customer -->
   <?php
// Connect to the database
include('../../connection.php'); 

$action = isset($_GET['action']) ? $_GET['action'] : 'view';

switch($action) {
    case 'view':
        $query = "SELECT * FROM `order`";
        $result = mysqli_query($conn, $query);

        echo "<h2>Order List</h2>";

        if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
            </div>
        <?php 
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
        endif;
        echo "<div class='table-container'>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Document</th><th>Status</th><th>Actions</th></tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['order_id'] . "</td>";
            echo "<td>" . $row['document_upload'] . "</td>";
            echo "<td>" . $row['order_status'] . "</td>";
            echo "<td>
                  <a href='customer.php?action=edit&id=" . $row['id'] . "' class='button button-edit'>Edit</a> |
                  <a href='customer.php?action=delete&id=" . $row['id'] . "' class='button button-delete' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                </td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        break;

   /* case 'edit':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $contact = $_POST['contact'];
                $address = $_POST['address'];

                $updateQuery = "UPDATE users SET name=?, email=?, contact=?, address=? WHERE id=?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ssssi", $name, $email, $contact, $address, $id);
                
                if ($updateStmt->execute()) {
                    $_SESSION['message'] = $updateStmt->affected_rows > 0 ? "Customer updated successfully!" : "No changes made.";
                    $_SESSION['msg_type'] = "success";
                    header("Location: customer.php?action=view");
                    exit;
                }
            } else {
                $fetchQuery = "SELECT * FROM users WHERE id = ?";
                $fetchStmt = $conn->prepare($fetchQuery);
                $fetchStmt->bind_param("i", $id);
                $fetchStmt->execute();
                $result = $fetchStmt->get_result();
                $customer = $result->fetch_assoc();

                if (!$customer) {
                    $_SESSION['message'] = "Customer not found.";
                    $_SESSION['msg_type'] = "danger";
                    header("Location: customer.php?action=view");
                    exit;
                }
                ?>

                <h2>Edit Customer</h2>
                <form method="POST" action="">
                    <table>
                        <tr>
                            <th>Username</th>
                            <td><input type="text" name="username" value="<?php echo $customer['username']; ?>" readonly></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><input type="text" name="name" value="<?php echo $customer['name']; ?>" required></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><input type="email" name="email" value="<?php echo $customer['email']; ?>" required></td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td><input type="number" name="contact" value="<?php echo $customer['contact']; ?>" required></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><input type="text" name="address" value="<?php echo $customer['address']; ?>" required></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type="submit" value="Update Customer">
                                <button onclick="history.go(-1);" class="button button-back">Back</button>
                            </td>
                        </tr>
                    </table>
                </form>

                <?php
            }
        }
        break;

    case 'delete':
        $id = $_GET['id'];
        $deleteQuery = "DELETE FROM users WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $id);
        if ($deleteStmt->execute()) {
            $_SESSION['message'] = "Customer deleted successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Error deleting customer.";
            $_SESSION['msg_type'] = "danger";
        }
        header("Location: customer.php?action=view");
        break;*/
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
