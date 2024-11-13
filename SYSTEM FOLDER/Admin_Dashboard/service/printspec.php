<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
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
  <title>Printing Management</title>
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />
  <!-- Custom CSS -->
  <link href="../dist/css/style.min.css" rel="stylesheet" />
  <link href="style.css" rel="stylesheet">
 
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
    
  <?php include '../sidebar/header.php'; ?>
    
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
include '../../connection.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'view';

switch ($action) {
    // Updated "view" case
    case 'view':
        $query = 'SELECT s.id, sn.spec_name, s.spec_type, s.price, s.status 
              FROM specification s
              JOIN spec_names sn ON s.spec_name_id = sn.id';
        $result = mysqli_query($conn, $query);

        echo "<div class='list-header'>
        <h2>Print Specification</h2>
        <a href='printspec.php?action=add' class='button button-add'>Add Print Specification</a>
      </div>";

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
        echo '<tr><th>ID</th><th>Specification Name</th><th>Specification Type</th><th>Price</th><th>Status</th><th>Actions</th></tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td>'.$row['spec_name'].'</td>';
            echo '<td>'.$row['spec_type'].'</td>';
            echo '<td>'.$row['price'].'</td>';
            echo '<td>'.$row['status'].'</td>';
            echo "<td>
          <a href='printspec.php?action=edit&id=".$row['id']."' class='button button-edit'>Edit</a> |
          <a href='javascript:void(0);' class='button button-delete' onclick='openDeleteModal(\"".addslashes($row['spec_name']).'", '.$row['id'].")'>Delete</a>
        </td>";
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';
        break;

    case 'edit':
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);

            // Select from both specification and spec_names tables
            $stmt = $conn->prepare('SELECT s.*, sn.spec_name FROM specification s JOIN spec_names sn ON s.spec_name_id = sn.id WHERE s.id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $spec = $result->fetch_assoc();
            } else {
                echo 'Specification not found!';
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $spec_name = $_POST['spec_name'];
                $spec_type = $_POST['spec_type'];
                $price = $_POST['price'];
                $status = $_POST['status'];
                $apply_price_to_all = isset($_POST['apply_price_to_all']);
                $apply_status_to_all = isset($_POST['apply_status_to_all']);

                // Update the spec_name in spec_names table if changed
                $stmt = $conn->prepare('UPDATE spec_names SET spec_name = ? WHERE id = ?');
                $stmt->bind_param('si', $spec_name, $spec['spec_name_id']);
                $stmt->execute();

                // Update the specific specification details
                $updateQuery = 'UPDATE specification SET spec_type = ?, price = ?, status = ? WHERE id = ?';
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param('sdsi', $spec_type, $price, $status, $id);

                if ($stmt->execute()) {
                    // Apply price to all specifications under the same spec_name_id
                    if ($apply_price_to_all) {
                        $stmt = $conn->prepare('UPDATE specification SET price = ? WHERE spec_name_id = ?');
                        $stmt->bind_param('di', $price, $spec['spec_name_id']);
                        $stmt->execute();
                    }

                    // Apply status to all specifications under the same spec_name_id
                    if ($apply_status_to_all) {
                        $stmt = $conn->prepare('UPDATE specification SET status = ? WHERE spec_name_id = ?');
                        $stmt->bind_param('si', $status, $spec['spec_name_id']);
                        $stmt->execute();
                    }

                    if ($stmt->affected_rows > 0) {
                        $_SESSION['message'] = 'Specification updated successfully!';
                        $_SESSION['msg_type'] = 'success';
                    } else {
                        $_SESSION['message'] = 'No changes made to the specification.';
                        $_SESSION['msg_type'] = 'warning';
                    }
                    header('Location: printspec.php?action=view');
                    exit;
                } else {
                    error_log('Error updating specification: '.$conn->error);
                    $_SESSION['message'] = 'Error updating specification.';
                    $_SESSION['msg_type'] = 'danger';
                    header('Location: printspec.php?action=view');
                    exit;
                }
            }
            ?>
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
                                <label>
                                    <input type="checkbox" name="apply_price_to_all" value="1"> Apply price to all specification types under this specification name
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <select name="status" required>
                                    <option value="available" <?php if ($spec['status'] == 'available') {
                                        echo 'selected';
                                    } ?>>Available</option>
                                    <option value="unavailable" <?php if ($spec['status'] == 'unavailable') {
                                        echo 'selected';
                                    } ?>>Unavailable</option>
                                </select>
                                <br>
                                <label>
                                    <input type="checkbox" name="apply_status_to_all" value="1"> Apply status to all specification types under this specification name
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type="submit" value="Update" class="button button-edit">
                                <button onclick="history.go(-1);" class="button button-back">Back</button>
                            </td>
                        </tr>
                    </table>
                </form>
                <?php
        } else {
            echo 'No specification ID provided!';
        }
        break;

    case 'add':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $spec_types = $_POST['spec_type'] ?? [];
            $prices = $_POST['price'] ?? [];
            $statuses = $_POST['status'] ?? [];

            if ($_POST['spec_name_option'] === 'existing') {
                $spec_name_id = intval($_POST['existing_spec_name_id']);
            } else {
                $spec_name = trim($_POST['new_spec_name']);
                if (!empty($spec_name)) {
                    $stmt = $conn->prepare('INSERT INTO spec_names (spec_name) VALUES (?)');
                    $stmt->bind_param('s', $spec_name);
                    if ($stmt->execute()) {
                        $spec_name_id = $stmt->insert_id;
                    } else {
                        $_SESSION['message'] = 'Error adding new specification name.';
                        $_SESSION['msg_type'] = 'danger';
                        header('Location: printspec.php?action=view');
                        exit;
                    }
                } else {
                    $_SESSION['message'] = 'New specification name cannot be empty.';
                    $_SESSION['msg_type'] = 'danger';
                    header('Location: printspec.php?action=view');
                    exit;
                }
            }

            $insertstmt = $conn->prepare('INSERT INTO specification (spec_name_id, spec_type, price, status) VALUES (?, ?, ?, ?)');
            $errors = [];

            foreach ($spec_types as $index => $spec_type) {
                $price = floatval($prices[$index]);
                $status = $statuses[$index];

                $insertstmt->bind_param('isds', $spec_name_id, $spec_type, $price, $status);
                if (!$insertstmt->execute()) {
                    $errors[] = 'Error inserting spec_type '.$spec_type.': '.$insertstmt->error;
                }
            }

            if (!empty($errors)) {
                $_SESSION['message'] = 'Some specifications failed to add. Check logs.';
                $_SESSION['msg_type'] = 'danger';
                foreach ($errors as $error) {
                }
            } else {
                $_SESSION['message'] = 'Specification(s) added successfully!';
                $_SESSION['msg_type'] = 'success';
            }

            $insertstmt->close();

            header('Location: printspec.php?action=view');
            exit;
        } else {
            ?>
                <form method="POST" action="">
                <h2>Add Specification</h2>
        
                    <table>
                        <tr>
                            <th>Select Existing Specification Name</th>
                            <td>
                                <label>
                                    <input type="radio" name="spec_name_option" value="existing" checked onclick="toggleSpecNameInput('existing')"> 
                                    Select Existing
                                </label>
                                <select name="existing_spec_name_id" id="existing_spec_name" required>
                                    <option value="">-- Select --</option>
                                    <?php
                                $result = $conn->query('SELECT id, spec_name FROM spec_names');
            while ($row = $result->fetch_assoc()) {
                echo "<option value='".$row['id']."'>".$row['spec_name'].'</option>';
            }
            ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Create New Specification Name</th>
                            <td>
                                <label>
                                    <input type="radio" name="spec_name_option" value="new" onclick="toggleSpecNameInput('new')"> 
                                    Create New
                                </label>
                                <input type="text" name="new_spec_name" id="new_spec_name" placeholder="New Specification Name" disabled>
                            </td>
                        </tr>
                    </table>

                    <div class="button-container" style="justify-content: center;">
                        <input type="submit" value="Add Specification" class="button button-add">
                        <button type="button" onclick="window.history.back();" class="button button-back">Back</button>
                    </div>
        
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
                                        <select name="status[]" style="width: 200px; height: 40px;">
                                            <option value="available">Available</option>
                                            <option value="unavailable">Unavailable</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <button type="button" class="button button-delete" onclick="removeType(this)">Delete Type</button>
                            <button type="button" class="button button-add" onclick="addType()">Add More Types</button>
                        </div>
                    </div>
                </form>
                 <script>
                    document.getElementById('same-price-checkbox').addEventListener('change', function() {
                        const priceInputs = document.querySelectorAll('.price-input');
                        
                        if (this.checked) {
                            const firstPrice = priceInputs[0].value;
                            if (!firstPrice) {
                                alert("Please enter the price for the first specification type before applying the same price to others.");
                                this.checked = false;  
                                return;
                            }
                            priceInputs.forEach((input, index) => {
                                if (index > 0) {
                                    input.value = firstPrice;   
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
                                        <select name="status[]" style="width: 200px; height: 40px;">
                                            <option value="available">Available</option>
                                            <option value="unavailable">Unavailable</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <button type="button" class="button button-delete" onclick="removeType(this)">Remove Type</button>
                            <button type="button" class="button button-add" onclick="addType()">Add More Types</button>
                        `;
        
                        specTypeSection.appendChild(newEntry);
        
                        if (document.getElementById('same-price-checkbox').checked) {
                            const priceInputs = document.querySelectorAll('.price-input');
                            const firstPrice = priceInputs[0].value;
                            if (firstPrice) {
                                priceInputs[priceInputs.length - 1].value = firstPrice;  
                            }
                        }
                    }
        
                    function removeType(button) {
                        button.closest('.spec-type-entry').remove();
                    }
        
                    function toggleSpecNameInput(option) {
                        if (option === 'existing') {
                            document.getElementById('existing_spec_name').disabled = false;
                            document.getElementById('new_spec_name').disabled = true;
                            document.getElementById('new_spec_name').value = ''; // Clear new spec name if not used
                        } else if (option === 'new') {
                            document.getElementById('existing_spec_name').disabled = true;
                            document.getElementById('new_spec_name').disabled = false;
                            document.getElementById('existing_spec_name').value = ''; // Clear existing spec name if not used
                        }
                    }
                </script>
              
                <?php
        }
        break;

    case 'delete':
        if (isset($_POST['specification_id'])) {
            $specId = $_POST['specification_id'];

            // Delete the specification
            $stmt = $conn->prepare('DELETE FROM specification WHERE id = ?');
            $stmt->bind_param('i', $specId);
            $stmt->execute();

            // Check if there are any remaining specifications with the same spec_name
            $stmt = $conn->prepare('SELECT COUNT(*) as count FROM specification WHERE spec_name_id = (SELECT spec_name_id FROM specification WHERE id = ?)');
            $stmt->bind_param('i', $specId);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_assoc()['count'];

            if ($count == 0) {
                // If no other specifications use this spec_name, delete it
                $stmt = $conn->prepare('DELETE FROM spec_names WHERE id = (SELECT spec_name_id FROM specification WHERE id = ?)');
                $stmt->bind_param('i', $specId);
                $stmt->execute();
            }

            if ($stmt->affected_rows > 0) {
                $_SESSION['message'] = 'Specification deleted successfully!';
                $_SESSION['msg_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error deleting specification.';
                $_SESSION['msg_type'] = 'danger';
            }

            header('Location: printspec.php?action=view');
            exit;
        }
        break;

    default:
        header('Location: printspec.php?action=view');
        break;
}
?>


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

function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    const specificationName = document.getElementById('specificationName').value;
    const specificationId = document.getElementById('specificationId').value;
    const deleteAll = document.getElementById('deleteAllSpecsCheckbox').checked;
    
    deleteSpecification(specificationId, specificationName, deleteAll);
    closeModal();
});

function deleteSpecification(specificationId, specificationName, deleteAll) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "printspec.php?action=delete", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.send("specification_id=" + specificationId + "&specification_name=" + specificationName + "&delete_all=" + (deleteAll ? 1 : 0));

    xhr.onload = function() {
    if (xhr.status === 200) {
        console.log(xhr.responseText); 
        location.reload(); 
    }
}
}
</script>



    <!-- ============================================================== -->
    <!-- Footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
      All Rights Reserved by Infinity Printing. Designed and Developed by <a href="https:www.wrappixel.com">WrapPixel</a>.
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
