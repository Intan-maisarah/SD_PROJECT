<?php
// Start session to access session variables
session_start();

// Check if the user is logged in by checking if the session variable is set
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Include the database connection
require '../../connection.php';

// Fetch the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Initialize the $staffEmail variable
$staffEmail = '';
$profile_pic = '';

// Prepare and execute a query to get the user's email
$sql = "SELECT email, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the result and set the email
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $staffEmail = $row['email'];
    $profile_pic = $row['profile_pic'];
} else {
    $staffEmail = "Email not found";
}

// Close the statement and connection
$stmt->close();

$profilePicPath = !empty($profile_pic) ? htmlspecialchars($profile_pic) : '../assets/profile_pic/default-placeholder.png';

$conn->close();
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords"
    content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Xtreme lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Xtreme admin lite design, Xtreme admin lite dashboard bootstrap 5 dashboard template" />
  
  <meta name="robots" content="noindex,nofollow" />
  <title>Staff Profile</title>
  <link rel="canonical" href="https://www.wrappixel.com/templates/xtreme-admin-lite/" />
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />
  <!-- Custom CSS -->
  <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet" />
  <!-- Custom CSS -->
  <link href="../dist/css/style.min.css" rel="stylesheet" />
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
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
          <a class="navbar-brand" href="staff_page.html">
            <!-- Logo icon -->
            <b class="logo-icon">
              <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
              <!-- Dark Logo icon -->
              <img src="../../assets/images/logo.png" alt="homepage" style="width: 60px; height: auto;"/>
             
            </b>
            <!--End Logo icon -->
            <!-- Logo text -->
          </a>
          <!-- ============================================================== -->
          <!-- End Logo -->
          <!-- ============================================================== -->
          <!-- This is for the sidebar toggle which is visible on mobile only -->
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
                <a class="dropdown-item" href="staff_profile.php"><i class="mdi mdi-account m-r-5 m-l-5"></i> My
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

    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
      <!-- ============================================================== -->
      <!-- Bread crumb and right sidebar toggle -->
      <!-- ============================================================== -->
      <div class="page-breadcrumb">
        <div class="row align-items-center">
          <div class="col-5">
            <h4 class="page-title">Dashboard</h4>
            <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">
                    Library
                  </li>
                </ol>
              </nav>
            </div>
          </div>
            </div>
          </div>
        </div>
      </div>
      <!-- ============================================================== -->
      <!-- End Bread crumb and right sidebar toggle -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Container fluid  -->
      <!-- ============================================================== -->
      <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Sales chart -->
        <!-- ============================================================== -->
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-body">
                <div class="d-md-flex align-items-center">
                  <div>
                    <h4 class="card-title">Sales Summary</h4>
                    <h5 class="card-subtitle">Overview of Latest Month</h5>
                  </div>
                  <div class="ms-auto d-flex no-block align-items-center">
                    <ul class="list-inline font-12 dl m-r-15 m-b-0">
                      <li class="list-inline-item text-info">
                        <i class="mdi mdi-checkbox-blank-circle"></i> Iphone
                      </li>
                      <li class="list-inline-item text-primary">
                        <i class="mdi mdi-checkbox-blank-circle"></i> Ipad
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="row">
                  <!-- column -->
                  <div class="col-lg-12">
                    <div class="campaign ct-charts"></div>
                  </div>
                  <!-- column -->
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Feeds</h4>
                <div class="feed-widget">
                  <ul class="list-style-none feed-body m-0 p-b-20">
                    <li class="feed-item">
                      <div class="feed-icon bg-info">
                        <i class="mdi mdi-bell fs-4"></i>
                      </div>
                      You have 4 pending tasks.
                      <span class="ms-auto font-12 text-muted">Just Now</span>
                    </li>
                    <li class="feed-item">
                      <div class="feed-icon bg-success">
                        <i class="mdi mdi-server fs-4"></i>
                      </div>
                      Server #1 overloaded.<span class="ms-auto font-12 text-muted">2 Hours ago</span>
                    </li>
                    <li class="feed-item">
                      <div class="feed-icon bg-warning">
                        <i class="mdi mdi-cart fs-4"></i>
                      </div>
                      New order received.<span class="ms-auto font-12 text-muted">31 May</span>
                    </li>
                    <li class="feed-item">
                      <div class="feed-icon bg-danger">
                        <i class="mdi mdi-account fs-4"></i>
                      </div>
                      New user registered.<span class="ms-auto font-12 text-muted">30 May</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- Sales chart -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Table -->
        <!-- ============================================================== -->
        <div class="row">
          <!-- column -->
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <!-- title -->
                <div class="d-md-flex">
                  <div>
                    <h4 class="card-title">Top Selling Products</h4>
                    <h5 class="card-subtitle">
                      Overview of Top Selling Items
                    </h5>
                  </div>
                  <div class="ms-auto">
                    <div class="dl">
                      <select class="form-select shadow-none">
                        <option value="0" selected>Monthly</option>
                        <option value="1">Daily</option>
                        <option value="2">Weekly</option>
                        <option value="3">Yearly</option>
                      </select>
                    </div>
                  </div>
                </div>
                <!-- title -->
              </div>
              <div class="table-responsive">
                <table class="table v-middle">
                  <thead>
                    <tr class="bg-light">
                      <th class="border-top-0">Products</th>
                      <th class="border-top-0">License</th>
                      <th class="border-top-0">Support Agent</th>
                      <th class="border-top-0">Technology</th>
                      <th class="border-top-0">Tickets</th>
                      <th class="border-top-0">Sales</th>
                      <th class="border-top-0">Earnings</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="m-r-10">
                            <a class="
                                  btn btn-circle
                                  d-flex
                                  btn-info
                                  text-white
                                ">EA</a>
                          </div>
                          <div class="">
                            <h4 class="m-b-0 font-16">Elite Admin</h4>
                          </div>
                        </div>
                      </td>
                      <td>Single Use</td>
                      <td>John Doe</td>
                      <td>
                        <label class="label label-danger">Angular</label>
                      </td>
                      <td>46</td>
                      <td>356</td>
                      <td>
                        <h5 class="m-b-0">$2850.06</h5>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="m-r-10">
                            <a class="
                                  btn btn-circle
                                  d-flex
                                  btn-orange
                                  text-white
                                ">MA</a>
                          </div>
                          <div class="">
                            <h4 class="m-b-0 font-16">Monster Admin</h4>
                          </div>
                        </div>
                      </td>
                      <td>Single Use</td>
                      <td>Venessa Fern</td>
                      <td>
                        <label class="label label-info">Vue Js</label>
                      </td>
                      <td>46</td>
                      <td>356</td>
                      <td>
                        <h5 class="m-b-0">$2850.06</h5>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="m-r-10">
                            <a class="
                                  btn btn-circle
                                  d-flex
                                  btn-success
                                  text-white
                                ">MP</a>
                          </div>
                          <div class="">
                            <h4 class="m-b-0 font-16">Material Pro Admin</h4>
                          </div>
                        </div>
                      </td>
                      <td>Single Use</td>
                      <td>John Doe</td>
                      <td>
                        <label class="label label-success">Bootstrap</label>
                      </td>
                      <td>46</td>
                      <td>356</td>
                      <td>
                        <h5 class="m-b-0">$2850.06</h5>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="m-r-10">
                            <a class="
                                  btn btn-circle
                                  d-flex
                                  btn-purple
                                  text-white
                                ">AA</a>
                          </div>
                          <div class="">
                            <h4 class="m-b-0 font-16">Ample Admin</h4>
                          </div>
                        </div>
                      </td>
                      <td>Single Use</td>
                      <td>John Doe</td>
                      <td>
                        <label class="label label-purple">React</label>
                      </td>
                      <td>46</td>
                      <td>356</td>
                      <td>
                        <h5 class="m-b-0">$2850.06</h5>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- Table -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Recent comment and chats -->
        <!-- ============================================================== -->
        <div class="row">
          <!-- column -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Recent Comments</h4>
              </div>
              <div class="comment-widgets scrollable">
                <!-- Comment Row -->
                <div class="d-flex flex-row comment-row m-t-0">
                  <div class="p-2">
                    <img src="../assets/images/users/1.jpg" alt="user" width="50" class="rounded-circle" />
                  </div>
                  <div class="comment-text w-100">
                    <h6 class="font-medium">James Anderson</h6>
                    <span class="m-b-15 d-block">Lorem Ipsum is simply dummy text of the printing and
                      type setting industry.
                    </span>
                    <div class="comment-footer">
                      <span class="text-muted float-end">April 14, 2024</span>
                      <span class="label label-rounded label-primary">Pending</span>
                      <span class="action-icons">
                        <a href="javascript:void(0)"><i class="mdi mdi-pencil-box-outline fs-4"></i></a>
                        <a href="javascript:void(0)"><i class="mdi mdi-check fs-4"></i></a>
                        <a href="javascript:void(0)"><i class="mdi mdi-heart-outline fs-4"></i></a>
                      </span>
                    </div>
                  </div>
                </div>
                <!-- Comment Row -->
                <div class="d-flex flex-row comment-row">
                  <div class="p-2">
                    <img src="../assets/images/users/4.jpg" alt="user" width="50" class="rounded-circle" />
                  </div>
                  <div class="comment-text active w-100">
                    <h6 class="font-medium">Michael Jorden</h6>
                    <span class="m-b-15 d-block">Lorem Ipsum is simply dummy text of the printing and
                      type setting industry.
                    </span>
                    <div class="comment-footer">
                      <span class="text-muted float-end">April 14, 2024</span>
                      <span class="label label-success label-rounded">Approved</span>
                      <span class="action-icons active">
                        <a href="javascript:void(0)"><i class="mdi mdi-pencil-box-outline fs-4"></i></a>
                        <a href="javascript:void(0)"><i class="mdi mdi-window-close fs-4"></i></a>
                        <a href="javascript:void(0)"><i class="mdi mdi-heart-outline fs-4 text-danger"></i></a>
                      </span>
                    </div>
                  </div>
                </div>
                <!-- Comment Row -->
                <div class="d-flex flex-row comment-row">
                  <div class="p-2">
                    <img src="../assets/images/users/5.jpg" alt="user" width="50" class="rounded-circle" />
                  </div>
                  <div class="comment-text w-100">
                    <h6 class="font-medium">Johnathan Doeting</h6>
                    <span class="m-b-15 d-block">Lorem Ipsum is simply dummy text of the printing and
                      type setting industry.
                    </span>
                    <div class="comment-footer">
                      <span class="text-muted float-end">April 14, 2024</span>
                      <span class="label label-rounded label-danger">Rejected</span>
                      <span class="action-icons">
                        <a href="javascript:void(0)"><i class="mdi mdi-pencil-box-outline fs-4"></i></a>
                        <a href="javascript:void(0)"><i class="mdi mdi-check fs-4"></i></a>
                        <a href="javascript:void(0)"><i class="mdi mdi-heart-outline fs-4"></i></a>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- column -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Temp Guide</h4>
                <div class="d-flex align-items-center flex-row m-t-30">
                  <div class="display-5 text-info">
                    <i class="mdi mdi-weather-lightning-rainy"></i>
                    <span>73<sup>°</sup></span>
                  </div>
                  <div class="m-l-10">
                    <h3 class="m-b-0">Saturday</h3>
                    <small>Ahmedabad, India</small>
                  </div>
                </div>
                <table class="table no-border mini-table m-t-20">
                  <tbody>
                    <tr>
                      <td class="text-muted">Wind</td>
                      <td class="font-medium">ESE 17 mph</td>
                    </tr>
                    <tr>
                      <td class="text-muted">Humidity</td>
                      <td class="font-medium">83%</td>
                    </tr>
                    <tr>
                      <td class="text-muted">Pressure</td>
                      <td class="font-medium">28.56 in</td>
                    </tr>
                    <tr>
                      <td class="text-muted">Cloud Cover</td>
                      <td class="font-medium">78%</td>
                    </tr>
                  </tbody>
                </table>
                <ul class="row list-style-none text-center m-t-30">
                  <li class="col-3">
                    <h4 class="text-info">
                      <i class="mdi mdi-weather-sunny fs-3"></i>
                    </h4>
                    <span class="d-block text-muted">09:30</span>
                    <h3 class="m-t-5">70<sup>°</sup></h3>
                  </li>
                  <li class="col-3">
                    <h4 class="text-info">
                      <i class="mdi mdi-weather-partlycloudy fs-3"></i>
                    </h4>
                    <span class="d-block text-muted">11:30</span>
                    <h3 class="m-t-5">72<sup>°</sup></h3>
                  </li>
                  <li class="col-3">
                    <h4 class="text-info">
                      <i class="mdi mdi-weather-pouring fs-3"></i>
                    </h4>
                    <span class="d-block text-muted">13:30</span>
                    <h3 class="m-t-5">75<sup>°</sup></h3>
                  </li>
                  <li class="col-3">
                    <h4 class="text-info">
                      <i class="mdi mdi-weather-hail fs-3"></i>
                    </h4>
                    <span class="d-block text-muted">15:30</span>
                    <h3 class="m-t-5">76<sup>°</sup></h3>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- Recent comment and chats -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Container fluid  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- footer -->
      <!-- ============================================================== -->
      <footer class="footer text-center">
        All Rights Reserved by Xtreme Admin. Designed and Developed by
        <a href="https://www.wrappixel.com">WrapPixel</a>.
      </footer>
      <!-- ============================================================== -->
      <!-- End footer -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
  </div>
  <!-- ============================================================== -->
  <!-- End Wrapper -->
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- All Jquery -->
  <!-- ============================================================== -->
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