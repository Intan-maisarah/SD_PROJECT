<?php
session_start();
require '../../connection.php'; 

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

$name = '';
$email = '';
$usernames = '';
$profile_pic = '';

$query = "SELECT name, email, username, password, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $usernames, $hashedPassword, $profile_pic);


if (!$stmt->fetch()) {
    die("User not found.");
}

$stmt->close();

$profilePicPath = !empty($profile_pic) ? htmlspecialchars($profile_pic) : '../assets/profile_pic/default-placeholder.png';

function validatePassword($password) {
    return preg_match('/.{8,}/', $password) &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/\d/', $password) &&
           preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword !== $confirmPassword) {
        $error = 'New passwords do not match.';
    } elseif (!validatePassword($newPassword)) {
        $error = 'New password does not meet the requirements.
                  Password must be at least 8 characters including
                  - special symbol
                  - number
                  - uppercase letter';
    } else {
        if (password_verify($currentPassword, $hashedPassword)) {
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            if ($updateStmt === false) {
                die("Prepare failed: " . htmlspecialchars($conn->error));
            }

            $updateStmt->bind_param('si', $newHashedPassword, $user_id);

            if ($updateStmt->execute()) {
                $success = 'Your password has been updated successfully!';
            } else {
                $error = 'Failed to update the password. Please try again later.';
            }

            $updateStmt->close();
        } else {
            $error = 'Current password is incorrect.';
        }
    }
}
?>







<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="robots" content="noindex,nofollow" />
  <title>Edit Profile</title>
  <link rel="canonical" href="https://www.wrappixel.com/templates/xtreme-admin-lite/" />
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />

  <!-- Local CSS -->
  <link href="../dist/css/style.css" rel="stylesheet" />

  <!-- CDN Links for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.6.1/css/flag-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.10/css/weather-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/themify-icons/0.1.2/css/themify-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/crypto-icons/1.1.0/cryptocoins.min.css">

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
      background-color: #007bff;
      border: none;
      border-radius: 4px;
      width: 180px;
      color: #ffffff;
      font-size: 16px;
      font-weight: bold;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      text-decoration: none;
    }

    .btn-primary:active {
      background-color: #004494;
      transform: translateY(1px);
    }

    .btn-primary:focus {
      outline: none;
      box-shadow: 0 0 0 2px rgba(38, 143, 255, 0.5);
    }
  </style>
  
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="../service/style.css">
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
  <!-- Main wrapper - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
    data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar" data-navbarbg="skin5">
      <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin5">
          <!-- ============================================================== -->
          <!-- Logo -->
          <!-- ============================================================== -->
          <a class="navbar-brand" href="staff_page.php">
            <!-- Logo icon -->
            <b class="logo-icon">
              <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
              <!-- Dark Logo icon -->
              <img src="../../assets/images/logo.png" alt="homepage" style="width: 62px !important; height: auto !important;"/>
            </b>
            <!--End Logo icon -->
            <!-- Logo text -->
          </a>
          <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
              class="ti-menu ti-close"></i></a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
          <!-- ============================================================== -->
          <!-- toggle and nav items -->
          <!-- ============================================================== -->
          <ul class="navbar-nav float-start me-auto">
            <!-- ============================================================== -->
            <!-- Search -->
            <!-- ============================================================== -->
            <li class="nav-item search-box">
              <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i
                  class="mdi mdi-magnify fs-4"></i></a>
              <form class="app-search position-absolute">
                <input type="text" class="form-control" placeholder="Search &amp; enter" />
                <a class="srh-btn"><i class="mdi mdi-close"></i></a>
              </form>
            </li>
          </ul>
          <!-- ============================================================== -->
          <!-- Right side toggle and nav items -->
          <!-- ============================================================== -->
          <ul class="navbar-nav float-end">
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown">
              <a class="
                    nav-link
                    dropdown-toggle
                    text-muted
                    waves-effect waves-dark
                    pro-pic
                  " href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31" />
              </a>
              <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-account m-r-5 m-l-5"></i> My
                  Profile</a>
                <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-wallet m-r-5 m-l-5"></i> My
                  Balance</a>
                <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-email m-r-5 m-l-5"></i> Inbox</a>
              </ul>
            </li>
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
          </ul>
        </div>
      </nav>
    </header>
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    
    <?php include "../sidebar/sidebarStaff.php";?>
    <div class="page-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-4">
            <div class="profile-sidebar">
              
              <div class="profile-usertitle">
                <div class="profile-usertitle-name">
                  <?php echo htmlspecialchars($name); ?>
                </div>
                <div class="profile-usertitle-job">
                  <?php echo htmlspecialchars($usernames); ?>
                </div>
              </div>
              <div class="profile-userbuttons">
                <a href="change_passwordStaff.php" class="btn btn-primary">Change Password</a>
              </div>
              <div class="profile-usermenu">
                <ul>
                  <!--<li>
                    <a href="profile.html">
                      <i class="mdi mdi-account"></i> Profile
                    </a>
                  </li>
                  <li>
                    <a href="settings.html">
                      <i class="mdi mdi-settings"></i> Settings
                    </a>
                  </li>-->
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="profile-content">
              <h3>Change Password</h3>
              <?php
              if (isset($success)) {
                  echo "<div class='alert alert-success'>$success</div>";
              }
              if (isset($error)) {
                  echo "<div class='alert alert-danger'>$error</div>";
              }
              ?>
              <form action="change_passwordStaff.php" method="POST">
                        <div id="requirementsMessage"></div>
                        <div class="mb-3">
                            <label class="small mb-1" for="currentPassword">Current Password</label>
                            <input class="form-control" id="currentPassword" name="currentPassword" type="password" placeholder="Enter current password" required>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="newPassword">New Password</label>
                            <input class="form-control" id="newPassword" name="newPassword" type="password" placeholder="Enter new password" required>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="confirmPassword">Confirm Password</label>
                            <input class="form-control" id="confirmPassword" name="confirmPassword" type="password" placeholder="Confirm new password" required>
                        </div>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        © 2024 Xtreme Admin by Wrappixel
      </footer>
    </div>
  </div>

  <!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Local JS -->
  <script src="../dist/js/app-style-switcher.js"></script>
  <script src="../dist/js/custom.js"></script>
  <script src="../dist/js/sidebarmen.js"></script>
  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const newPasswordInput = document.getElementById('newPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const requirementsMessage = document.getElementById('requirementsMessage');

        // Function to validate password
        function validatePassword(password) {
            const minLength = /.{8,}/;
            const upperCase = /[A-Z]/;
            const number = /\d/;
            const specialChar = /[!@#$%^&*(),.?":{}|<>]/;

            return minLength.test(password) && upperCase.test(password) && number.test(password) && specialChar.test(password);
        }

        form.addEventListener('submit', function(event) {
            let newPassword = newPasswordInput.value;
            let confirmPassword = confirmPasswordInput.value;

            if (!validatePassword(newPassword)) {
                requirementsMessage.innerText = 'Password must be at least 8 characters long, include at least one uppercase letter, one number, and one special character.';
                event.preventDefault(); 
            } else if (newPassword !== confirmPassword) {
                requirementsMessage.innerText = 'New passwords do not match.';
                event.preventDefault(); 
            }
        });
    });
</script>
</body>

</html>
