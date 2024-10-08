
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
  <title>Printing Management</title>
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
  <!-- ============================================================== -->
  <!-- Preloader - style you can find in spinners.css -->
  <!-- ============================================================== -->
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


  
  <!-- ============================================================== -->
  <!-- Main wrapper -->
  <!-- ============================================================== -->
  <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-header-position="absolute">
    
    <!-- ============================================================== -->
    <!-- Topbar header -->
    <!-- ============================================================== -->
    <header class="topbar" data-navbarbg="skin5">
      <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin5">
          <a class="navbar-brand" href="admin_page.php">
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
    
    <!-- ============================================================== -->
    <!-- Sidebar -->
    <!-- ============================================================== -->
    <?php
    if ($usertype === 'ADMIN') {
        include '../sidebar/sidebarAdmin.php';
    } else {
        include '../sidebar/sidebarStaff.php';
    }
    ?>
    
    <!-- ============================================================== -->
    <!-- Page wrapper -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
   <!--php coding for spec-->
   <?php
// Connect to the database
include('../../connection.php'); // Include your database connection file

// Check if action is set in the URL
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

switch($action) {
    case 'view':
        // View customers
        $query = "SELECT * FROM specification";
$result = mysqli_query($conn, $query);

echo "<h2>Print Specification</h2>";
if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']; ?>
        
    </div>
    <?php
    // Unset message after displaying it
    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
endif;

// Apply CSS styling to the table
echo "<style>
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

// Table structure
echo "<table>";
echo "<tr><th>ID</th><th>Specification Name</th><th>Specification Type</th><th>Price</th><th>Status</th><th>Actions</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>";
  echo "<td>" . $row['id'] . "</td>";
  echo "<td>" . $row['spec_name'] . "</td>";
  echo "<td>" . $row['spec_type'] . "</td>";
  echo "<td>" . $row['price'] . "</td>";
  echo "<td>" . $row['status'] . "</td>";
  echo "<td>
  <a href='printspec.php?action=edit&id=" . $row['id'] . "' style='display: inline-block; padding: 8px 16px; text-align: center; text-decoration: none; background-color: #1a73e8; color: white; border-radius: 4px; margin-right: 8px;'>Edit</a>
  <a href='javascript:void(0);'
     style='display: inline-block; padding: 8px 16px; text-align: center; text-decoration: none; background-color: #e53935; color: white; border-radius: 4px;'
     onclick='openDeleteModal(\"" . addslashes($row['spec_name']) . "\", " . $row['id'] . ")'>Delete</a>
  </td>";
  echo "</tr>";
}


