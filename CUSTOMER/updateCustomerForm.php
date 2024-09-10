<?php
include "customer.php"; // Make sure this contains the getCustomerInformation() function

$username = $name = $email = $contact = $address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the username from the POST request
    $username = $_POST['usernameToUpdate'];
    
    // Get selected customer information
    $qryCustomer = getCustomerInformation($username);
    
    if ($qryCustomer) {
        $customerRecord = mysqli_fetch_assoc($qryCustomer);
        
        if ($customerRecord) {
            // Populate form fields with customer data
            $username = $customerRecord['username'];
            $name = $customerRecord['name'];
            $email = $customerRecord['email'];
            $contact = $customerRecord['contact'];
            $address = $customerRecord['address'];
        } else {
            echo "No customer found with username: $username";
        }
    } else {
        echo "Error fetching customer data.";
    }
}
?>

<
