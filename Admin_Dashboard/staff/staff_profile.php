<?php
include '../../connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

$query = "SELECT name, username, email, contact, address, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'] ?? "N/A";
    $usernames = $row['username'] ?? "N/A";
    $email = $row['email'] ?? "N/A";
    $contact = $row['contact'] ?? "N/A";
    $address = $row['address'] ?? "N/A";
    $profile_pic = $row['profile_pic'] ?? '../assets/profile_pic/default-placeholder.png'; 

} else {
    $name = "N/A";
    $usernames = "N/A";
    $email = "N/A";
    $contact = "N/A";
    $address = "N/A";
    $profile_pic = '../assets/profile_pic/default-placeholder.png'; 

}
$stmt->close();

$profilePicPath = !empty($profile_pic) ? htmlspecialchars($profile_pic) : '../assets/profile_pic/default-placeholder.png';

$conn->close();
?>



<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="robots" content="noindex,nofollow" />
  <title>Staff Profile</title>
  <link rel="canonical" href="https://www.wrappixel.com/templates/xtreme-admin-lite/" />
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />
  <link href="../dist/css/style.css" rel="stylesheet" />
  
  <!-- Embedded CSS for customization -->
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    
    .topbar {
      background-color: #343a40;
      padding: 10px 0;
    }
    
    .navbar-brand img {
      height: 40px;
    }
    
    .navbar-nav .nav-link {
      color: #ffffff;
    }
    
    .navbar-nav .nav-link:hover {
      color: #cccccc;
    }
    
    .profile-sidebar {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .profile-userpic img {
      border-radius: 50%;
      width: 150px;
      height: 150px;
      margin-bottom: 20px;
    }
    
    .profile-usertitle {
      text-align: center;
    }
    
    .profile-usertitle-name {
      font-size: 20px;
      font-weight: bold;
    }
    
    .profile-usertitle-job {
      font-size: 16px;
      color: #777777;
    }
    
    .profile-userbuttons {
      text-align: center;
      margin-top: 20px;
    }
    
    .profile-userbuttons .btn-primary {
      background-color: #007bff;
      border: none;
    }
    
    .profile-userbuttons .btn-primary:hover {
      background-color: #0056b3;
    }
    
    .profile-usermenu ul {
      list-style: none;
      padding: 0;
    }
    
    .profile-usermenu ul li {
      margin-bottom: 10px;
    }
    
    .profile-usermenu ul li a {
      text-decoration: none;
      color: #007bff;
    }
    
    .profile-usermenu ul li a:hover {
      text-decoration: underline;
    }
    
    .footer {
      background-color: #343a40;
      color: #ffffff;
      padding: 20px;
      text-align: center;
      position: relative;
      bottom: 0;
      width: 100%;
    }

    /* Edit Profile Button Styles */
.btn-primary {
  background-color: #007bff; /* Primary button color */
  border: none;
  border-radius: 4px;
  width: 150px;
  color: #ffffff; /* Text color */
  font-size: 16px;
  font-weight: bold;
  padding: 10px 20px; /* Vertical and horizontal padding */
  text-align: center;
  text-decoration: none;
  display: inline-block;
  transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transitions */
}

.btn-primary:hover {
  background-color: #0056b3; /* Darker color on hover */
  text-decoration: none;
}

.btn-primary:active {
  background-color: #004494; /* Even darker color on click */
  transform: translateY(1px); /* Button presses down slightly */
}

.btn-primary:focus {
  outline: none; /* Remove default outline */
  box-shadow: 0 0 0 2px rgba(38, 143, 255, 0.5); /* Custom focus outline */
}

  </style>
  
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="../service/style.css">
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
  <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
    <header class="topbar" data-navbarbg="skin5">
      <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin5">
          <a class="navbar-brand" href="staff_page.php">
            <b class="logo-icon">
              <img src="../../assets/images/logo.png" alt="homepage" style="width: 60px; height: auto;" />
            </b>
          </a>
          <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
        </div>
        <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
          <ul class="navbar-nav float-start me-auto">
            <li class="nav-item search-box">
              <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-magnify fs-4"></i></a>
              <form class="app-search position-absolute">
                <input type="text" class="form-control" placeholder="Search &amp; enter" />
                <a class="srh-btn"><i class="mdi mdi-close"></i></a>
              </form>
            </li>
          </ul>
          <ul class="navbar-nav float-end">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31" />
              </a>
              <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="pages_profile.php"><i class="mdi mdi-account m-r-5 m-l-5"></i> My Profile</a>
                <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-email m-r-5 m-l-5"></i> Inbox</a>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <?php include "../sidebar/sidebarStaff.php";?>
    <div class="page-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Staff Profile</h4>
                <div class="row">
                  <div class="col-md-3 col-sm-12">
                    <div class="profile-sidebar">
                      <div class="profile-userpic">
                      <img src="<?php echo $profilePicPath; ?>" alt="Profile Picture" id="profile_pic" style="width: 120px; height: 120px; border-radius: 50%; margin-left: 50px;">
                      </div>
                      <div class="profile-usertitle">
                        <div class="profile-usertitle-name"> <?php echo htmlspecialchars($name); ?> </div>
                        <div class="profile-usertitle-job"> <?php echo htmlspecialchars($usernames); ?> </div>
                      </div>
                      <div class="profile-userbuttons">
                        <a href="update_StaffProfile.php" class="btn  btn-primary">Edit Profile</a>
                      </div>
                      <!--<div class="profile-usermenu">
                        <ul class="nav">
                          <li class="active">
                            <a href="javascript:void(0)">
                              <i class="glyphicon glyphicon-home"></i> Overview
                            </a>
                          </li>
                          <li>
                            <a href="javascript:void(0)">
                              <i class="glyphicon glyphicon-user"></i> Account Settings
                            </a>
                          </li>
                        </ul>
                      </div>-->
                    </div>
                  </div>
                  <div class="col-md-9 col-sm-12">
                    <div class="profile-content">
                      <h5>Email: <?php echo htmlspecialchars($email); ?></h5>
                      <h5>Contact: <?php echo htmlspecialchars($contact); ?></h5>
                      <h5>Address: <?php echo htmlspecialchars($address); ?></h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer text-center">
        All Rights Reserved by Xtreme Admin. Designed and Developed by <a href="https://www.wrappixel.com">WrapPixel</a>.
      </footer>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap tether Core JavaScript -->
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../dist/js/app-style-switcher.js"></script>
  <!--Wave Effects -->
  <script src="../dist/js/waves.js"></script>
  <!--Menu sidebar -->
  <script src="../dist/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="../dist/js/custom.js"></script>
  <!--This page JavaScript -->
  <!--chartis chart-->
  <script src="../assets/libs/chartist/dist/chartist.min.js"></script>
  <script src="../assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
  <script src="../dist/js/pages/dashboards/dashboard1.js"></script>
</body>

</html>
