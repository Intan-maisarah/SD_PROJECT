<?php
include "connection.php";

function getListOfCustomer()
{
 //create connection to mysql server
 include "connection.php";
 if(!$conn)//if error
	{
	 echo mysqli_connect_error();
	}
else
	{
	echo 'connected';
	$sql ="select * from customer";
	$customerListQry = mysqli_query($conn,$sql);
	return $customerListQry;
	}
	
}

/*function deleteCustomer()
{
//create connection
//create sql statement to delete record
//delete record	
	//create connection to mysql server
 $con = mysqli_connect("localhost","webs412024",
	"webs412024","labskill5");
 if(!$con)//if error
	{
	 echo mysqli_connect_error();
	}
else
	{
	echo 'connected';
	$customerIdToDelete = $_POST['usernameToDelete'];
	$sql = "delete from customer where username = '$usernameToDelete'";
	//echo $sql;
	mysqli_query($con,$sql);
	}
	
}*/
/*
function addNewCustomer()
{
 $con = mysqli_connect("localhost","webs412024",
	"webs412024","labskill5");
 if(!$con)//if error
	{
	 echo mysqli_connect_error();
	}
else
	{
	//collect form data
	
	
	$username = $_POST['username'];
	$name = $_POST['name'];
	$contactNumber = $_POST['contactNumber'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	//cosntruct insert sql stmt
	 $sql = "insert into customer(username,name,contactNumber,email,password)
			values('$username','$name','$contactNumber',
			'$email','$password')";
	echo $sql;
	//run query
	mysqli_query($con,$sql);
	}	
	
	
}*/
function getCustomerInformation($customerId)
{
 //create connection to mysql server
 include "connection.php";

 if(!$conn)//if error
	{
	 echo mysqli_connect_error();
	}
else
	{
	echo 'connected';
	$sql ="select * from customer where username='$username'";
	echo $sql;
	$customerListQry = mysqli_query($conn,$sql);
	return $customerListQry;
	}
	
}
function updateCustomer()
{

    include "connection.php";

 if(!$conn)
	{
	echo mysqli_connect_error(); 
	}
else //connected
	{
	 //collect all the data from updateForm
     $username = $_POST['username'];
     $name = $_POST['name'];
     $email = $_POST['email'];
     $contact = $_POST['contact'];
     $address = $_POST['address'];


	 
	 $sql = "update customer set username ='$username',
	 name='$name',email='$email',contact='$contact',address='$address'
	 where username='$username'";
	 echo $sql;
	 mysqli_query($conn,$sql);
	 
	}	
}

?>








