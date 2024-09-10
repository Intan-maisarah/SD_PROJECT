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
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="assets/images/logo.png" alt="Logo"style="width: 100px; height: auto;"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <nav>
                    <div id="marker"></div>
                    <a href="#home">Home</a>
                    <a href="#services">Services</a>
                    <a href="#about">About</a>
                    <a href="#contact">Contact</a>
                    <a href="#feedback">Feedback</a>
                </nav>
                <?php if (!isset($_SESSION['signin'])): ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='signin.php'">Log In</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='signup.php'">Sign Up</button>
                    </div>
                <?php else: ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='logout.php'">Log Out</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='view_profile.html'">Profile</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home" >
        <div class="hero-overlay"></div>
        <div class="hero-content" >
            <h1>Let Us Handle All Your Printing Needs.</h1>
            <p>Your on the go printing service!</p>
            <button class="btn btn-primary" onclick="window.location.href='signin.php'">Get Started</button>
        </div>
        
    </section>

    <section id="services">
        <div class="container">
            <h2>Our Services</h2>
            <!-- Service content here -->
        </div>
    </section>

    <section id="about">
   <div class="container">
    <h2>About Us</h2>
    <h6 style="font-size: 1.2em; color: #777; margin-bottom: 40px;">
      Address: Gurney Mall, Lot 1-30, Jln Maktab, 54000 Kuala Lumpur
    </h6>

    <!-- Google Maps Embed -->
    <div class="map-container" style="width: 100%; height: 400px; margin-bottom: 40px; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d434.727282914923!2d101.7217383527438!3d3.1720723536050173!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc37a3f442ec45%3A0xc85fb666f112d900!2sInfinity%20Printing%20and%20Stationery!5e0!3m2!1sen!2smy!4v1725715700058!5m2!1sen!2smy"
        width="100%" 
        height="400" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>

    <p style="font-size: 1.1em; color: #555; text-align: justify; padding: 0 20px;">
      Welcome to <strong>Infinity Printing & Stationery Service</strong>, your go-to solution for remote document printing and delivery.
      Our platform allows you to easily upload your documents, track order statuses, and choose between convenient pickup appointments or delivery within a 1km range.
      With a focus on reducing wait times and improving service quality, we offer a streamlined experience for customers, staff, and administrators alike. 
      Whether you need quick access to printed materials or efficient management of your printing needs, Infinity Printing is here to serve you anytime, anywhere.
    </p>
  </div>
</section>


<section id="contact">
    <div class="container">
        <h2>Contact Us</h2>
        <!-- Contact content here -->

        <!-- Instagram Account Link -->
        <p>Follow us on Instagram:</p>
        <a href="https://www.instagram.com/infinity.utmkl" target="_blank" style="text-decoration: none; color: inherit;">
            <i class="fab fa-instagram" style="font-size: 24px; margin-right: 8px;"></i> @infinity.utmkl
        </a>

        <!-- Email Address -->
        <p>Email us at:</p>
        <a href="mailto:infinity.utmkl@gmail.com" style="text-decoration: none; color: inherit;">
            <i class="fas fa-envelope" style="font-size: 20px; margin-right: 8px;"></i> infinity.utmkl@gmail.com
        </a>
        
        <!-- Phone Number -->
        <p>Call us:</p>
        <a href="tel:+60142272646" style="text-decoration: none; color: inherit;">
            <i class="fas fa-phone-alt" style="font-size: 20px; margin-right: 8px;"></i> +6014 2272-647
        </a>
    </div>
</section>

<section id="feedback">
    <div class="container">
        <h2>Feedback</h2>
        <!-- Feedback Form -->
        <form action="send_feedback.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="message">Feedback:</label>
                <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Feedback</button>
        </form>
    </div>
</section>


<!-- Footer -->
<footer class="text-white" style="background-color: #74b3ce; padding: 40px 0;">
  <div class="container">
    <div class="row">
      <!-- Left side: Short paragraph -->
      <div class="col-md-6">
        <p>
          Our online printing service was inspired by the needs and creativity of students who constantly
          juggle tight deadlines and demanding schedules. With 24/7 document uploads and a convenient 1km
          delivery range, we provide the flexibility students need, ensuring their printing requirements are met
          anytime, anywhere.
        </p>
      </div>

      <!-- Right side: List of icons with information, aligned left -->
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



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/ipasss.js"></script>
</body>
</html>
