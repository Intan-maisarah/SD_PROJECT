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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
        }

        .message p {
            margin: 0;
        }

        .message .error {
            color: red;
        }

        .message .success {
            color: green;
        }
    </style>
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
    <form action="" method="POST">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
        
        <button type="submit" name="submit_password">Reset Password</button>
    </form>
</div>

</body>
</html>
