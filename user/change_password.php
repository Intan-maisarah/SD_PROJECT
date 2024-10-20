<?php
session_start();
require '../connection.php'; 
function validatePassword($password) {
    return preg_match('/.{8,}/', $password) &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/\d/', $password) &&
           preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; 

    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('New passwords do not match.');</script>";
    } elseif (!validatePassword($newPassword)) {
        echo "<script>alert('New password does not meet the requirements.');</script>";
    } else {
        $query = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($currentPassword, $hashedPassword)) {
            // Hash the new password before storing it in the database
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('si', $newHashedPassword, $user_id);

            if ($updateStmt->execute()) {
                echo "<script>alert('Your password has been updated successfully!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Failed to update the password. Please try again later.');</script>";
            }
            $updateStmt->close();
        } else {
            echo "<script>alert('Current password is incorrect.');</script>";
        }
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
    #requirementsMessage { color: red; }
</style>
<link rel="stylesheet" href="../assets/ipasss.css">
</head>
<body>
<div class="container-xl px-4 mt-4">
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="../assets/images/logo.png" alt="Logo" style="width: 100px; height: auto;"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <nav>
                <div id="marker"></div>
                <a href="../index.php#home" class="active">Home</a>
                <a href="../index.php#services">Services</a>
                <a href="../index.php#about">About</a>
                <a href="../index.php#contact">Contact</a>
                <a href="../index.php#feedback">Feedback</a>

                </nav>
                <?php if (!isset($_SESSION['signin'])): ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='signin.php'">Log In</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='signup.php'">Sign Up</button>
                    </div>
                <?php else: ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='../logout.php'">Log Out</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='view_profile.php'">Profile</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>  
    <br>
    <hr class="mt-0 mb-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">Change Password</div>
                <div class="card-body">
                    <form action="change_password.php" method="POST">
                        <div id="requirementsMessage"></div>
                        <div class="mb-3">
                            <label class="small mb-1" for="currentPassword">Current Password</label>
                            <input class="form-control" id="currentPassword" name="currentPassword" type="password" placeholder="Enter current password" required>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="newPassword">New Password</label>
                            <input class="form-control" id="newPassword" name="newPassword" type="password" placeholder="Enter new password" required>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="confirmPassword">Confirm Password</label>
                            <input class="form-control" id="confirmPassword" name="confirmPassword" type="password" placeholder="Confirm new password" required>
                        </div>
                        <button class="btn btn-secondary" type="submit">Save</button>
                        <button class="btn btn-secondary" onclick="history.back()">Back</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const newPasswordInput = document.getElementById('newPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const requirementsMessage = document.getElementById('requirementsMessage');

        function validatePassword(password) {
            const minLength = /.{8,}/;
            const upperCase = /[A-Z]/;
            const number = /\d/;
            const specialChar = /[!@#$%^&*(),.?":{}|<>]/;

            return minLength.test(password) && upperCase.test(password) && number.test(password) && specialChar.test(password);
        }

        form.addEventListener('submit', function(event) {
            let newPassword = newPasswordInput.value;
            let confirmPassword = confirmPasswordInput.value;

            if (!validatePassword(newPassword)) {
                requirementsMessage.innerText = 'Password must be at least 8 characters long, include at least one uppercase letter, one number, and one special character.';
                event.preventDefault(); 
            } else if (newPassword !== confirmPassword) {
                requirementsMessage.innerText = 'New passwords do not match.';
                event.preventDefault(); 
            }
        });
    });
</script>
</body>
</html>