echo "</table>";
echo "<br><a href='printspec.php?action=add' style='background-color: #00b300; padding: 8px;  margin-left: 30px; color: white;'>Add Print Specification</a><br><br>";
        break;

            case 'edit':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $id = intval($_GET['id']); // Ensure $id is an integer

                    // Fetch the existing specification from the database
                    $stmt = $conn->prepare("SELECT * FROM specification WHERE id = ?");
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0) {
                        $spec = $result->fetch_assoc();
                    } else {
                        echo "Specification not found!";
                        exit;
                    }

                    // Handle form submission for editing the specification
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                      $spec_type = $_POST['spec_type'];
                      $price = $_POST['price'];
                      $status = $_POST['status'];
                      $spec_name = $spec['spec_name']; // Fetch the current specification name
                  
                      $apply_price_to_all = isset($_POST['apply_price_to_all']) ? 1 : 0;
                      $apply_status_to_all = isset($_POST['apply_status_to_all']) ? 1 : 0;
                  
                      // If both checkboxes are checked, update price and status for all specification types under the same spec name
                      if ($apply_price_to_all && $apply_status_to_all) {
                          $updateQuery = "UPDATE specification SET price = ?, status = ? WHERE spec_name = ?";
                          $updatestmt = $conn->prepare($updateQuery);
                          $updatestmt->bind_param('sss', $price, $status, $spec_name);
                  
                      // If only the price checkbox is checked, update the price for all specification types under the same spec name
                      } elseif ($apply_price_to_all) {
                          $updateQuery = "UPDATE specification SET price = ? WHERE spec_name = ?";
                          $updatestmt = $conn->prepare($updateQuery);
                          $updatestmt->bind_param('ss', $price, $spec_name);
                  
                      // If only the status checkbox is checked, update the status for all specification types under the same spec name
                      } elseif ($apply_status_to_all) {
                          $updateQuery = "UPDATE specification SET status = ? WHERE spec_name = ?";
                          $updatestmt = $conn->prepare($updateQuery);
                          $updatestmt->bind_param('ss', $status, $spec_name);
                  
                      // Otherwise, update only the current specification
                      } else {
                          $updateQuery = "UPDATE specification SET spec_type = ?, price = ?, status = ? WHERE id = ?";
                          $updatestmt = $conn->prepare($updateQuery);
                          $updatestmt->bind_param('sssi', $spec_type, $price, $status, $id);
                      }
                  
                      // Execute the update query
                      if ($updatestmt->execute()) {
                          if ($updatestmt->affected_rows > 0) {
                              $_SESSION['message'] = "Print specification updated successfully!";
                              $_SESSION['msg_type'] = "success";
                          } else {
                              $_SESSION['message'] = "No changes made to the specification.";
                              $_SESSION['msg_type'] = "warning";
                          }
                          header("Location: printspec.php?action=view");
                          exit;
                      } else {
                          error_log("Error updating specification: " . $conn->error);
                          $_SESSION['message'] = "Error updating print specification.";
                          $_SESSION['msg_type'] = "danger";
                          header("Location: printspec.php?action=view");
                          exit;
                      }
                  }
                  
                  

                    // Display the form for editing the specification
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

                    <div class="container">
                        <h2>Edit Specification</h2>

                        <!-- Form to edit specification details -->
                        <form method="POST" action="">
                            <table>
                                <tr>
                                    <th>Specification Name</th>
                                    <td><input type="text" name="spec_name" value="<?php echo htmlspecialchars($spec['spec_name']); ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>Specification Type</th>
                                    <td><input type="text" name="spec_type" value="<?php echo htmlspecialchars($spec['spec_type']); ?>" required></td>
                                </tr>
                                <tr>
                                    <th>Price</th>
                                    <td>
                                        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($spec['price']); ?>" required>
                                        <br>
                                        <!-- Checkbox to apply price to all specification types under the same specification name -->
                                        <label>
                                            <input type="checkbox" name="apply_price_to_all" value="1"> Apply price to all specification types under this specification name
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <select name="status" required>
                                            <option value="available" <?php if ($spec['status'] == 'available') echo 'selected'; ?>>Available</option>
                                            <option value="unavailable" <?php if ($spec['status'] == 'unavailable') echo 'selected'; ?>>Unavailable</option>
                                        </select>
                                        <br>
                                        <!-- Checkbox to apply status to all specification types under the same specification name -->
                                        <label>
                                            <input type="checkbox" name="apply_status_to_all" value="1"> Apply status to all specification types under this specification name
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;">
                                        <input type="submit" value="Update Service" style="padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        <button onclick="history.go(-1);" style="padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                                            Back
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>



                    <?php
                } else {
                    echo "No specification ID provided!";
                }
                break;

     

        case 'add':
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {
              // Handle form submission for adding new specification and types
              $spec_name = $_POST['spec_name'];
              $spec_types = $_POST['spec_type']; // This will be an array
              $prices = $_POST['price']; // This will be an array
              $statuses = $_POST['status']; // This will be an array
        
              // Prepare the statement
              $insertstmt = $conn->prepare("INSERT INTO specification (spec_name, spec_type, price, status) VALUES (?, ?, ?, ?)");
      
              // Loop through the specification types and prices
              foreach ($spec_types as $index => $spec_type) {
                $price = floatval($prices[$index]); // Get the corresponding price
                  $status = $statuses[$index]; // Get the corresponding status
                  
                  // Bind parameters
                  $insertstmt->bind_param('ssds', $spec_name, $spec_type, $price, $status);
                  
                  if ($insertstmt->execute()) {
                    if ($insertstmt->affected_rows > 0) {
                        $_SESSION['message'] = "Specification added successfully!";
                        $_SESSION['msg_type'] = "success"; 
                    } else {
                        $_SESSION['message'] = "Error adding specification.";
                        $_SESSION['msg_type'] = "danger";
                        header("Location: printspec.php?action=view");
                        exit;
                    }
                } else {
                    // Log the error or display it
                    error_log("Error adding specification: " . $insertstmt->error);
                    $_SESSION['message'] = "Error adding specification.";
                    $_SESSION['msg_type'] = "danger";
                    header("Location: printspec.php?action=view");
                    exit;
                }
            }
              
              // Close the statement
              $insertstmt->close();
              
              // Redirect to view the list of specifications
              header("Location: printspec.php?action=view");
              exit;
          } else {
              // Display the form for adding a new specification
              ?>
              
              <!-- Add styling to the add specification form -->
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
      
                  input[type="text"], input[type="number"], textarea {
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
      
      <h2>Add New Specification</h2>

        <form method="POST" action="">
            <table>
                <tr>
                    <th>Specification Name</th>
                    <td><input type="text" name="spec_name" required></td>
                </tr>
            </table>

            <h3>Specification Types</h3>
            <label>
                <input type="checkbox" id="same-price-checkbox"> Apply the same price for all specification types
            </label>
            
            <div id="spec-type-section">
                <div class="spec-type-entry">
                    <table>
                        <tr>
                            <th>Specification Type</th>
                            <td><input type="text" name="spec_type[]" required></td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td><input type="number" step="0.01" name="price[]" class="price-input" required></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <select name="status[]">
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <button type="button" class="remove-btn" onclick="removeType(this)">Remove Type</button>
                </div>
            </div>

            <button type="button" class="add-more" onclick="addType()">Add More Types</button><br><br>

            <td colspan="2" style="text-align: center;">
                <input type="submit" value="Add Specification" style="padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                <button onclick="window.history.back();" style="padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                    Back
                </button>
            </td>
        </form>

              <script>
                 document.getElementById('same-price-checkbox').addEventListener('change', function() {
                    const priceInputs = document.querySelectorAll('.price-input');
                    
                    if (this.checked) {
                        const firstPrice = priceInputs[0].value;
                        if (!firstPrice) {
                            alert("Please enter the price for the first specification type before applying the same price to others.");
                            this.checked = false; // Uncheck the checkbox if no price is entered
                            return;
                        }
                        priceInputs.forEach((input, index) => {
                            if (index > 0) {
                                input.value = firstPrice;  // Auto-fill the same price in all other fields
                            }
                        });
                    }
                });

                function addType() {
                    const specTypeSection = document.getElementById('spec-type-section');
                    const newEntry = document.createElement('div');
                    newEntry.classList.add('spec-type-entry');
                    
                    newEntry.innerHTML = `
                        <table>
                            <tr>
                                <th>Specification Type</th>
                                <td><input type="text" name="spec_type[]" required></td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td><input type="number" step="0.01" name="price[]" class="price-input" required></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <select name="status[]">
                                        <option value="available">Available</option>
                                        <option value="unavailable">Unavailable</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <button type="button" class="remove-btn" onclick="removeType(this)">Remove Type</button>
                    `;

                    specTypeSection.appendChild(newEntry);

                    // If checkbox is checked, auto-fill the new price field
                    if (document.getElementById('same-price-checkbox').checked) {
                        const priceInputs = document.querySelectorAll('.price-input');
                        const firstPrice = priceInputs[0].value;
                        if (firstPrice) {
                            priceInputs[priceInputs.length - 1].value = firstPrice; // Fill the last added price input
                        }
                    }
                }

                function removeType(button) {
                    button.closest('.spec-type-entry').remove();
                }

              </script>
      
              <?php
          }
          break;
      
          case 'delete':
    if (isset($_POST['specification_name'])) {
        $specName = $_POST['specification_name']; // Corrected the variable name
        $deleteAll = isset($_POST['delete_all']) ? $_POST['delete_all'] : 0;

        if ($deleteAll) {
            // Delete all specifications related to the selected name
            $deleteQuery = "DELETE FROM specification WHERE spec_name = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("s", $specName);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "All specifications related to '$specName' have been deleted successfully.";
            } else {
                echo "No specifications found related to '$specName'.";
            }
        } else {
            // Delete only the clicked specification
            if (isset($_POST['specification_id'])) { // Check if specification_id is set
                $specId = $_POST['specification_id']; // Use the correct key here
                $deleteQuery = "DELETE FROM specification WHERE id = ?";
                $stmt = $conn->prepare($deleteQuery);
                $stmt->bind_param("i", $specId); // Use the correct variable
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "Specification deleted successfully.";
                } else {
                    echo "Error deleting the specification.";
                }
            } else {
                echo "Specification ID is required for deletion.";
            }
        }
    } else {
        echo "Specification name is required for deletion.";
    }
    break;

        
    default:
        // Default action is to view customers
        header("Location: printspec.php?action=view");
        break;
}
?>

