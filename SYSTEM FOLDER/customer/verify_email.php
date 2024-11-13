<?php
session_start();

include '../connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token and activate user
    $query = 'SELECT * FROM users WHERE verification_token = ? AND is_verified = 0';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $updateQuery = 'UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = ?';
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('s', $token);
        if ($updateStmt->execute()) {
            $message = '<p>Your email has been verified successfully.</p>';
        } else {
            $message = '<p>Failed to verify your email. Please try again later.</p>';
        }
    } else {
        $message = '<p>Invalid or expired token. Please try again.</p>';
    }
} else {
    $message = '<p>No token provided. Please check your email for the verification link.</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification</title>
    <style>
        /* General Body Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Container Styles */
.container {
    width: 80%;
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Heading Styles */
h1 {
    color: #4CAF50;
    margin-bottom: 20px;
}

/* Paragraph Styles */
p {
    font-size: 16px;
    line-height: 1.6;
    margin: 20px 0;
}

/* Button Styles */
button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

    </style>
</head>
<body>

    <div class="container">
        <h1>Email Verification</h1>
        <?php echo $message; ?>
    </div>

</body>
</html>
