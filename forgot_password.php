<?php
ob_start();
session_start();
require 'connection.php'; // Your database connection

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload the PHPMailer classes via Composer
require 'vendor/autoload.php';

if (isset($_POST['submit_email'])) {
    $email = $_POST['email'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format';
        header('Location: signin.php#forgotPasswordModal'); 
        exit();
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

        // Save token and expiry in the database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiration = ? WHERE email = ?");
        $stmt->bind_param('sss', $token, $expiry, $email);
        if ($stmt->execute()) {

            // Determine base URL
            $base_url = ($_SERVER['HTTP_HOST'] == 'localhost') 
                        ? "http://localhost/SD_PROJECT" 
                        : "http://infinityprinting.com";
            
            // Prepare the reset link
            $reset_link = $base_url . "/reset_password.php?token=" . $token;

            // Send the reset link via PHPMailer
            $mail = new PHPMailer(true);
            
            try {
                //Server settings
                $mail->isSMTP();                   
                $mail->SMTPDebug = 0; // Disable debug output               
                $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server
                $mail->SMTPAuth   = true;                         
                $mail->Username   = 'dayangziha@gmail.com';     // SMTP username
                $mail->Password   = 'fknw ujbi ecku tqmn';        // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
                $mail->Port       = 587;                          // TCP port

                //Recipients
                $mail->setFrom('no-reply@infinityprinting.com', 'Infinity Printing');
                $mail->addAddress($email);                        // Add recipient's email

                // Content
                $mail->isHTML(true);                              // Set email format to HTML
                $mail->Subject = 'Password Reset Request';
                $mail->Body    = 'Hi, click the link below to reset your password:<br><br><a href="' . $reset_link . '">Reset Password</a>';
                $mail->AltBody = 'Hi, click the link below to reset your password: ' . $reset_link;

                // Send the email
                $mail->send();
                $_SESSION['success'] = 'A password reset link has been sent to your email';
            } catch (Exception $e) {
                $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['error'] = 'Database update failed';
        }
    } else {
        $_SESSION['error'] = 'Email not found';
    }

    header('Location: signin.php#forgotPasswordModal'); // Redirect to the page with a fragment identifier to show the modal
    exit();
}
ob_end_flush();
?>
