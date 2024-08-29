<?php

session_start();
$servername = "serverhost";
$username = "web1";
$password = "web1";
$dbname = "ipss";


$con= new mysqli("serverhost","web1","web1","ipss");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $con->real_escape_string($_POST['username']);
    $password = $con->real_escape_string($_POST['password']);
    $customer_name = $con->real_escape_string($_POST['customer_name']);
    $contact = $con->real_escape_string($_POST['contact']);
    $email = $con->real_escape_string($_POST['email']);
    $state = $con->real_escape_string($_POST['state']);
    $postcode = $con->real_escape_string($_POST['postcode']);
    $city = $con->real_escape_string($_POST['city']);
    $address = $con->real_escape_string($_POST['address']);
    $veryficatio_code = $con->real_escape_string($_POST['verification_code']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $SQL = "INSERT INTO customer (username, password, customer_name, contact, email, state, postcode, city, address, verification_code)
            VALUES ('$username', '$hashed_password', '$customer_name', '$contact', '$email', '$state', '$postcode', '$city', '$address', '$verification_code' )";
    
    if($con->QUERY($sql) === TRUE){
        $forward = $email;
        $subject = "Email verification";
        $message = "Your verification code is: $verification_code";
        $headers = "From: System_message@main.com";

    if(mail($forward, $subject, $message, $headers)){
        echo "A verification code has been sent to your email.";

    }else{
        echo "Failed to send verification code to your email. ";
    }
    }
   
    if($con->query($sql) === TRUE){
        echo "Your Record been saved";
    }else{
        echo "Error: " .$sql. "<br>" .$con->error;
    }
}
?>
