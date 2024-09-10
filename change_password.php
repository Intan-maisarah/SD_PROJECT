<?php
session_start();
require 'connection.php'; // Include database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user ID from session (ensure the user is logged in and session is active)
    $user_id = $_SESSION['user_id']; // Assuming you have the user_id stored in session after login

    // Get the form data
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    // Fetch the current password hash from the database
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify if the current password matches the hashed password in the database
    if (password_verify($currentPassword, $hashedPassword)) {
        // Hash the new password before storing it in the database
        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('si', $newHashedPassword, $user_id);

        // Execute the query and check if the update is successful
        if ($updateStmt->execute()) {
            echo "<script>alert('Your password has been updated successfully!'); window.location.href='signin.php';</script>";
        } else {
            echo "<script>alert('Failed to update the password. Please try again later.');</script>";
        }
        $updateStmt->close();
    } else {
        echo "<script>alert('Current password is incorrect.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Change Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    body {margin-top:20px; background-color:#f2f6fc; color:#69707a;}
    .img-account-profile { height: 10rem; }
    .rounded-circle { border-radius: 50% !important; }
    .card { box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%); }
    .card .card-header { font-weight: 500; }
    .card-header:first-child { border-radius: 0.35rem 0.35rem 0 0; }
    .card-header { padding: 1rem 1.35rem; margin-bottom: 0; background-color: rgba(33, 40, 50, 0.03); border-bottom: 1px solid rgba(33, 40, 50, 0.125); }
    .form-control, .dataTable-input { display: block; width: 100%; padding: 0.875rem 1.125rem; font-size: 0.875rem; font-weight: 400; line-height: 1; color: #69707a; background-color: #fff; background-clip: padding-box; border: 1px solid #c5ccd6; -webkit-appearance: none; -moz-appearance: none; appearance: none; border-radius: 0.35rem; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; }
    .nav-borders .nav-link.active { color: #0061f2; border-bottom-color: #0061f2; }
    .nav-borders .nav-link { color: #69707a; border-bottom-width: 0.125rem; border-bottom-style: solid; border-bottom-color: transparent; padding-top: 0.5rem; padding-bottom: 0.5rem; padding-left: 0; padding-right: 0; margin-left: 1rem; margin-right: 1rem; }
    .btn-danger-soft { color: #000; background-color: #f1e0e3; border-color: #f1e0e3; }
</style>
</head>
<body>
<div class="container-xl px-4 mt-4">
    <nav class="nav nav-borders">
        <a class="nav-link" href="view_profile.html" target="_self">Profile</a>
        <a class="nav-link active ms-0" href="change_password.php" target="_self">Security</a>
        <a class="nav-link ms-auto" href="index.php" target="_self">Home</a>
    </nav>   
<hr class="mt-0 mb-4">
<div class="row">
<div class="col-lg-8">
<div class="card mb-4">
<div class="card-header">Change Password</div>
<div class="card-body">
<form action="change_password.php" method="POST">
    <div class="mb-3">
        <label class="small mb-1" for="currentPassword">Current Password</label>
        <input class="form-control" id="currentPassword" name="currentPassword" type="password" placeholder="Enter current password" required>
    </div>
    <div class="mb-3">
        <label class="small mb-1" for="newPassword">New Password</label>
        <input class="form-control" id="newPassword" name="newPassword" type="password" placeholder="Enter new password" required>
    </div>
    <button class="btn btn-primary" type="submit">Save</button>
</form>
</div>
</div>
</div>
<div class="col-lg-4">
<div class="card mb-4">
<div class="card-header">Delete Account</div>
<div class="card-body">
<p>Deleting your account is a permanent action and cannot be undone. If you are sure you want to delete your account, select the button below.</p>
<button class="btn btn-danger-soft text-danger" type="button">I understand, delete my account</button>
</div>
</div>
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
