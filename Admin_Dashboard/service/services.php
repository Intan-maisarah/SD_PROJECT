<!-- services.php -->

<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    <title>Service Management</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet" />
    
    <style>
        /* Preloader styles */
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

        /* Animation for the paper printing effect */
        @keyframes paper-print {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(20px);
            }
            100% {
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
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
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="../assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31" />
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="pages_profile.php"><i class="mdi mdi-account m-r-5 m-l-5"></i> My Profile</a>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        
        <?php
            if ($usertype === 'ADMIN') {
                include '../sidebar/sidebarAdmin.php';
            } else {
                include '../sidebar/sidebarStaff.php';
            }
        ?>
        
        <div class="page-wrapper">
            <?php
            // Include database connection
            include('../../connection.php');

            // Check if action is set in the URL
            $action = isset($_GET['action']) ? $_GET['action'] : 'view';

            switch ($action) {
                case 'view':
                    // View services
                    $query = "SELECT * FROM services";
                    $result = mysqli_query($conn, $query);
                
                    echo "<h2>Service List</h2>";
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

                             .alert {
                                padding: 15px;
                                margin-bottom: 20px;
                                border: 1px solid transparent;
                                border-radius: 4px;
                            }
                            .alert-success {
                                color: #155724;
                                background-color: #d4edda;
                                border-color: #c3e6cb;
                            }
                            .alert-danger {
                                color: #721c24;
                                background-color: #f8d7da;
                                border-color: #f5c6cb;
                            }
                            .close {
                                float: right;
                                font-size: 1.5rem;
                                font-weight: bold;
                                line-height: 1;
                                color: #000;
                                text-shadow: 0 1px 0 #fff;
                                opacity: .5;
                                cursor: pointer;
                            }

                            table {
                              width: 100%;
                              border-collapse: collapse;
                              font-family: Arial, sans-serif;
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
                              text-align: center;
                            }
                
                            tr:nth-child(even) {
                              background-color: #f9f9f9;
                            }
                
                            tr:hover {
                              background-color: #f1f1f1;
                            }
                
                            a {
                              color: #1a73e8;
                              text-decoration: none;
                            }
                
                            a:hover {
                              text-decoration: underline;
                            }
                
                            img {
                              max-width: 100px;
                              height: auto;
                            }
                
                            @media screen and (max-width: 600px) {
                              table, th, td {
                                width: 100%;
                                display: block;
                              }
                
                              th, td {
                                text-align: left;
                                padding: 10px;
                              }
                
                              th {
                                background-color: #f0f0f0;
                              }
                            }
                          </style>";
                
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Status</th><th>Image</th><th>Actions</th></tr>";
                
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['service_id'] . "</td>";
                        echo "<td>" . $row['service_name'] . "</td>";
                        echo "<td>" . $row['service_description'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                
                        // Display the image, if available
                        if (!empty($row['image'])) {
                            echo "<td><img src='" . $row['image'] . "' alt='" . htmlspecialchars($row['service_name']) . "'></td>";
                        } else {
                            echo "<td>No image available</td>";
                        }
                
                        echo "<td>
                                <a href='services.php?action=edit&service_id=" . $row['service_id'] . "' style='display: inline-block; padding: 8px 16px; text-align: center; text-decoration: none; background-color: #1a73e8; color: white; border-radius: 4px; margin-right: 8px;'>Edit</a>
                                <a href='services.php?action=delete&service_id=" . $row['service_id'] . "' style='display: inline-block; padding: 8px 16px; text-align: center; text-decoration: none; background-color: #e53935; color: white; border-radius: 4px;' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                
                    echo "</table>";
                    echo "<br><a href='services.php?action=add' style='background-color: #00b300; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;'>Add New Service</a>";
                
                    break;
                

                    case 'add':
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            // Initialize variables
                            $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : null; // Handle optional service_id
                            $service_name = $_POST['service_name'];
                            $service_description = $_POST['service_description'];
                            $status = $_POST['status'];
                            $imageURL = ""; // Initialize image URL
                    
                            // Check if the image file was uploaded
                            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                                // Define the destination path
                                $targetDirectory = "../../assets/images/uploads/";
                                $imageName = basename($_FILES['image']['name']);
                                $targetFilePath = $targetDirectory . $imageName;
                    
                                // Create the directory if it doesn't exist
                                if (!is_dir($targetDirectory)) {
                                    if (!mkdir($targetDirectory, 0755, true)) {
                                        echo "<div>Error creating directory.</div>";
                                        exit;
                                    }
                                }
                    
                                // Attempt to move the uploaded file to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                                    $imageURL = $targetFilePath; // Store the file path for the database
                                } else {
                                    echo "<div>Error uploading image.</div>";
                                    exit;
                                }
                            } else {
                                echo "<div>No image uploaded or error in the upload.</div>";
                                exit;
                            }
                    
                            // Prepare and execute the insert query
                            $insertQuery = "INSERT INTO services (service_id, service_name, service_description, status, image) VALUES (?, ?, ?, ?, ?)";
                            $insertStmt = $conn->prepare($insertQuery);
                    
                            if ($insertStmt) {
                                // Use null if service_id is not provided, adjust the bind_param accordingly
                                $insertStmt->bind_param("sssss", $service_id, $service_name, $service_description, $status, $imageURL);
                                $insertStmt->execute();
                    
                                if ($insertStmt->affected_rows > 0) {
                                    $_SESSION['message'] = "Service added successfully!";
                                    $_SESSION['msg_type'] = "success";
                                } else {
                                    $_SESSION['message'] = "Error adding service.";
                                    $_SESSION['msg_type'] = "danger";
                                }
                                $insertStmt->close();
                            } else {
                                echo "<div>Error preparing statement: " . $conn->error . "</div>";
                                exit;
                            }
                    
                            // Redirect to services view page
                            header("Location: services.php?action=view");
                            exit;
                        }
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
                    
                        input[type="text"], input[type="number"], select {
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
                    
                        .remove-btn {
                            background-color: #dc3545;
                            color: white;
                            padding: 5px 10px;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                            margin-left: 10px;
                        }
                    
                        .add-more {
                            margin-top: 20px;
                        }
                    </style>
                    
                    <h2>Add New Service</h2>
                    <form action="services.php?action=add" method="POST" enctype="multipart/form-data"> <!-- Add enctype -->
                        <table>
                            <tr>
                                <th>Service Name</th>
                                <td><input type="text" name="service_name" required></td>
                            </tr>
                            <tr>
                                <th>Service Description</th>
                                <td><input type="text" name="service_description" required></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <select name="status" required>
                                        <option value="available" <?php if (isset($service) && $service['status'] == 'available') echo 'selected'; ?>>Available</option>
                                        <option value="unavailable" <?php if (isset($service) && $service['status'] == 'unavailable') echo 'selected'; ?>>Unavailable</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Image</th>
                                <td>
                                    <input type="file" name="image" id="image" class="form-control" required> <!-- Make sure the file input is required -->
                                </td>
                            </tr>
                            <tr>
                                      <td colspan="2" style="text-align: center;">
                                          <input type="submit" value="Add Service" style="padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                          <button onclick="history.go(-1);" style="padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                                             Back</button>

                                      </td>
                                  </tr>
                        </table>
                        
                    </form>
                    
                    <?php
                    break;
                    

                    case 'edit':
                        // Edit service
                        $service_id = $_GET['service_id'];
                    
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $service_name = $_POST['service_name'];
                            $service_description = $_POST['service_description'];
                            $status = $_POST['status'];
                            $imageURL = $_POST['image']; // Keep existing image by default
                            
                            // Define the destination path for the new image
                            $targetDirectory = "../../assets/images/uploads/";
                    
                            // Check if the image is uploaded
                            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                                $imageName = $_FILES['image']['name'];
                                $targetFilePath = $targetDirectory . basename($imageName);
                                $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                    
                                // Validate the image file type
                                $allowedFormats = ["jpg", "jpeg", "png", "gif"];
                                if (in_array($imageFileType, $allowedFormats)) {
                                    // Create the directory if it doesn't exist
                                    if (!is_dir($targetDirectory)) {
                                        mkdir($targetDirectory, 0755, true);
                                    }
                    
                                    // Attempt to move the uploaded file
                                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                                        $imageURL = $targetFilePath; // Update with the new image URL
                                    } else {
                                        echo "<div>Error uploading image.</div>";
                                        exit;
                                    }
                                } else {
                                    echo "<div>Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.</div>";
                                    exit;
                                }
                            }
                    
                            // Update service details in the database
                        $updateQuery = "UPDATE services SET service_name = ?, status = ?, service_description = ?, image = ? WHERE service_id = ?";
                        $updateStmt = $conn->prepare($updateQuery);
                        $updateStmt->bind_param("ssssi", $service_name, $status, $service_description, $imageURL, $service_id);

                        if ($updateStmt->execute()) {
                            // Check if the query executed and the row was affected
                            if ($updateStmt->affected_rows > 0) {
                                $_SESSION['message'] = "Service updated successfully!";
                                $_SESSION['msg_type'] = "success";
                            } else {
                                // No rows were updated (data may not have changed)
                                $_SESSION['message'] = "No changes made to the service.";
                                $_SESSION['msg_type'] = "warning";
                            }
                            header("Location: services.php?action=view");
                            exit;
                        } else {
                            // Log the error or display it
                            error_log("Error updating service: " . $updateStmt->error);
                            $_SESSION['message'] = "Error updating service.";
                            $_SESSION['msg_type'] = "danger";
                            header("Location: service.php?action=view");
                            exit;
                        }
                        }
                        
                    
                  
                      // Fetch the current details of the service
                      $selectQuery = "SELECT * FROM services WHERE service_id = ?";
                      $selectStmt = $conn->prepare($selectQuery);
                      $selectStmt->bind_param("i", $service_id);
                      $selectStmt->execute();
                      $selectResult = $selectStmt->get_result();
                  
                      if ($selectResult->num_rows > 0) {
                          $service = $selectResult->fetch_assoc();
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
                              input[type="text"], input[type="number"], select {
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
                          <h2>Edit Service</h2>
                          <form action="" method="POST" enctype="multipart/form-data">
                              <table>
                                  <tr>
                                      <th>Service ID</th>
                                      <td><input type="text" name="service_id" value="<?php echo htmlspecialchars($service['service_id']); ?>" readonly></td>
                                  </tr>
                                  <tr>
                                      <th>Service Name</th>
                                      <td><input type="text" name="service_name" value="<?php echo htmlspecialchars($service['service_name']); ?>" required></td>
                                  </tr>
                                  <tr>
                                      <th>Service Description</th>
                                      <td><input type="text" name="service_description" value="<?php echo htmlspecialchars($service['service_description']); ?>" required></td>
                                  </tr>
                                  <tr>
                                      <th>Status</th>
                                      <td>
                                          <select name="status" required>
                                              <option value="available" <?php if ($service['status'] == 'available') echo 'selected'; ?>>Available</option>
                                              <option value="unavailable" <?php if ($service['status'] == 'unavailable') echo 'selected'; ?>>Unavailable</option>
                                          </select>
                                      </td>
                                  </tr>
                                  <tr>
                                      <th>Image</th>
                                      <td>
                                          <input type="file" name="image" id="image" class="form-control">
                                          <input type="hidden" name="image" value="<?php echo htmlspecialchars($service['image']); ?>" required>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2" style="text-align: center;">
                                          <input type="submit" value="Update Service" style="padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                          <button onclick="history.go(-1);" style="padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                                             Back</button>

                                      </td>
                                  </tr>
                              </table>
                          </form>
                          <br>
                          <?php
                      } else {
                          echo "<div>Service not found.</div>";
                      }
                      $selectStmt->close();
                      break;
                  

                case 'delete':
                    // Delete service
                    $service_id = $_GET['service_id'];
                    $deleteQuery = "DELETE FROM services WHERE service_id = ?";
                    $deleteStmt = $conn->prepare($deleteQuery);
                    $deleteStmt->bind_param("i", $service_id);
                    $deleteStmt->execute();

                    if ($deleteStmt->affected_rows > 0) {
                        $_SESSION['message'] = "Service deleted successfully!";
                        $_SESSION['msg_type'] = "success";
                        header("Location: services.php?action=view");
                        exit;
                    } else {
                        $_SESSION['message'] = "Error deleting service.";
                        $_SESSION['msg_type'] = "danger";
                        header("Location: services.php?action=view");
                        exit;
                    }
                    
                

                default:
                    echo "<div>Invalid action.</div>";
            }
            ?>

            <footer class="footer text-center">
                All Rights Reserved by Infinity Printing
            </footer>
        </div>
    </div>

   <!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap tether Core JavaScript -->
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../dist/js/app-style-switcher.js"></script>
  <script src="../dist/js/waves.js"></script>
  <script src="../dist/js/sidebarmenu.js"></script>
  <script src="../dist/js/custom.js"></script>

</html>


<?php

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_end_flush();
?>