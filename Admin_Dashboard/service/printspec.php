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
// Prepare to fetch print specifications
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

switch ($action) {
    case 'view':
        $query = "SELECT * FROM specification";
        $result = $conn->query($query);
        
        if (!$result) {
            echo "Query failed: " . $conn->error . "<br>";
            exit;
        }
        break;

    case 'toggle':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            // Toggle status
            $stmt = $conn->prepare("UPDATE specification SET status = CASE WHEN status = 'available' THEN 'unavailable' ELSE 'available' END WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            header("Location: printspec.php?action=view");
            exit;
        }
        break;

        case 'add':
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {
              // Form submission logic to insert into the database
              $spec_name = $_POST['spec_name'];
              
              // Ensure spec_type, price, and status are arrays
              $spec_types = isset($_POST['spec_type']) ? $_POST['spec_type'] : [];
              $prices = isset($_POST['price']) ? $_POST['price'] : [];
              $statuses = isset($_POST['status']) ? $_POST['status'] : [];
        
              // Check if arrays are populated
              if (empty($spec_types) || empty($prices) || empty($statuses)) {
                  echo "Please fill all fields.";
                  return;
              }
        
              // Prepare the statement for inserting data
              $stmt = $conn->prepare("INSERT INTO specification (spec_name, spec_type, price, status) VALUES (?, ?, ?, ?)");
        
              if ($stmt) {
                  // Loop through the specification types and prices
                  foreach ($spec_types as $index => $spec_type) {
                      // Ensure the corresponding price and status exist
                      $price = floatval($prices[$index]); // Ensure price is treated as float
                      $status = $statuses[$index]; // Get the corresponding status
      
                      // Bind parameters for each row
                      $stmt->bind_param('ssds', $spec_name, $spec_type, $price, $status);
        
                      // Execute the statement
                      if (!$stmt->execute()) {
                          echo "Error adding specification: " . $stmt->error;
                      }
                  }
        
                  // Close the statement
                  $stmt->close();
        
                  // Redirect to view the list of specifications
                  header("Location: printspec.php?action=view");
                  exit;
              } else {
                  // Statement preparation error
                  echo "Error preparing statement: " . $conn->error;
              }
          }
          break;
      
      

          case 'edit':
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $id = intval($_GET['id']); // Ensure $id is an integer
        
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Sanitize and validate inputs
                    $spec_name = trim($_POST['spec_name']);
                    $spec_type = trim($_POST['spec_type']);
                    $price = floatval($_POST['price']); // Ensure price is a float
                    $status = $_POST['status'];
        
                    // Prepare the statement for update
                    $stmt = $conn->prepare("UPDATE specification SET spec_name=?, spec_type=?, price=?, status=? WHERE id=?");
                    if ($stmt) {
                        $stmt->bind_param('ssssi', $spec_name, $spec_type, $price, $status, $id);
        
                        // Execute the statement and check for errors
                        if ($stmt->execute()) {
                            header("Location: printspec.php?action=view");
                            exit;
                        } else {
                            echo "Error updating specification: " . $stmt->error;
                        }
                        $stmt->close();
                    } else {
                        echo "Error preparing statement: " . $conn->error;
                    }
                } else {
                    // Fetch the existing specification from the database
                    $stmt = $conn->prepare("SELECT * FROM specification WHERE id = ?");
                    if ($stmt) {
                        $stmt->bind_param('i', $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
        
                        if ($result && $result->num_rows > 0) {
                            $spec = $result->fetch_assoc();
                        } else {
                            echo "Specification not found!";
                            exit;
                        }
                    } else {
                        echo "Error preparing statement: " . $conn->error;
                    }
                }
            } else {
                echo "No specification ID provided!";
            }
            break;
        
        

    case 'delete':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $conn->prepare("DELETE FROM specification WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            header("Location: printspec.php?action=view");
            exit;
        }
        break;

    default:
        header("Location: printspec.php?action=view");
        exit;
}

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
    <a href='printspec.php?action=delete&id=" . $row['id'] . "' style='display: inline-block; padding: 8px 16px; text-align: center; text-decoration: none; background-color: #e53935; color: white; border-radius: 4px;' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
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
      
                  // Update query to edit the specification
                  $updateQuery = "UPDATE specification SET spec_type = ?, price = ?, status = ? WHERE id = ?";
                  $stmt = $conn->prepare($updateQuery);
                  $stmt->bind_param('sssi', $spec_type, $price, $status, $id);

                  
                  if ($stmt->execute()) {
                      // If update is successful, redirect to view the list of specifications
                      header("Location: printspec.php?action=view");
                      exit;
                  } else {
                      // Display error if update fails
                      echo "Error updating specification: " . $stmt->error;
                  }
              }
      
              // Display the form for editing the specification
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
                          <td><input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($spec['price']); ?>" required></td>
                      </tr>
                      <tr>
                          <th>Status</th>
                          <td>
                              <select name="status" required>
                                  <option value="available" <?php if ($spec['status'] == 'available') echo 'selected'; ?>>Available</option>
                                  <option value="unavailable" <?php if ($spec['status'] == 'unavailable') echo 'selected'; ?>>Unavailable</option>
                              </select>
                          </td>
                      </tr>
                      <tr>
                          <td colspan="2" style="text-align: center;">
                              <input type="submit" value="Update Specification" style="padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                              <button onclick="window.history.back();" style="padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                                  Back
                              </button>
                          </td>
                      </tr>

                  </table>
              </form>
      
              <?php
          } else {
              echo "No specification ID provided!";
          }
          break;
      
      
      

          
    case 'delete':
        // Delete customer
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $deleteQuery = "DELETE FROM specification WHERE id='$id'";
            mysqli_query($conn, $deleteQuery);
            header("Location: printspec.php?action=view"); // Redirect to view after deleting
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
              $stmt = $conn->prepare("INSERT INTO specification (spec_name, spec_type, price, status) VALUES (?, ?, ?, ?)");
      
              // Loop through the specification types and prices
              foreach ($spec_types as $index => $spec_type) {
                $price = floatval($prices[$index]); // Get the corresponding price
                  $status = $statuses[$index]; // Get the corresponding status
                  
                  // Bind parameters
                  $stmt->bind_param('ssds', $spec_name, $spec_type, $price, $status);
                  
                  // Execute the statement
                  if (!$stmt->execute()) {
                      echo "Error adding specification: " . $stmt->error;
                  }
              }
              
              // Close the statement
              $stmt->close();
              
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
                  <div id="spec-type-section">
                      <div class="spec-type-entry">
                          <table>
                              <tr>
                                  <th>Specification Type</th>
                                  <td><input type="text" name="spec_type[]" required></td>
                              </tr>
                              <tr>
                                  <th>Price</th>
                                  <td><input type="number" step="0.01" name="price[]" required></td>
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
                      </tr>

              </form>
      
              <script>
                  // Function to dynamically add more specification types
                  function addType() {
                      let section = document.getElementById('spec-type-section');
                      let newTypeEntry = document.createElement('div');
                      newTypeEntry.classList.add('spec-type-entry');
                      newTypeEntry.innerHTML = `
                          <table>
                              <tr>
                                  <th>Specification Type</th>
                                  <td><input type="text" name="spec_type[]" required></td>
                              </tr>
                              <tr>
                                  <th>Price</th>
                                  <td><input type="number" step="0.01" name="price[]" required></td>
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
                      section.appendChild(newTypeEntry);
                  }
      
                  // Function to remove a specification type entry
                  function removeType(button) {
                      button.parentElement.remove();
                  }
              </script>
      
              <?php
          }
          break;
      
      

    default:
        // Default action is to view customers
        header("Location: printspec.php?action=view");
        break;
}
?>

    <!-- ============================================================== -->
    <!-- Footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
      All Rights Reserved by Your Company. Designed and Developed by <a href="https://www.wrappixel.com">WrapPixel</a>.
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
