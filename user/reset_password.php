<?php

require '../connection.php'; 

if (isset($_POST['submit_password'])) {
    $token = $_GET['token'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (empty($token) || empty($new_password)) {
        $_SESSION['error'] = 'Invalid token or password';
        header('Location: reset_password.php?token=' . urlencode($token));
        exit();
    }

    if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password) || !preg_match('/[@$!%*?&#]/', $new_password)) {
        $_SESSION['error'] = 'Password must be at least 8 characters long and include at least one uppercase letter, one number, and one special character.';
        header('Location: reset_password.php?token=' . urlencode($token));
        exit();
    }

    $stmt = $conn->prepare("SELECT reset_token, reset_token_expiration FROM users WHERE reset_token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $stored_token = $row['reset_token'];
        $token_expiration = $row['reset_token_expiration'];

        if ($token_expiration > date('Y-m-d H:i:s')) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiration = NULL WHERE reset_token = ?");
            $stmt->bind_param('ss', $hashed_password, $token);
            if ($stmt->execute()) {
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
       /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    margin-top: 50px;
}

h2 {
    margin-top: 0;
    color: #333333;
    text-align: center;
}

.message {
    margin-bottom: 20px;
}

.error {
    color: #ff0000;
    font-weight: bold;
    text-align: center;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-weight: bold;
    margin-bottom: 10px;
}

input[type="password"] {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #cccccc;
    border-radius: 4px;
    font-size: 16px;
    width: 100%;
    box-sizing: border-box;
}

button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    box-sizing: border-box;
}

button:hover {
    background-color: #0056b3;
}

    </style>
    <script>
        function validatePassword() {
            var password = document.getElementById('new_password').value;
            var errorMessage = '';

            if (password.length < 8) {
                errorMessage += 'Password must be at least 8 characters long.\n';
            }

            if (!/[A-Z]/.test(password)) {
                errorMessage += 'Password must include at least one uppercase letter.\n';
            }

            if (!/[0-9]/.test(password)) {
                errorMessage += 'Password must include at least one number.\n';
            }

            if (!/[@$!%*?&#]/.test(password)) {
                errorMessage += 'Password must include at least one special character.\n';
            }

            if (errorMessage) {
                alert(errorMessage);
                return false; 
            }
            return true; 
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Reset Your Password</h2>

    <div class="message">
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
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
