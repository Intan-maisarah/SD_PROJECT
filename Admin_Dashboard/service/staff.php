<?php
ob_start();
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

// Prepare and execute a query to get the user's email and usertype
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
    <title>Staff Management</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet" />

    <style>
        .preloader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            flex-direction: column;
        }
        /* Printer styling */
        .printer {
            position: relative;
            width: 120px;
            height: 120px;
        }
        .printer-top {
            width: 80px;
            height: 20px;
            background: #666;
            border-radius: 10px 10px 0 0;
            position: absolute;
            top: 0;
            left: 20px;
        }
        .paper-input-slot {
            width: 80px;
            height: 10px;
            background: #444;
            border-radius: 3px;
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .printer-body {
            width: 120px;
            height: 60px;
            background: #333;
            border-radius: 5px;
            position: absolute;
            top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .paper {
            width: 80px;
            height: 50px;
            background: #fff;
            border: 2px solid #333;
            border-radius: 3px;
            position: relative;
            animation: paper-print 2s infinite;
        }
        .printer-tray {
            width: 100px;
            height: 10px;
            background: #333;
            border-radius: 0 0 5px 5px;
            position: absolute;
            bottom: 0;
            left: 10px;
        }
        @keyframes paper-print {
            0% { transform: translateY(0); }
            50% { transform: translateY(20px); }
            100% { transform: translateY(0); }
        }
    </style>
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
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <a class="navbar-brand" href="admin_page.html">
                        <b class="logo-icon">
                            <img src="../../assets/images/logo.png" alt="homepage" style="width: 60px; height: auto;" />
                        </b>
                    </a>
                </div>
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <ul class="navbar-nav float-end">
                        <li class="nav-item dropdown">
                            
                            <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="pages_profile.php"><i class="mdi mdi-account m-r-5 m-l-5"></i> My Profile</a>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        
        <?php include '../sidebar/sidebarAdmin.php'; ?>
        
        <div class="page-wrapper">
            <?php
            include('../../connection.php');
            $action = isset($_GET['action']) ? $_GET['action'] : 'view';

            switch($action) {
                case 'view':
                    $query = "SELECT * FROM users WHERE userType = 'STAFF'";
                    $result = mysqli_query($conn, $query);

                    echo "<h2>Staff List</h2>";
                    if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['message']; ?>
                            
                        </div>
                        <?php
                        // Unset message after displaying it
                        unset($_SESSION['message']);
                        unset($_SESSION['msg_type']);
                      endif;

                    echo "<style>
                            table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; }
                            th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
                            th { background-color: #f2f2f2; color: #333; font-weight: bold; }
                            tr:nth-child(even) { background-color: #f9f9f9; }
                            tr:hover { background-color: #f1f1f1; }
                            a { color: #1a73e8; text-decoration: none; }
                            a:hover { text-decoration: underline; }
                            
                        </style>";

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
                        <a href='staff.php?action=edit&id=" . $row['id'] . "' style='background-color: #1a73e8; border-radius: 3px; padding: 8px; color: white;'>Edit</a>
                        <a href='staff.php?action=delete&id=" . $row['id'] . "' style='background-color: #e53935; border-radius: 3px; padding: 8px; color: white;' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                        </td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                    
                    echo "<br><a href='staff.php?action=add' style='background-color: #00b300; padding: 8px;  margin-left: 30px;border-radius: 3px;  color: white;'>Add Staff</a><br><br>";
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
                                // Check if the query executed and the row was affected
                                if ($updateStmt->affected_rows > 0) {
                                    $_SESSION['message'] = "Staff updated successfully!";
                                    $_SESSION['msg_type'] = "success";
                                } else {
                                    // No rows were updated (data may not have changed)
                                    $_SESSION['message'] = "No changes made to the staff.";
                                    $_SESSION['msg_type'] = "warning";
                                }
                                header("Location: staff.php?action=view");
                                exit;
                            } else {
                                // Log the error or display it
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

<style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f8f9fa;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            max-width: 800px;
                            margin: 50px auto;
                            padding: 20px;
                            background-color: #fff;
                            border-radius: 8px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                        h2 {
                            text-align: center;
                            color: #333;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                        }
                        th, td {
                            text-align: left;
                            padding: 12px;
                            border-bottom: 1px solid #ddd;
                        }
                        th {
                            background-color: #f2f2f2;
                            color: #333;
                            font-weight: bold;
                        }
                        tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }
                        tr:hover {
                            background-color: #f1f1f1;
                        }
                        input[type="text"], input[type="number"], select {
                            width: 95%;
                            padding: 10px;
                            border: 1px solid #ccc;
                            border-radius: 4px;
                            font-size: 16px;
                        }
                        input[type="submit"], button {
                            padding: 10px 15px;
                            background-color: #28a745;
                            color: white;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 16px;
                        }
                        input[type="submit"]:hover, button:hover {
                            background-color: #218838;
                        }
                        .back-button {
                            background-color: #007bff;
                            margin-left: 10px;
                        }
                        .back-button:hover {
                            background-color: #0056b3;
                        }
                        .form-actions {
                            text-align: center;
                            margin-top: 20px;
                        }
                    </style>
                            <h2>Edit Staff</h2>

                        <form method="POST" action="">
                            <table>
                                <tr>
                                    <th>Username</th>
                                    <td><input type="text" name="username" value="<?php echo $row['username']; ?>" required>
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
                                          <input type="submit" value="Update Staff" style="padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                          <button onclick="history.go(-1);" style="padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
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
                        // Prepare the insert query using placeholders
                        $insertQuery = "INSERT INTO users (username, name, email, contact, address, password, userType, is_verified) 
                        VALUES (?, ?, ?, ?, ?, ?, 'STAFF', '0')";
                        $insertStmt = $conn->prepare($insertQuery);

                        // Bind the parameters (username, name, email, contact, address, password)
                        $insertStmt->bind_param("ssssss", $username, $name, $email, $contact, $address, $password);

                        // Execute the query
                        $insertStmt->execute();

                        // Check if the insertion was successful
                        if ($insertStmt->affected_rows > 0) {
                        $_SESSION['message'] = "Staff added successfully!";
                        $_SESSION['msg_type'] = "success"; // 'success', 'danger', 'info', etc.
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

<style>
                  table {
                      width: 100%;
                      border-collapse: collapse;
                      font-family: Arial, sans-serif;
                      margin-top: 20px;
                  }
                  th, td {
                      text-align: left;
                      padding: 12px;
                      border-bottom: 1px solid #ddd;
                  }
                  th {
                      background-color: #f2f2f2;
                      color: #333;
                      font-weight: bold;
                  }
                  tr:nth-child(even) {
                      background-color: #f9f9f9;
                  }
                  tr:hover {
                      background-color: #f1f1f1;
                  }
                  input[type="text"], input[type="email"], input[type="password"],textarea[name="address"],input[type="number"], select {
                      width: 95%;
                      padding: 10px;
                      border: 1px solid #ccc;
                      border-radius: 4px;
                  }
                  input[type="submit"] {
                      background-color: #28a745;
                      color: white;
                      padding: 10px 15px;
                      border: none;
                      border-radius: 4px;
                      cursor: pointer;
                  }
                  input[type="submit"]:hover {
                      background-color: #218838;
                  }
                  h2 {
                      margin-top: 20px;
                      color: #333;
                  }
              </style>
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
                            <input type="submit" value="Add Staff">
                            <button onclick="history.go(-1);" style="padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
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

