<?php

include "customer.php";
print_r($_POST);



if(isSet($_POST['saveUpdateButton']))//handle update
	{
	 echo 'nak save updated data';
	 updateCustomer();//call function in car.php
	 header('Location:index.php.');
	}
?>