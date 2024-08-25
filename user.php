<?php

function validatePassword($username,$password){
    $con=mysqli_connect("serverhost","web1","web1","ipss");
    if($con){
        echo mysqli_connect_error();
        exit;
    }
}
 




?>

