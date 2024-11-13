<?php

ob_start();
session_start();
require '../connection.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require '../../vendor/autoload.php';

if (isset($_POST['submit_email'])) {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format';
        header('Location: signin.php#forgotPasswordModal');
        exit;
    }

    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $conn->prepare('UPDATE users SET reset_token = ?, reset_token_expiration = ? WHERE email = ?');
        $stmt->bind_param('sss', $token, $expiry, $email);
        if ($stmt->execute()) {
            $base_url = ($_SERVER['HTTP_HOST'] == 'localhost')
                        ? 'https://palegreen-buffalo-300863.hostingersite.com'
                        : 'http://infinityprinting.com';

            $reset_link = $base_url.'/user/reset_password.php?token='.$token;

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->SMTPDebug = 0;
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'dayangziha@gmail.com';
                $mail->Password = 'fknw ujbi ecku tqmn';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('no-reply@infinityprinting.com', 'Infinity Printing');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = 'Hi, click the link below to reset your password:<br><br><a href="'.$reset_link.'">Reset Password</a>';
                $mail->AltBody = 'Hi, click the link below to reset your password: '.$reset_link;

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

    header('Location: signin.php#forgotPasswordModal');
    exit;
}
ob_end_flush();
