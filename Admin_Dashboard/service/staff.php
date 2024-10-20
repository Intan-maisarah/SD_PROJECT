<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.<br>";
    exit;
}

require '../../connection.php';

$user_id = $_SESSION['user_id'];
$adminEmail = '';
$usertype = '';

$sql = "SELECT email, usertype FROM users WHERE id = ?";
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
} else {
    echo "User not found.<br>";
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
    <title>Staff Management</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet" />
    <link href="style.css" rel="stylesheet">

    
</head>
<body>
    <div class="preloader">
        <div class="printer">
            <div class="printer-top"></div>
            <div class="paper-input-slot"></div>
            <div class="printer-body">
                <div class="paper"></div>
            </div>
            <div class="printer-tray"></div>
        </div>
    </div>

    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-header-position="absolute">
    <?php include '../sidebar/header.php'; ?>

        
        <?php include '../sidebar/sidebarAdmin.php'; ?>
        
        <div class="page-wrapper">
            <?php
            include('../../connection.php');
            $action = isset($_GET['action']) ? $_GET['action'] : 'view';

            switch($action) {
                case 'view':
                    $query = "SELECT * FROM users WHERE userType = 'STAFF'";
                    $result = mysqli_query($conn, $query);

                    echo "<div>
                        <h2>Staff List</h2>
                        <a href='staff.php?action=add' class='button button-add' >Add Staff</a>
                    </div>";
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
                    echo "<tr><th>ID</th><th>Username</th><th>Name</th><th>Email</th><th>Phone Number</th><th>Address</th><th>Actions</th></tr>";

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['contact'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo "<td>
                        <a href='staff.php?action=edit&id=" . $row['id'] . "' class='button button-edit'>Edit</a> |
                        <a href='staff.php?action=delete&id=" . $row['id'] . "' class='button button-delete' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                        </td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                    echo "</div>";
                    
                    break;

                case 'edit':
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                          $username = $_POST['username'];
                            $name = $_POST['name'];
                            $email = $_POST['email'];
                            $contact = $_POST['contact'];
                            $address = $_POST['address'];
                            $updateQuery = "UPDATE users SET name=?, email=?, contact=?, address=? WHERE id=?";
                            $updateStmt = $conn->prepare($updateQuery);
                            $updateStmt->bind_param("ssssi", $name, $email, $contact, $address, $id);
                            
                            if ($updateStmt->execute()) {
                                if ($updateStmt->affected_rows > 0) {
                                    $_SESSION['message'] = "Staff updated successfully!";
                                    $_SESSION['msg_type'] = "success";
                                } else {
                                    $_SESSION['message'] = "No changes made to the staff.";
                                    $_SESSION['msg_type'] = "warning";
                                }
                                header("Location: staff.php?action=view");
                                exit;
                            } else {
                                error_log("Error updating staff: " . $conn->error);
                                $_SESSION['message'] = "Error updating staff.";
                                $_SESSION['msg_type'] = "danger";
                                header("Location: staff.php?action=view");
                                exit;
                            }
                        } else {
                            $selectQuery = "SELECT * FROM users WHERE id='$id'";
                            $result = mysqli_query($conn, $selectQuery);
                            $row = mysqli_fetch_assoc($result);
                            ?>

                            <h2>Edit Staff</h2>

                        <form method="POST" action="">
                            <table>
                                <tr>
                                    <th>Username</th>
                                    <td><input type="text" name="username" value="<?php echo $row['username']; ?>" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><input type="text" name="name" value="<?php echo $row['name']; ?>" required></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><input type="email" name="email" value="<?php echo $row['email']; ?>" required></td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td>
                                    <input type="text" name="contact" value="<?php echo $row['contact']; ?>" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>
                                    <textarea name="address" required><?php echo $row['address']; ?></textarea><br>
                                    </td>
                                </tr>
                            
                            <tr>
                                      <td colspan="2" style="text-align: center;">
                                          <input type="submit" value="Update Staff" class="button button-edit">
                                          <button onclick="history.go(-1);" class="button button-back">
                                             Back</button>

                                      </td>
                                  </tr>
                                  </table>
                        </form>
                            
                            <?php
                        }
                    }
                    break;

                    case 'delete':
                        // Delete service
                        $id = $_GET['id'];
                        $deleteQuery = "DELETE FROM users WHERE id = ?";
                        $deleteStmt = $conn->prepare($deleteQuery);
                        $deleteStmt->bind_param("i", $id);
                        $deleteStmt->execute();
    
                        if ($deleteStmt->affected_rows > 0) {
                            $_SESSION['message'] = "Staff deleted successfully!";
                            $_SESSION['msg_type'] = "success";
                            header("Location: staff.php?action=view");
                            exit;
                        } else {
                            $_SESSION['message'] = "Error deleting staff.";
                            $_SESSION['msg_type'] = "danger";
                            header("Location: staff.php?action=view");
                            exit;
                        }

                case 'add':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                      $username = $_POST['username'];
                        $name = $_POST['name'];
                        $email = $_POST['email'];
                        $contact = $_POST['contact'];
                        $address = $_POST['address'];
                        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                      $insertQuery = "INSERT INTO users (username, name, email, contact, address, password, userType, is_verified) 
                        VALUES (?, ?, ?, ?, ?, ?, 'STAFF', '0')";
                        $insertStmt = $conn->prepare($insertQuery);

                      $insertStmt->bind_param("ssssss", $username, $name, $email, $contact, $address, $password);

                        $insertStmt->execute();

                        if ($insertStmt->affected_rows > 0) {
                        $_SESSION['message'] = "Staff added successfully!";
                        $_SESSION['msg_type'] = "success"; 
                        header("Location: staff.php?action=view");
                        exit;
                        } else {
                        $_SESSION['message'] = "Error adding staff.";
                        $_SESSION['msg_type'] = "danger";
                        header("Location: staff.php?action=view");
                        exit;
                        }

                    } else {
                        ?>

                        <h2>Add Staff</h2>
                        <form method="POST">
                            <label for="username">Username:</label><br>
                            <input type="text" name="username" required><br>
                            <label for="name">Name:</label><br>
                            <input type="text" name="name" required><br>
                            <label for="email">Email:</label><br>
                            <input type="email" name="email" required><br>
                            <label for="contact">Phone Number:</label><br>
                            <input type="text" name="contact" required><br>
                            <label for="address">Address:</label><br>
                            <textarea name="address" required></textarea><br>
                            <label for="password">Password:</label><br>
                            <input type="password" name="password" required><br>
                            <br>
                            <input type="submit" value="Add Staff" class="button button-add">
                            <button onclick="history.go(-1);" class="button button-back">
                                             Back</button>
                        </form>
                        <?php
                    }
                    break;

                default:
                    echo "Invalid action.";
                    break;
            }
            ?>
        </div>
    </div>

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

