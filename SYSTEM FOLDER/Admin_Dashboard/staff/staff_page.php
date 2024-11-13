<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
    exit('User not logged in.');
}

require '../../connection.php';

$user_id = $_SESSION['user_id'];

$adminEmail = '';
$profile_pic = '';
$sql = 'SELECT email, profile_pic FROM users WHERE id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $adminEmail = $row['email'];
    $profile_pic = $row['profile_pic'];
} else {
    $adminEmail = 'Email not found';
}

// Fetch notification data
$pendingOrders = 0;
$newUsers = 0;
$totalSales = 0;

$sqlPending = "SELECT COUNT(*) AS count FROM orders WHERE status = 'pending'";
if ($resultPending = $conn->query($sqlPending)) {
    $pendingOrders = $resultPending->fetch_assoc()['count'];
} else {
    // Handle query error
    exit('Error executing query: '.$conn->error);
}

$sqlNewUsers = 'SELECT COUNT(*) AS count FROM users';
if ($resultNewUsers = $conn->query($sqlNewUsers)) {
    $newUsers = $resultNewUsers->fetch_assoc()['count'];
} else {
    // Handle query error
    exit('Error executing query: '.$conn->error);
}

$sqlSales = 'SELECT SUM(total_order_price) AS total FROM orders WHERE DATE(created_at) = CURDATE()';
if ($resultSales = $conn->query($sqlSales)) {
    $totalSales = $resultSales->fetch_assoc()['total'];
} else {
    // Handle query error
    exit('Error executing query: '.$conn->error);
}

$sqlStatus = 'SELECT status, COUNT(*) AS count FROM orders GROUP BY status';
if ($resultStatus = $conn->query($sqlStatus)) {
    $orderStatus = ['completed' => 0, 'in_progress' => 0, 'pending' => 0];
    while ($row = $resultStatus->fetch_assoc()) {
        if (array_key_exists($row['status'], $orderStatus)) {
            $orderStatus[$row['status']] = $row['count'];
        }
    }
} else {
    // Handle query error
    exit('Error executing query: '.$conn->error);
}

