<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "connection.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'vendor/autoload.php';

// Initialize error message variable
$error_message = '';
$success_message = '';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    } 
    // Check if passwords match
    elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match. Please try again.';
    } 
    // Check password length
    elseif (strlen($password) < 8) {
        $error_message = 'Password must be at least 8 characters long.';
    } 
    else {
        // Check if email already exists
        $check = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($check);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $error_message = 'This email is already in use. Please try another email or log in if you already have an account.';
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database with a verification token
            $verification_token = bin2hex(random_bytes(16));
            $sql = "INSERT INTO users(username, email, password, verification_token, is_verified) VALUES(?, ?, ?, ?, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $username, $email, $hashed_password, $verification_token);
            $result = $stmt->execute();

            if ($result) {
                // Send verification email
                $mail = new PHPMailer(true);
            
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'dayangziha@gmail.com'; // Replace with your email
                    $mail->Password = 'fknw ujbi ecku tqmn'; // Use environment variables for security
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
            
                    // Recipients
                    $mail->setFrom('no-reply@infinityprinting.com', 'Infinity Printing');
                    $mail->addAddress($email);
            
                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Verify Your Email Address';
                    $mail->Body    = '<p>Hi ' . htmlspecialchars($username) . ',</p>
                                      <p>Thank you for registering. Please click the link below to verify your email address:</p>
                                      <p><a href="http://localhost/SD_PROJECT/verify_email.php?token=' . $verification_token . '">Verify Email</a></p>';
            
                    $mail->send();
            
                    // Set session variable to trigger alert
                    $_SESSION['email_sent'] = true;
                    
                    // Redirect after successful email send
                    header("Location: signin.php");
                    exit();
                } catch (Exception $e) {
                    error_log('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
                    $error_message = 'Message could not be sent. Please try again later.';
                }
            }
            
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up</title>
    <!-- MDB Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.0.0/mdb.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
                                    <?php if (!empty($error_message)): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo htmlspecialchars($error_message); ?>
                                    </div>
                                    <?php endif; ?>
                                    <form id="signupForm" action="signup.php" method="POST" class="mx-1 mx-md-4">
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <input type="text" name="username" id="form3Example1c" class="form-control" required />
                                                <label class="form-label" for="form3Example1c">Your Username</label>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <input type="email" name="email" id="form3Example3c" class="form-control" required />
                                                <label class="form-label" for="form3Example3c">Your Email</label>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <input type="password" name="password" id="form3Example4c" class="form-control" required />
                                                <label class="form-label" for="form3Example4c">Password</label>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <input type="password" name="confirm_password" id="form3Example4cd" class="form-control" required />
                                                <label class="form-label" for="form3Example4cd">Repeat your password</label>
                                            </div>
                                        </div>
                                        <div class="form-check d-flex justify-content-center mb-5">
                                            <input class="form-check-input me-2" type="checkbox" value="" id="agreeCheckbox" required />
                                            <label class="form-check-label" for="agreeCheckbox">
                                                I agree to all statements in <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of service</a>
                                            </label>
                                        </div>
                                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                            <button type="submit" name="register" class="btn btn-primary btn-lg">Register</button>
                                        </div>
                                    </form>
                                    <div class="text-center">
                                        <p>Already have an account? <a href="signin.php">Sign in</a></p>
                                    </div>
                                </div>
                                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                    <img src="assets/images/laptop.png" class="img-fluid" alt="Sample image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms of Service Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms of Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Terms of Service</strong></p>
                    <p>Welcome to Infinity Printing!</p>
                    <p>By accessing or using our website and services, you agree to comply with and be bound by the following terms and conditions. If you do not agree to these terms, please do not use our services.</p>
                    <h3>1. Use of Service</h3>
                    <p>You agree to use our services only for lawful purposes and in accordance with our guidelines. You may not use our services to engage in any activity that is illegal, harmful, or disruptive.</p>
                    <h3>2. User Accounts</h3>
                    <p>To use certain features of our service, you may need to create an account. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account.</p>
                    <h3>3. Prohibited Activities</h3>
                    <p>You agree not to:
                        <ul>
                            <li>Engage in any fraudulent or deceptive practices.</li>
                            <li>Upload or transmit any malicious software or viruses.</li>
                            <li>Infringe upon the intellectual property rights of others.</li>
                            <li>Use our service to harass or harm others.</li>
                        </ul>
                    </p>
                    <h3>4. Payment and Billing</h3>
                    <p>All payments must be made in accordance with the billing terms outlined on our website. We reserve the right to modify our pricing at any time.</p>
                    <h3>5. Termination</h3>
                    <p>We may terminate or suspend your account and access to our services if you violate these terms or engage in behavior that we consider inappropriate or harmful.</p>
                    <h3>6. Limitation of Liability</h3>
                    <p>Our liability is limited to the maximum extent permitted by law. We are not responsible for any indirect, incidental, or consequential damages arising from your use of our services.</p>
                    <h3>7. Changes to Terms</h3>
                    <p>We may update these Terms of Service from time to time. Any changes will be posted on this page, and your continued use of our services constitutes your acceptance of the updated terms.</p>
                    <h3>8. Contact Us</h3>
                    <p>If you have any questions or concerns about these Terms of Service, please contact us at <a href="mailto:support@infinityprinting.com">support@infinityprinting.com</a>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    

    <!-- MDB Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.0.0/mdb.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('DOMContentLoaded', function() {
    // Check if the session variable is set
    <?php if (isset($_SESSION['email_sent']) && $_SESSION['email_sent']): ?>
    alert('Verification link has been sent to your email. Please check your inbox.');
    <?php unset($_SESSION['email_sent']); // Clear the session variable ?>
    <?php endif; ?>
});

        document.getElementById('signupForm').addEventListener('submit', function(event) {
            var password = document.getElementById('form3Example4c').value;
            var confirmPassword = document.getElementById('form3Example4cd').value;
            var checkbox = document.getElementById('agreeCheckbox');
            var errorMessage = '';

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

            if (password !== confirmPassword) {
                errorMessage += 'Passwords do not match.\n';
            }

            if (errorMessage) {
                alert(errorMessage);
                event.preventDefault(); // Prevent form submission
            } else if (!checkbox.checked) {
                event.preventDefault();
                alert('You must agree to the terms of service before registering.');
            }
        });
    });
    </script>
</body>
</html>
