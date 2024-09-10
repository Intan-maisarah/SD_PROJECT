<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

<h1>Customer List</h1>
<?php
//carList.php
include "customer.php";
//1.get list of car from database
echo '<div class="w3-container">';
$customerListQry = getListOfCustomer();
//2.display the list of car
echo '<br>There are :'. mysqli_num_rows($customerListQry) 
	. ' record';
echo '<table class="w3-table w3-striped">';
	echo '<tr>';
		echo '<th>Bil</th>
		      <th>Name</th>
		      <th>Email</th>
			  <th>Contact</th>
			  <th>Username</th>  
			  <th>Password</th>
			  <th>Delete</th>
			  <th>Update</th>';
	echo '</tr>';
	//for each record in, query, display the record
	$bil=1;
	while($row=mysqli_fetch_assoc($customerListQry))
	{
		echo '<tr>';
			echo '<td>'.$bil.'</td>';
			echo '<td>'.$row['name'].'</td>';
			echo '<td>'.$row['email'].'</td>';
			echo '<td>'.$row['contactNumber'].'</td>';
			echo '<td>'.$row['username'].'</td>';
			echo '<td>'.$row['password'].'</td>';
			$usernameToDelete = $row['username']; 
			echo '<td>';//delete option
				echo '<form action="processCustomer.php" 
							method="POST">';
				echo "<input type='hidden' 
					name='usernameToDelete' value='$usernameToDelete'>";
				echo '<input type="submit" name="deleteButton"
					value="Delete">';
				echo '</form>';
			echo '</td>';
			$usernameToUpdate=$row['username'];
			echo '<td>';//update option
				echo '<form action="updateCustomerForm.php" method="post">';
					echo "<input type='hidden' name='usernameToUpdate'
						value='$usernameToUpdate'>";
					echo "<input type='submit' value='Update'
						name='updateButton'>";
				echo '</form>';
			echo '</td>';
		echo '</tr>';
		$bil++;
	}

echo '</table>';
echo '</div>';
?>

<!--</div>-->

<?php

?>
</body>
</html>