<?php
error_reporting(0);
require_once("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>PHP GURUKUL | DEMO</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">  	
	</head>
	<body>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="navbar-header">
<h4 style="padding-left: 100px;padding-top: 20px;">PHP GURUKUL | Programming Blog</h4>
	</div>
</nav>
<div class="container-fluid">
  <!--center-->
  <div class="col-sm-8">
    <div class="row">
      <div class="col-xs-12">
        <h3 style="padding-left: 100px;">How to get sales report from database between two dates in php and MySQL</h3>
		<hr >
		<form name="bwdatesdata" action="" method="post" action="">
  <table width="100%" height="117"  border="0">
<tr>
    <th width="27%" height="63" scope="row">From Date :</th>
    <td width="73%">
<input type="date" name="fdate" class="form-control" id="fdate">
    	</td>
  </tr>

  <tr>
    <th width="27%" height="63" scope="row">To Date :</th>
    <td width="73%">
    	<input type="date" name="tdate" class="form-control" id="tdate"></td>
  </tr>
  <tr>
    <th width="27%" height="63" scope="row">Request Type :</th>
    <td width="73%">
         <input type="radio" name="requesttype" value="mtwise" checked="true">Month wise
          <input type="radio" name="requesttype" value="yrwise">Year wise</td>
  </tr>
<tr>
    <th width="27%" height="63" scope="row"></th>
    <td width="73%">
    	<button class="btn-primary btn" type="submit" name="submit">Submit</button>
  </tr>
 
</table>
     </form>
 
      </div>
    </div>
    <hr>
      <div class="row">
      <div class="col-xs-12">
      	 <?php
      	 if(isset($_POST['submit']))
{ 
$fdate=$_POST['fdate'];
$tdate=$_POST['tdate'];
$rtype=$_POST['requesttype'];

?>
<?php if($rtype=='mtwise'){
$month1=strtotime($fdate);
$month2=strtotime($tdate);
$m1=date("F",$month1);
$m2=date("F",$month2);
$y1=date("Y",$month1);
$y2=date("Y",$month2);
    ?>
        <h4 class="header-title m-t-0 m-b-30">Sales Report Month Wise</h4>
<h4 align="center" style="color:blue">Sales Report  from <?php echo $m1."-".$y1;?> to <?php echo $m2."-".$y2;?></h4>
		<hr >
<div class="row">
<table class="table table-bordered" width="100%"  border="0" style="padding-left:40px">
<thead>
<tr>
<th>S.NO</th>
<th>Month / Year </th>
<th>Sales</th>
</tr>
</thead>
<?php
$ret=mysqli_query($con,"select month(OrderDate) as lmonth,year(OrderDate) as lyear,
    tblproduct.SellingPrice,tblorder.Quantity from tblorder 
    join tblproduct on tblproduct.ID=tblorder.ProductID 
    where date(tblorder.OrderDate) between '$fdate' and '$tdate' 
    group by lmonth,lyear ");
$num=mysqli_num_rows($ret);
if($num>0){
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
<tbody>
<tr>
<td><?php echo $cnt;?></td>
<td><?php  echo $row['lmonth']."/".$row['lyear'];?></td>
<td><?php  echo $total=$row['SellingPrice']*$row['Quantity'];?></td>
</tr>
<?php
$ftotal+=$total;
$cnt++;
}?>
<tr>
<td colspan="2" align="center">Total </td>
<td><?php  echo $ftotal;?></td>
 </tr>             
</tbody>
</table>
 <?php } } else {
$year1=strtotime($fdate);
$year2=strtotime($tdate);
$y1=date("Y",$year1);
$y2=date("Y",$year2);
?>
<h4 class="header-title m-t-0 m-b-30">Sales Report Year Wise</h4>
<h4 align="center" style="color:blue">Sales Report  from <?php echo $y1;?> to <?php echo $y2;?></h4>
 <hr >
<div class="row">
<table class="table table-bordered" width="100%"  border="0" style="padding-left:40px">
<thead>
<tr>
<th>S.NO</th>
<th>Year </th>
<th>Sales</th>
</tr>
 </thead>
<?php
$ret=mysqli_query($con,"select month(OrderDate) as lmonth,year(OrderDate) as lyear,
    tblproduct.SellingPrice,tblorder.Quantity from tblorder 
    join tblproduct on tblproduct.ID=tblorder.ProductID 
    where date(tblorder.OrderDate) between '$fdate' and '$tdate'
    group by lyear ");
$num=mysqli_num_rows($ret);
if($num>0){
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
<tbody>
<tr>
<td><?php echo $cnt;?></td>
<td><?php  echo $row['lyear'];?></td>
<td><?php  echo $total=$row['SellingPrice']*$row['Quantity'];?></td>
</tr>
<?php
$ftotal+=$total;
$cnt++;
}?>
<tr>
<td colspan="2" align="center">Total </td>
<td><?php  echo $ftotal;?></td>
</tr>             
</tbody>
</table>  <?php } } }?>  
</div>
      </div>
    </div>  
  </div><!--/center-->
  <hr>
</div><!--/container-fluid-->
	<!-- script references -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
