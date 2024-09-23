@ -1,156 +0,0 @@
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
$userEmail = '';

// Prepare and execute a query to get the user's email
$sql = "SELECT email FROM users WHERE id = ?";
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
    $userEmail = $row['email'];
} else {
    $userEmail = "Email not found";
}

// Close statement and connection
$stmt->close();
$conn->close();

// Debugging output for email
?>

<!-- Sidebar HTML Structure -->
<aside class="left-sidebar" data-sidebarbg="skin6">
      <!-- Sidebar scroll-->
      <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
          <ul id="sidebarnav">
            <!-- User Profile-->
            <li>
              <!-- User Profile-->
              <div class="user-profile d-flex no-block dropdown m-t-20">
                <div class="user-pic">
                  <img src="../assets/images/users/1.jpg" alt="users" class="rounded-circle" width="40" />
                </div>
                <div class="user-content hide-menu m-l-10">
                 <!-- HTML -->
                    <a href="#" class="" id="Userdd" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <h5 class="m-b-0 user-name font-medium">
                            STAFF <i class="mdi mdi-chevron-down fs-4"></i>
                        </h5>
                        <span class="hide-menu"> <?php echo htmlspecialchars($userEmail ?? 'Email not found', ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>

                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="Userdd">
                    <a class="dropdown-item" href="staff_profile.php"><i class="mdi mdi-account m-r-5 m-l-5"></i> My
                      Profile</a>
                    <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-wallet m-r-5 m-l-5"></i> My
                      Balance</a>
                    <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-email m-r-5 m-l-5"></i>
                      Inbox</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-settings m-r-5 m-l-5"></i>
                      Account
                      Setting</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="../../logout.php"><i class="mdi mdi-power m-r-5 m-l-5"></i>
                      Logout</a>
                  </div>
                </div>
              </div>
              <!-- End User Profile-->
            </li>
            <li class="p-15 m-t-10">
              <a href="javascript:void(0)" class="
                    btn
                    d-block
                    w-100
                    create-btn
                    text-white
                    no-block
                    d-flex
                    align-items-center
                  "><i class="mdi mdi-plus-box"></i>
                <span class="hide-menu m-l-5">Create New</span>
              </a>
            </li>
            <!-- User Profile-->
            <li class="sidebar-item">
              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="staff_page.php" aria-expanded="false"><i
                  class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="staff_profile.php"
                aria-expanded="false"><i class="mdi mdi-account-network"></i><span class="hide-menu">Profile</span></a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="customer.php"
                aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Customer</span></a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="printspec.php"
                aria-expanded="false"><i class="mdi mdi-printer"></i><span class="hide-menu">Printing</span></a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="services.php"
                aria-expanded="false"><i class="mdi mdi-printer"></i><span class="hide-menu">Services</span></a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link waves-effect waves-dark sidebar-link" href="table_basic.html"
                aria-expanded="false"><i class="mdi mdi-border-all"></i><span class="hide-menu">Table</span></a>
            </li>
            </li>
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>

<!-- CSS Links -->
<link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet" />
<link href="../dist/css/style.min.css" rel="stylesheet" />

<style>
    /* CSS */
#Userdd {
    color: black; /* Change the text color of the <a> tag */
    text-decoration: none; /* Remove underline from <a> tag */
}

#Userdd .hide-menu {
    color: grey; /* Ensure email text color is set */
}

</style>