<style>
  .modal {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed; /* Use fixed positioning */
    top: 3 %;
    left: 35%;
    right: 0%;
    bottom: 0;
}

.modal-content {
    background-color: white; /* Background color of the modal */
    border-radius: 8px; /* Rounded corners */
    padding: 20px; /* Padding inside the modal */
    width: 400px; /* Fixed width for the modal */
    max-width: 90%; /* Responsive width */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
}

</style>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <h4>Are you sure you want to delete this specification?</h4>
        <p>This action cannot be undone.</p>

        <!-- Checkbox to delete all related specification types -->
        <label>
            <input type="checkbox" id="deleteAllSpecsCheckbox">
            Delete all specification types related to this specification name.
        </label>

        <!-- Hidden inputs to store the specification name and ID -->
        <input type="hidden" id="specificationName" value="">
        <input type="hidden" id="specificationId" value="">

        <div class="modal-footer">
            <button id="confirmDelete" class="btn btn-danger">Confirm Delete</button>
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
function openDeleteModal(specificationName, specificationId) {
    document.getElementById('specificationName').value = specificationName;
    document.getElementById('specificationId').value = specificationId; 
    document.getElementById('deleteModal').style.display = 'block';
}

// Function to close the modal
function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Handle the delete confirmation
document.getElementById('confirmDelete').addEventListener('click', function() {
    const specificationName = document.getElementById('specificationName').value;
    const specificationId = document.getElementById('specificationId').value;
    const deleteAll = document.getElementById('deleteAllSpecsCheckbox').checked;
    
    // Send data to the server to delete the specific record or all related ones
    deleteSpecification(specificationId, specificationName, deleteAll);
    closeModal();
});

// AJAX function to handle the deletion
function deleteSpecification(specificationId, specificationName, deleteAll) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "printspec.php?action=delete", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Send whether to delete all related specifications or just the one
    xhr.send("specification_id=" + specificationId + "&specification_name=" + specificationName + "&delete_all=" + (deleteAll ? 1 : 0));

    xhr.onload = function() {
        if (xhr.status === 200) {
            alert(xhr.responseText);
            // Reload the page or update the table
            location.reload();
        }
    }
}
</script>



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
