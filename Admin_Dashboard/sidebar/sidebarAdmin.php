<?php
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
$profile_pic = '';

$sql = 'SELECT email, profile_pic FROM users WHERE id = ?';
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
    $profile_pic = $row['profile_pic'];
} else {
    $adminEmail = 'Email not found';
}

$stmt->close();
$profilePicPath = !empty($profile_pic) ? htmlspecialchars($profile_pic) : '../assets/profile_pic/default-placeholder.png';
$conn->close();

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
          <div class="user-profile d-flex no-block dropdown m-t-20">
            <div class="user-pic">
              <img src="<?php echo htmlspecialchars($profilePicPath); ?>" alt="Profile Picture" class="rounded-circle" width="40" />
            </div>
            <div class="user-content hide-menu m-l-10">
              <a href="#" class="" id="Userdd" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <h5 class="m-b-0 user-name font-medium">ADMIN <i class="mdi mdi-chevron-down fs-4"></i></h5>
                <span class="hide-menu"><?php echo htmlspecialchars($adminEmail ?? 'Email not found', ENT_QUOTES, 'UTF-8'); ?></span>
              </a>

              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="Userdd">
                <a class="dropdown-item" href="../admin/pages_profile.php"><i class="mdi mdi-account m-r-5 m-l-5"></i> My Profile</a>
                <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-wallet m-r-5 m-l-5"></i> My Balance</a>
                <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-email m-r-5 m-l-5"></i> Inbox</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-settings m-r-5 m-l-5"></i> Account Setting</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../../logout.php"><i class="mdi mdi-power m-r-5 m-l-5"></i> Logout</a>
              </div>
            </div>
          </div>
        </li>
        
        <!-- Sidebar Links -->
        <li class="sidebar-item">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../admin/admin_page.php" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../admin/pages_profile.php" aria-expanded="false"><i class="mdi mdi-account-network"></i><span class="hide-menu">Profile</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../service/staff.php" aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Staff</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../service/customer.php" aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Customer</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../service/printspec.php" aria-expanded="false"><i class="mdi mdi-printer"></i><span class="hide-menu">Printing</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../service/services.php" aria-expanded="false"><i class="mdi mdi-briefcase"></i><span class="hide-menu">Services</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../service/delivery_location.php" aria-expanded="false"><i class="mdi mdi-map-marker"></i><span class="hide-menu">Delivery Locations</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../service/order.php" aria-expanded="false"><i class="mdi mdi-cart"></i><span class="hide-menu">Orders</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="table_basic.html" aria-expanded="false"><i class="mdi mdi-border-all"></i><span class="hide-menu">Table</span></a>
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
    color: black; 
    text-decoration: none; 
}

#Userdd .hide-menu {
    color: grey; 
}

.left-sidebar {
    width: 250px; 
    transition: width 0.3s ease;
}

.left-sidebar.collapsed {
    width: 60px; 
}

.sidebar-item {
    display: flex;
    align-items: center; 
}

.sidebar-item .hide-menu {
    display: inline-block; 
}

.collapsed .sidebar-item .hide-menu {
    display: none; 
}

.collapsed .sidebar-link {
    justify-content: center; 
}

.collapsed .user-content {
    display: none; 
}

.collapsed .user-pic img {
    width: 30px; 
}

.collapsed .sidebar-link {
    padding: 15px; 
}

.left-sidebar {
    background-color: #f8f9fa; 
}


</style>