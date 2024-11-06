<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

require '../../connection.php';

$user_id = $_SESSION['user_id'];

$adminEmail = '';
$profile_pic = '';

$sql = "SELECT email, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $adminEmail = $row['email'];
    $profile_pic = $row['profile_pic'];
} else {
    $adminEmail = "Email not found";
}

$stmt->close();
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
  <meta name="description"
    content="Xtreme Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework" />
  <meta name="robots" content="noindex,nofollow" />
  <title>Admin</title>
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
          <a class="navbar-brand" href="admin_page.html">
            <!-- Logo icon -->
            <b class="logo-icon">
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
                <a class="dropdown-item" href="pages_profile.php"><i class="mdi mdi-account m-r-5 m-l-5"></i> My
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
    <?php include "../sidebar/sidebarAdmin.php";?>
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
                <!--    <h5 class="card-subtitle">Overview of Latest Month</h5> -->
                  </div>
                <!--  <div class="ms-auto d-flex no-block align-items-center">
                    <ul class="list-inline font-12 dl m-r-15 m-b-0">
                      <li class="list-inline-item text-info">
                        <i class="mdi mdi-checkbox-blank-circle"></i> Iphone
                      </li>
                      <li class="list-inline-item text-primary">
                        <i class="mdi mdi-checkbox-blank-circle"></i> Ipad
                      </li>
                    </ul>
                  </div> -->
                </div>
                 <!--<div class="row">
                  // column 
                  <div class="col-lg-12">
                    <div class="campaign ct-charts"></div>
                  </div>
                  // column 
                </div> -->
              </div>
            </div>
          </div>
        <!--  <div class="col-md-4">
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
          </div> -->
        </div>
        <!-- ============================================================== -->
        <!-- Sales chart -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Container fluid  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- footer -->
      <!-- ============================================================== -->

      <!-- new report data -->

      <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Sales Report</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <h4 style="padding-left: 100px;padding-top: 20px;">Sales Report</h4>
    </div>
</nav>

