<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'connection.php'; // Your database connection

// Generate a new token
$token = bin2hex(random_bytes(32)); // Generates a 64-character token
$expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Set the expiry to 1 hour from now

// Assume user ID is 1 for this example
$user_id = 12; 

// Update the user's reset token and expiry in the database
$stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiration = ? WHERE id = ?");
if ($stmt === false) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param('ssi', $token, $expiry, $user_id);
if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

if ($stmt->affected_rows > 0) {
    echo "Token generated and stored successfully!";
} else {
    echo "Failed to generate token!";
}

$stmt->close();
$conn->close();
?>
