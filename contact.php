<?php
session_start();
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

    <section id="contact">
<div class="contact-container">
    <div class="contact-info">
        <h2>Get in Touch with Us</h2>
        <p>Feel free to contact us anytime. We will get back to you as soon as we can!</p>
            <ul class="list-unstyled">
                <li>
                <p>Email us at</p>
                <a href="mailto:infinity.utmkl@gmail.com" class="links">infinity.utmkl@gmail.com</a>
                </li>
                <li>
                <p>Call us at</p>
                <a href="tel:+60142272646" class="links">+6014 2272-646</a>
                </li>
                <li>
                <p>We're located here:</p>
                <span>Gurney Mall, Lot 1-30, Jln Maktab, 54000 Kuala Lumpur</span>
                </li>
                <li>
                <p>Our business hours:</p>
                <span>Mon-Fri: 9 AM - 6 PM</span>
                </li>
            </ul>
    </div>
    <div class="contact-form">
        <form id="feedbackForm">
        <div class="form-group">
            <input type="text" id="name" name="name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="form-group">
            <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" required>
        </div>
        <div class="form-group">
            <textarea id="message" name="message" class="form-control" rows="5" placeholder="Your Message" required></textarea>
        </div>
        <button type="submit" class="btn">Send Message</button>
        </form>
    </div>
  </div>

  <div class="map-container">
  <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15934.826999217967!2d101.7216456!3d3.1717061!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc37a3f442ec45%3A0xc85fb666f112d900!2sInfinity%20Printing%20and%20Stationery!5e0!3m2!1sen!2smy!4v1729328998724!5m2!1sen!2smy" 
    width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade"></iframe>
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
    <script src="assets/ipasss.js"></script>
</body>
</html>