<div class="container-fluid">
    <div class="col-sm-8">
        <div class="row">
            <div class="col-xs-12">
                <h3 style="padding-left: 100px;">Sales Report between Two Dates</h3>
                <hr>
                <form name="bwdatesdata" action="" method="post">
                    <table width="100%" height="117" border="0">
                        <tr>
                            <th width="27%" height="63" scope="row">From Date :</th>
                            <td width="73%">
                                <input type="date" name="fdate" class="form-control" id="fdate" required>
                            </td>
                        </tr>
                        <tr>
                            <th width="27%" height="63" scope="row">To Date :</th>
                            <td width="73%">
                                <input type="date" name="tdate" class="form-control" id="tdate" required>
                            </td>
                        </tr>
                        <tr>
                            <th width="27%" height="63" scope="row">Request Type :</th>
                            <td width="73%">
                                <input type="radio" name="requesttype" value="mtwise" checked="true"> Month wise
                                <input type="radio" name="requesttype" value="yrwise"> Year wise
                            </td>
                        </tr>
                        <tr>
                            <th width="27%" height="63" scope="row"></th>
                            <td width="73%">
                                <button class="btn-primary btn" type="submit" name="submit">Submit</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-12">
                <?php
                if (isset($_POST['submit'])) {
                    $fdate = $_POST['fdate'];
                    $tdate = $_POST['tdate'];
                    $rtype = $_POST['requesttype'];

                    if ($rtype == 'mtwise') {
                        $month1 = strtotime($fdate);
                        $month2 = strtotime($tdate);
                        $m1 = date("F", $month1);
                        $m2 = date("F", $month2);
                        $y1 = date("Y", $month1);
                        $y2 = date("Y", $month2);
                        ?>
                        <h4 class="header-title m-t-0 m-b-30">Sales Report Month Wise</h4>
                        <h4 align="center" style="color:blue">Sales Report from <?php echo $m1 . "-" . $y1; ?> to <?php echo $m2 . "-" . $y2; ?></h4>
                        <hr>
                        <div class="row">
                            <table class="table table-bordered" width="100%" border="0">
                                <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Month / Year</th>
                                    <th>Sales</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "
                                    SELECT 
                                        MONTH(pickup_appointment) AS month,
                                        YEAR(pickup_appointment) AS year,
                                        SUM(total_order_price) AS total_order_price
                                    FROM 
                                        orders
                                    WHERE 
                                        DATE(pickup_appointment) BETWEEN '$fdate' AND '$tdate' 
                                    GROUP BY 
                                        month, year
                                    UNION ALL
                                    SELECT 
                                        MONTH(delivery_time) AS month,
                                        YEAR(delivery_time) AS year,
                                        SUM(total_order_price) AS total_order_price
                                    FROM 
                                        orders
                                    WHERE 
                                        DATE(delivery_time) BETWEEN '$fdate' AND '$tdate' 
                                    GROUP BY 
                                        month, year
                                ";

                                $ret = mysqli_query($conn, $query);
                                if (!$ret) {
                                    die("SQL Query Failed: " . mysqli_error($con)); // Display the SQL error message
                                }

                                $num = mysqli_num_rows($ret);
                                if ($num > 0) {
                                    $cnt = 1;
                                    $ftotal = 0; // Initialize total for final sum
                                    $salesData = [];
                                    while ($row = mysqli_fetch_array($ret)) {
                                        $key = $row['month'] . "/" . $row['year'];
                                        if (!isset($salesData[$key])) {
                                            $salesData[$key] = 0;
                                        }
                                        $salesData[$key] += $row['total_order_price'];
                                    }

                                    foreach ($salesData as $key => $total) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $key; ?></td>
                                            <td><?php echo $total; ?></td>
                                        </tr>
                                        <?php
                                        $ftotal += $total;
                                        $cnt++;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="2" align="center">Total</td>
                                        <td><?php echo $ftotal; ?></td>
                                    </tr>
                                    <?php
                                } else {
                                    echo "<tr><td colspan='3' align='center'>No records found.</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    } else {
                        $year1 = strtotime($fdate);
                        $year2 = strtotime($tdate);
                        $y1 = date("Y", $year1);
                        $y2 = date("Y", $year2);
                        ?>
                        <h4 class="header-title m-t-0 m-b-30">Sales Report Year Wise</h4>
                        <h4 align="center" style="color:blue">Sales Report from <?php echo $y1; ?> to <?php echo $y2; ?></h4>
                        <hr>
                        <div class="row">
                            <table class="table table-bordered" width="100%" border="0">
                                <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Year</th>
                                    <th>Sales</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "
                                    SELECT 
                                        YEAR(pickup_appointment) AS year,
                                        SUM(total_order_price) AS total_order_price
                                    FROM 
                                        orders
                                    WHERE 
                                        DATE(pickup_appointment) BETWEEN '$fdate' AND '$tdate' 
                                    GROUP BY 
                                        year
                                    UNION ALL
                                    SELECT 
                                        YEAR(delivery_time) AS year,
                                        SUM(total_order_price) AS total_order_price
                                    FROM 
                                        orders
                                    WHERE 
                                        DATE(delivery_time) BETWEEN '$fdate' AND '$tdate' 
                                    GROUP BY 
                                        year
                                ";

                                $ret = mysqli_query($conn, $query);
                                if (!$ret) {
                                    die("SQL Query Failed: " . mysqli_error($con)); // Display the SQL error message
                                }

                                $num = mysqli_num_rows($ret);
                                if ($num > 0) {
                                    $cnt = 1;
                                    $ftotal = 0; // Initialize total for final sum
                                    $salesData = [];
                                    while ($row = mysqli_fetch_array($ret)) {
                                        $year = $row['year'];
                                        if (!isset($salesData[$year])) {
                                            $salesData[$year] = 0;
                                        }
                                        $salesData[$year] += $row['total_order_price'];
                                    }

                                    foreach ($salesData as $year => $total) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $year; ?></td>
                                            <td><?php echo $total; ?></td>
                                        </tr>
                                        <?php
                                        $ftotal += $total;
                                        $cnt++;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="2" align="center">Total</td>
                                        <td><?php echo $ftotal; ?></td>
                                    </tr>
                                    <?php
                                } else {
                                    echo "<tr><td colspan='3' align='center'>No records found.</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- script references -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>



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