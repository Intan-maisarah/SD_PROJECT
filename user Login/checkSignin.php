
<?php
session_start(); 
//checkLogin.php
include "user.php";
$_SESSION['username']=$_POST['username'];  
$_SESSION['password']=$_POST['password'];  
//$_SESSION['curTime']=time('G:i:sa');//get the login time


// username and password sent from form 
$username=$_POST['username']; 
$password=$_POST['password']; 


$isValidUser = validatePassword($username,$password);

if($isValidUser)
	{
	$userType=getUserType($username);
	if($userType =='ADMIN')
		header("location:../index.php"); // redirect to admin page
	else if($userType =='CUSTOMER')
		header("location:../customerMenu.php"); // redirect to customer menu page
	else
		header("location:../staffMenu.php"); // redirect to staff menu page
	}

?>
	
