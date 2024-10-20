<?php
include 'services.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity Printing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/ipasss.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nanum+Pen+Script&family=Shadows+Into+Light&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Gochi+Hand&family=Nanum+Pen+Script&family=Shadows+Into+Light&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="assets/images/logo.png" alt="Logo" style="width: 100px; height: auto;"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <nav>
                    <a href="index.php" class="active">Home</a>
                    <a href="servicepage.php">Services</a>
                    <a href="about.php">About</a>
                    <a href="contact.php">Contact</a>
                </nav>
                <?php if (!isset($_SESSION['signin'])) { ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded sm-4" onclick="window.location.href='user/signin.php'">Log In</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded sm-4" onclick="window.location.href='user/signup.php'">Sign Up</button>
                    </div>
                <?php } else { ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded sm-4" onclick="window.location.href='logout.php'">Log Out</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded sm-4" onclick="window.location.href='user/view_profile.php'">Profile</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </nav>

    <section id="services">
        <div class="container">
            <h2>Our Services</h2>
            <div class="service-container">
            <?php
            if (!empty($services)) {
                foreach ($services as $service) {
                    echo '<div class="service-item">';
                    echo '<h2>'.htmlspecialchars($service['service_name']).'</h2>';
                    echo '<p>'.htmlspecialchars($service['service_description']).'</p>';
                    if (!empty($service['image'])) {
                        $imagePath = 'assets/images/'.htmlspecialchars($service['image']);
                        echo '<img src="'.$imagePath.'" alt="'.htmlspecialchars($service['service_name']).'">';
                    }

                    echo '</div>';
                }
            } else {
                echo '<p>No services available at the moment.</p>';
            }
?>
            </div>
        </div>
</section>

    <!-- Footer -->
    <footer class="text-white" style="background-color: #74b3ce; padding: 40px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>
                        Our online printing service was inspired by the needs and creativity of students who constantly
                        juggle tight deadlines and demanding schedules. With 24/7 document uploads and a convenient 1km
                        delivery range, we provide the flexibility students need, ensuring their printing requirements are met
                        anytime, anywhere.
                    </p>
                </div>

                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-center mb-2">
                            <img src="assets/images/location.png" alt="Location Icon" style="width: 24px; height: auto; margin-right: 10px;">
                            <span>Gurney Mall, Lot 1-30, Jln Maktab, 54000 Kuala Lumpur</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <img src="assets/images/call.png" alt="Phone Icon" style="width: 24px; height: auto; margin-right: 10px;">
                            <span>+6014 2272-647</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <img src="assets/images/mail.png" alt="Mail Icon" style="width: 24px; height: auto; margin-right: 10px;">
                            <span>infinity.utmkl@gmail.com</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <img src="assets/images/bhours.png" alt="Business Hours Icon" style="width: 24px; height: auto; margin-right: 10px;">
                            <span>Mon-Fri: 9 AM - 6 PM</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   

</body>
</html>
