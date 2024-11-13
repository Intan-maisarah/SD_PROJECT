<?php
session_start();
include '../connection.php';
include 'user.php';

if (isset($_SESSION['email_sent']) && $_SESSION['email_sent']) {
    $popup_message = 'A verification link has been sent to your email. Please check your inbox.';
    unset($_SESSION['email_sent']);
} else {
    $popup_message = '';
}

if (isset($_POST['signin'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['signin'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            $userType = getUserType($conn, $username);
            if ($userType == 'ADMIN') {
                header('Location: ../Admin_Dashboard/admin/admin_page.php');
                exit;
            } elseif ($userType == 'STAFF') {
                header('Location: ../Admin_Dashboard/staff/staff_page.php');
                exit;
            } else {
                header('Location: ../index.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Incorrect Username or Password';
        }
    } else {
        $_SESSION['error'] = 'Incorrect Username or Password';
    }

    header('Location: signin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In</title>

    <!-- MDB Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.0.0/mdb.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }
        .h-custom {
            height: calc(100% - 73px);
        }
        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }
        .error-message {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="../assets/images/printer.png"
                        class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Log In</p>
                    <form action="signin.php" method="POST">
                    

                        <!-- Username input -->
                        <div class="form-outline mb-4">
                            <input type="text" name="username" id="form3Example3" class="form-control form-control-lg"
                                placeholder="Enter your username" required />
                            <label class="form-label" for="form3Example3">Username</label>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-3">
                            <input type="password" name="password" id="form3Example4" class="form-control form-control-lg"
                                placeholder="Enter password" required />
                            <label class="form-label" for="form3Example4">Password</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                           
                            <a href="#forgotPasswordModal" class="text-body" data-mdb-toggle="modal">Forgot password?</a>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" name="signin" class="btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                            <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="signup.php"
                                    class="link-danger">Register</a></p>
                        </div>
                    </form>

                    <!-- Display error message -->
                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="error-message mt-3">
                            <?php
                                echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
            <!-- Copyright -->
            <div class="text-white mb-3 mb-md-0">
                Copyright © 2020. All rights reserved.
            </div>
            
        </div>
    </section>

   <!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Forgot Password Form -->
                <form id="forgotPasswordForm">
                    <div class="form-outline mb-4">
                        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Enter your email" required />
                        <label class="form-label" for="email">Email</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                </form>
                <!-- Success/Error message -->
                <div id="modalMessage" class="mt-3">
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- MDB Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.0.0/mdb.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        <?php if (!empty($popup_message)) { ?>
            alert('<?php echo $popup_message; ?>');  
        <?php } ?>
    });
    </script>
</body>
</html>
