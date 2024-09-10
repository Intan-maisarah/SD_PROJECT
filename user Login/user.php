<?php

session_start();
//user.php
//=================== validatePassword
function validatePassword($username,$password)
{
    include "connection.php";


$sql= "SELECT * FROM users where username = '".$username ."' and password ='".$password."'";
$result=mysqli_query($con,$sql);
$count=mysqli_num_rows($result); //check how many matching record - should be 1 if correct
if($count == 1){
	return true;//username and password is valid
}
else
	{
	return false; //invalid password
	}
}

//=================== getUserType
function getUserType($username)
{
    include "connection.php";


$sql= "SELECT * FROM users where username = '".$username ."'";
$result=mysqli_query($con,$sql);
$count=mysqli_num_rows($result); //check how many matching record - should be 1 if correct
if($count == 1){
	$row = mysqli_fetch_assoc($result);
	$userType=$row['userType'];
	return $userType;
	}
 }

?>