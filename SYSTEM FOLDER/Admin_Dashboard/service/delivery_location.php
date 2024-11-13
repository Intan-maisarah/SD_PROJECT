<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    <title>Delivery Location Management</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />
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
            require '../../connection.php';
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

switch ($action) {
    case 'view':
        $query = 'SELECT * FROM delivery_locations';
        $result = mysqli_query($conn, $query);

        echo "<div class='list-header'>
                            <h2>Location List</h2>
                            <a href='delivery_location.php?action=add' class='button button-add'>Add Location</a>
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
        echo '<table><tr><th>ID</th><th>Location</th><th>Action</th></tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td>'.$row['location_name'].'</td>';
            echo "<td>
                                <a href='delivery_location.php?action=delete&id=".$row['id']."' class='button button-delete' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                              </td>";
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
        break;

    case 'delete':
        $id = $_GET['id'];
        $deleteQuery = 'DELETE FROM delivery_locations WHERE id = ?';
        $deleteStmt = $conn->prepare($deleteQuery);
        if ($deleteStmt) {
            $deleteStmt->bind_param('i', $id);
            $deleteStmt->execute();

            if ($deleteStmt->affected_rows > 0) {
                $_SESSION['message'] = 'Location deleted successfully!';
                $_SESSION['msg_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error deleting location.';
                $_SESSION['msg_type'] = 'danger';
            }
            $deleteStmt->close();
        }
        header('Location: delivery_location.php?action=view');
        exit;

    case 'add':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['location_name'])) { // Check if location_name is set
                $location = $_POST['location_name'];
                $insertQuery = 'INSERT INTO delivery_locations (location_name) VALUES (?)';
                $insertStmt = $conn->prepare($insertQuery);

                $insertStmt->bind_param('s', $location);

                $insertStmt->execute();

                if ($insertStmt->affected_rows > 0) {
                    $_SESSION['message'] = 'Location added successfully!';
                    $_SESSION['msg_type'] = 'success';
                    header('Location: delivery_location.php?action=view');
                    exit;
                } else {
                    $_SESSION['message'] = 'Error adding location.';
                    $_SESSION['msg_type'] = 'danger';
                    header('Location: delivery_location.php?action=view');
                    exit;
                }
            } else {
                $_SESSION['message'] = 'Location name cannot be empty.';
                $_SESSION['msg_type'] = 'danger';
                header('Location: delivery_location.php?action=add');
                exit;
            }
        } else {
            ?>
        
                <h2>Add Location</h2>
                <form method="POST">
                    <label for="location">Location Name:</label><br>
                    <input type="text" name="location_name" required><br> 
                    <br>
                    <input type="submit" value="Add Location" class="button button-add">
                    <button onclick="history.go(-1);" class="button button-back">Back</button>
                </form>
        
                <?php
        }
        break;

    default:
        echo 'Invalid action.';
        break;
}
$conn->close();
?>
        </div>
    </div>

    <footer class="footer text-center">
        All Rights Reserved by Infinity Printing. Designed and Developed by <a href="https://www.wrappixel.com">WrapPixel</a>.
    </footer>

    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
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
