<?php
session_start();
require 'connection.php'; // Your database connection

if (isset($_POST['submit_password'])) {
    $token = $_GET['token'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    // Validate token and new password
    if (empty($token) || empty($new_password)) {
        $_SESSION['error'] = 'Invalid token or password';
        header('Location: reset_password.php?token=' . urlencode($token));
        exit();
    }

    // Validate the new password
    if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password) || !preg_match('/[@$!%*?&#]/', $new_password)) {
        $_SESSION['error'] = 'Password must be at least 8 characters long and include at least one uppercase letter, one number, and one special character.';
        header('Location: reset_password.php?token=' . urlencode($token));
        exit();
    }

    // Check token and its expiration in the database
    $stmt = $conn->prepare("SELECT reset_token, reset_token_expiration FROM users WHERE reset_token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $stored_token = $row['reset_token'];
        $token_expiration = $row['reset_token_expiration'];

        // Check if the token is expired
        if ($token_expiration > date('Y-m-d H:i:s')) {
            // Token is valid, update the password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiration = NULL WHERE reset_token = ?");
            $stmt->bind_param('ss', $hashed_password, $token);
            if ($stmt->execute()) {
                // Set success message and redirect with JavaScript
                echo "<script>
                    alert('Your password has been reset');
                    window.location.href = 'signin.php';
                </script>";
                exit();
            } else {
                $_SESSION['error'] = 'Failed to update password';
                header('Location: reset_password.php?token=' . urlencode($token));
                exit();
            }
        } else {
            $_SESSION['error'] = 'Token has expired';
            header('Location: reset_password.php?token=' . urlencode($token));
            exit();
        }
    } else {
        $_SESSION['error'] = 'Invalid token';
        header('Location: reset_password.php?token=' . urlencode($token));
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* Existing styles */
    </style>
    <script>
        function validatePassword() {
            var password = document.getElementById('new_password').value;
            var errorMessage = '';

            // Check password length
            if (password.length < 8) {
                errorMessage += 'Password must be at least 8 characters long.\n';
            }

            // Check for at least one uppercase letter
            if (!/[A-Z]/.test(password)) {
                errorMessage += 'Password must include at least one uppercase letter.\n';
            }

            // Check for at least one number
            if (!/[0-9]/.test(password)) {
                errorMessage += 'Password must include at least one number.\n';
            }

            // Check for at least one special character
            if (!/[@$!%*?&#]/.test(password)) {
                errorMessage += 'Password must include at least one special character.\n';
            }

            if (errorMessage) {
                alert(errorMessage);
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Reset Your Password</h2>

    <div class="message">
        <?php
        // Show error messages if any
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']); // Clear the message after displaying
        }
        ?>
    </div>

    <!-- Reset Password Form -->
    <form action="" method="POST" onsubmit="return validatePassword();">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" placeholder="At least 8 characters, one uppercase, one number, one special character" required>
        
        <button type="submit" name="submit_password">Reset Password</button>
    </form>
</div>

</body>
</html>
