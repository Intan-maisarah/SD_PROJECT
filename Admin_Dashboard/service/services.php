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
$profile_pic = '';

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
    <link href="style.css" rel="stylesheet">
    
    
</head>

<body>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-header-position="absolute">
        
    <?php include '../sidebar/header.php'; ?>

        
        <?php
            if ($usertype === 'ADMIN') {
                include '../sidebar/sidebarAdmin.php';
            } else {
                include '../sidebar/sidebarStaff.php';
            }
        ?>
        
        <div class="page-wrapper">
            <?php
            include('../../connection.php');

            $action = isset($_GET['action']) ? $_GET['action'] : 'view';

            switch ($action) {
                case 'view':
                    $query = "SELECT * FROM services";
                    $result = mysqli_query($conn, $query);
                
                    echo "<div class='list-header'>
                        <h2>Service List</h2>
                        <a href='services.php?action=add' class ='button button-add'>Add Service</a>
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
                    echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Status</th><th>Image</th><th>Actions</th></tr>";
                
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['service_id'] . "</td>";
                        echo "<td>" . $row['service_name'] . "</td>";
                        echo "<td>" . $row['service_description'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                
                        if (!empty($row['image'])) {
                            echo "<td><img src='" . $row['image'] . "' alt='" . htmlspecialchars($row['service_name']) . "'></td>";
                        } else {
                            echo "<td>No image available</td>";
                        }
                
                        echo "<td>
                                <a href='services.php?action=edit&service_id=" . $row['service_id'] . "' class= 'button button-edit'>Edit</a> |
                                <a href='services.php?action=delete&service_id=" . $row['service_id'] . "' class = 'button button-delete' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                
                    echo "</table>";
                    echo "</div>";
                
                    break;
                

                    case 'add':
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : null; // Handle optional service_id
                            $service_name = $_POST['service_name'];
                            $service_description = $_POST['service_description'];
                            $status = $_POST['status'];
                            $imageURL = ""; 
                    
                            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                                $targetDirectory = "../../assets/images/uploads/";
                                $imageName = basename($_FILES['image']['name']);
                                $targetFilePath = $targetDirectory . $imageName;
                    
                                if (!is_dir($targetDirectory)) {
                                    if (!mkdir($targetDirectory, 0755, true)) {
                                        echo "<div>Error creating directory.</div>";
                                        exit;
                                    }
                                }
                    
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                                    $imageURL = $targetFilePath; 
                                } else {
                                    echo "<div>Error uploading image.</div>";
                                    exit;
                                }
                            } else {
                                echo "<div>No image uploaded or error in the upload.</div>";
                                exit;
                            }
                    
                            $insertQuery = "INSERT INTO services (service_id, service_name, service_description, status, image) VALUES (?, ?, ?, ?, ?)";
                            $insertStmt = $conn->prepare($insertQuery);
                    
                            if ($insertStmt) {
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
                    
                            header("Location: services.php?action=view");
                            exit;
                        }
                    ?>                        
                    
                    <h2>Add New Service</h2>
                    <form action="services.php?action=add" method="POST" enctype="multipart/form-data"> 
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
                                    <input type="file" name="image" id="image" class="form-control" required> 
                                </td>
                            </tr>
                            <tr>
                                      <td colspan="2" style="text-align: center;">
                                          <input type="submit" value="Add Service" class = "button button-edit" >
                                          <button onclick="history.go(-1);" class = "button button-back">
                                             Back</button>

                                      </td>
                                  </tr>
                        </table>
                        
                    </form>
                    
                    <?php
                    break;
                    

                    case 'edit':
                        $service_id = $_GET['service_id'];
                    
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $service_name = $_POST['service_name'];
                            $service_description = $_POST['service_description'];
                            $status = $_POST['status'];
                            $imageURL = $_POST['image']; 
                            
                            $targetDirectory = "../../assets/images/uploads/";
                    
                            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                                $imageName = $_FILES['image']['name'];
                                $targetFilePath = $targetDirectory . basename($imageName);
                                $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                    
                                $allowedFormats = ["jpg", "jpeg", "png", "gif"];
                                if (in_array($imageFileType, $allowedFormats)) {
                                    if (!is_dir($targetDirectory)) {
                                        mkdir($targetDirectory, 0755, true);
                                    }
                    
                                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                                        $imageURL = $targetFilePath; 
                                    } else {
                                        echo "<div>Error uploading image.</div>";
                                        exit;
                                    }
                                } else {
                                    echo "<div>Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.</div>";
                                    exit;
                                }
                            }
                    
                        $updateQuery = "  SET service_name = ?, status = ?, service_description = ?, image = ? WHERE service_id = ?";
                        $updateStmt = $conn->prepare($updateQuery);
                        $updateStmt->bind_param("ssssi", $service_name, $status, $service_description, $imageURL, $service_id);

                        if ($updateStmt->execute()) {
                            if ($updateStmt->affected_rows > 0) {
                                $_SESSION['message'] = "Service updated successfully!";
                                $_SESSION['msg_type'] = "success";
                            } else {
                                $_SESSION['message'] = "No changes made to the service.";
                                $_SESSION['msg_type'] = "warning";
                            }
                            header("Location: services.php?action=view");
                            exit;
                        } else {
                            error_log("Error updating service: " . $updateStmt->error);
                            $_SESSION['message'] = "Error updating service.";
                            $_SESSION['msg_type'] = "danger";
                            header("Location: service.php?action=view");
                            exit;
                        }
                        }
                      $selectQuery = "SELECT * FROM services WHERE service_id = ?";
                      $selectStmt = $conn->prepare($selectQuery);
                      $selectStmt->bind_param("i", $service_id);
                      $selectStmt->execute();
                      $selectResult = $selectStmt->get_result();
                  
                      if ($selectResult->num_rows > 0) {
                          $service = $selectResult->fetch_assoc();
                          ?>
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
                                          <input type="submit" value="Update" class="button button-edit">
                                          <button onclick="history.go(-1);" class="button button-back">
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