// Notifications array
$notifications = [
    ['message' => "$pendingOrders pending orders", 'time' => 'Check orders section', 'icon' => 'bell', 'color' => 'blue'],
    ['message' => "$newUsers total registered users", 'time' => 'Check user management', 'icon' => 'user', 'color' => 'red'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="robots" content="noindex,nofollow" />
  <title>Staff Dashboard</title>
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />
  <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet" />
  <link href="../dist/css/style.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../service/style.css">
  <style>
    /* Styling for cards */
    .card {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .dashboard-value {
      font-size: 24px;
      font-weight: bold;
      color: #4CAF50;
    }

    .chart-container {
      height: 200px;
    }
  </style>
</head>

<body>
  <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
    <!-- Header and Sidebar -->
    <?php include '../sidebar/header.php'; ?>
    <?php include '../sidebar/sidebarStaff.php'; ?>

   <!-- Page Wrapper -->
<div class="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <h4>Notifications</h4>
          <ul class="list-unstyled">
            <?php foreach ($notifications as $notification) { ?>
              <li style="border-left: 4px solid <?php echo $notification['color']; ?>; padding: 10px;">
                <i class="icon-<?php echo $notification['icon']; ?>"></i> 
                <?php echo $notification['message']; ?> 
                <span class="text-muted" style="font-size: 12px;"><?php echo $notification['time']; ?></span>
              </li>
            <?php } ?>
          </ul>
        </div>
        <div class="card">
          <h4>Today's Sales</h4>
          <p class="dashboard-value">RM <?php echo number_format($totalSales ?? 0, 2); ?></p>
          </div>
      </div>

      <!-- Main Content Section -->
      <div class="col-md-8">
        <h2>Dashboard</h2>

        <div class="row">
          <!-- Total Orders -->
          <div class="col-md-6">
            <div class="card">
              <h5>Pending Orders</h5>
              <p class="dashboard-value"><?php echo $pendingOrders; ?> Orders</p>
              <div class="chart-container">
                <canvas id="orders-chart"></canvas>
              </div>
            </div>
          </div>

          <!-- Order Status Breakdown -->
          <div class="col-md-6">
            <div class="card">
              <h5>Order Status Breakdown</h5>
              <div class="chart-container">
                <canvas id="status-breakdown-chart"></canvas>
              </div>
            </div>
          </div>
</div>
</div>
        <!--sales-->
        <div class="col-md-8" >
                <h3 style="padding-left: 100px;">Sales Report</h3>
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
        <hr>
            <div class="col-md-8">
                <?php
require '../../connection.php';

if (isset($_POST['submit'])) {
    $fdate = $_POST['fdate'];
    $tdate = $_POST['tdate'];
    $fromDate = new DateTime($fdate);
    $toDate = new DateTime($tdate);
    $rtype = $_POST['requesttype'];

    if ($rtype == 'mtwise') {
        $month1 = strtotime($fdate);
        $month2 = strtotime($tdate);
        $m1 = $fromDate->format('F');
        $y1 = $fromDate->format('Y');

        $m2 = $toDate->format('F');
        $y2 = $toDate->format('Y');

        if ($m1 === $m2 && $y1 === $y2) {
            echo "<h4 align='center' style='color:blue'>Sales Report for $m1-$y1</h4>";
        } else {
            echo "<h4 align='center' style='color:blue'>Sales Report from $m1-$y1 to $m2-$y2</h4>";
        }

        ?>
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
                     MONTH(created_at) AS month,
                     YEAR(created_at) AS year,
                     SUM(total_order_price) AS total_order_price
                 FROM 
                     orders
                 WHERE 
                     DATE(created_at) BETWEEN '$fdate' AND '$tdate' 
                 GROUP BY 
                     month, year
             ";

        $ret = mysqli_query($conn, $query);
        if (!$ret) {
            exit('SQL Query Failed: '.mysqli_error($conn));
        }

        $num = mysqli_num_rows($ret);
        if ($num > 0) {
            $cnt = 1;
            $ftotal = 0;
            while ($row = mysqli_fetch_array($ret)) {
                $monthYear = date('F', mktime(0, 0, 0, $row['month'], 10)).'/'.$row['year'];
                echo "<tr>
                        <td>{$cnt}</td>
                        <td>{$monthYear}</td>
                        <td>{$row['total_order_price']}</td>
                    </tr>";
                $ftotal += $row['total_order_price'];
                ++$cnt;
            }

            echo "<tr>
                    <td colspan='2' align='center'>Total</td>
                    <td>{$ftotal}</td>
                </tr>";
        } else {
            echo "<tr><td colspan='3' align='center'>No records found.</td></tr>";
        }

        echo '</tbody></table></div>';
    } else { // Year-wise Report
        $year1 = strtotime($fdate);
        $year2 = strtotime($tdate);
        $y1 = date('Y', $year1);
        $y2 = date('Y', $year2);

        echo "<h4 class='header-title m-t-0 m-b-30'>Sales Report Year Wise</h4>";
        echo "<h4 align='center' style='color:blue'>Sales Report from $y1 to $y2</h4>";
        echo '<hr>';
        echo "<div class='row'>";
        echo "<table class='table table-bordered' width='100%' border='0'>
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Year</th>
                        <th>Sales</th>
                    </tr>
                </thead>
                <tbody>";
        $query = "
                SELECT 
                    YEAR(created_at) AS year,
                    SUM(total_order_price) AS total_order_price
                FROM 
                    orders
                WHERE 
                    DATE(created_at) BETWEEN '$fdate' AND '$tdate' 
                GROUP BY 
                    year
            ";

        $ret = mysqli_query($conn, $query);
        if (!$ret) {
            exit('SQL Query Failed: '.mysqli_error($conn));
        }

        $num = mysqli_num_rows($ret);
        if ($num > 0) {
            $cnt = 1;
            $ftotal = 0;
            while ($row = mysqli_fetch_array($ret)) {
                echo "<tr>
                        <td>{$cnt}</td>
                        <td>{$row['year']}</td>
                        <td>{$row['total_order_price']}</td>
                    </tr>";
                $ftotal += $row['total_order_price'];
                ++$cnt;
            }

            echo "<tr>
                    <td colspan='2' align='center'>Total</td>
                    <td>{$ftotal}</td>
                </tr>";
        } else {
            echo "<tr><td colspan='3' align='center'>No records found.</td></tr>";
        }

        echo '</tbody></table></div>';
    }
}
?>
    </div>
</div>
    <!-- Footer -->
    <footer class="footer text-center">
      All Rights Reserved by Xtreme Admin. Designed and Developed by <a href="https://www.wrappixel.com">WrapPixel</a>.
    </footer>
  </div>

  <!-- JS Scripts -->
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../dist/js/app-style-switcher.js"></script>
  <script src="../dist/js/waves.js"></script>
  <script src="../dist/js/sidebarmenu.js"></script>
  <script src="../dist/js/custom.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Chart.js Initialization -->
  <script>
    // Orders per Week Chart (Dummy data example for demo purposes)
    var ctxOrders = document.getElementById('orders-chart').getContext('2d');
    var ordersChart = new Chart(ctxOrders, {
      type: 'line',
      data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
          label: 'Pending Orders',
          data: [<?php echo $pendingOrders; ?>, 5, 3, 4], // Replace with dynamic data as needed
          borderColor: 'rgba(75, 192, 192, 1)',
          fill: false,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          tooltip: {
            enabled: true
          }
        }
      }
    });

    var ctxBreakdown = document.getElementById('status-breakdown-chart').getContext('2d');
var breakdownChart = new Chart(ctxBreakdown, {
  type: 'pie',
  data: {
    labels: ['Completed', 'In Progress', 'Pending'],
    datasets: [{
      label: 'Order Status',
      data: [<?php echo $orderStatus['completed']; ?>, <?php echo $orderStatus['in_progress']; ?>, <?php echo $orderStatus['pending']; ?>],
      backgroundColor: ['#4CAF50', '#FFEB3B', '#F44336'],
    }]
  },
  options: {
    responsive: true,
    plugins: {
      tooltip: {
        enabled: true
      }
    }
  }
});
  </script>
</body>
</html>
