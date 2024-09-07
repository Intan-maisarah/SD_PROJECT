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
            <a class="navbar-brand" href="#"><img src="imgs/logo.svg" alt="Logo"></a>
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
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='signin.html'">Log In</button>
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
            <button class="btn btn-primary" onclick="window.location.href='signin.html'">Get Started</button>
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
        <h6>Address: Gurney Mall, Lot 1-30, Jln Maktab, 54000 Kuala Lumpur</h6>

        <!-- Google Maps Embed -->

        <div class="map-container" style="width: 100%; height: 400px; margin-top: 20px;">
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
        <p>
        Welcome to Infinity Printing & Stationery Service, your go-to solution for remote document printing and delivery.
        Our platform allows you to easily upload your documents, track order statuses, and choose between convenient pickup appointments or delivery within a 1km range.
        With a focus on reducing wait times and improving service quality, we offer a streamlined experience for customers, staff, and administrators alike. Whether you need quick access to printed materials or efficient management of your printing needs, Infinity Printing is here to serve you anytime, anywhere.</p>
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
<footer class="text-center text-white" style="background-color: #3f51b5">
  <!-- Grid container -->
  <div class="container">
    <!-- Section: Links -->
    <div class="row text-center d-flex justify-content-center pt-3"> <!-- Reduced padding -->
      <!-- Grid column -->
      <div class="col-md-2 mb-3">
        <h6 class="text-uppercase font-weight-bold">
          <a href="#!" class="text-white">About Us</a>
        </h6>
      </div>
      <!-- Grid column -->

      <!-- Grid column -->
      <div class="col-md-2 mb-3">
        <h6 class="text-uppercase font-weight-bold">
          <a href="#!" class="text-white">Services</a>
        </h6>
      </div>
      <!-- Grid column -->

      <!-- Grid column -->
      <div class="col-md-2 mb-3">
        <h6 class="text-uppercase font-weight-bold">
          <a href="#!" class="text-white">Contact Us</a>
        </h6>
      </div>
      <!-- Grid column -->

      <!-- Grid column -->
      <div class="col-md-2 mb-3">
        <h6 class="text-uppercase font-weight-bold">
          <a href="#!" class="text-white">Feedback</a>
        </h6>
      </div>
      <!-- Grid column -->
    </div>
    <!-- Section: Links -->

    <hr class="my-4" /> <!-- Adjusted hr spacing -->

    <!-- Section: Text -->
    <div class="row d-flex justify-content-center" style="padding-bottom: 20px;"> <!-- Added padding -->
      <div class="col-lg-8">
        <p>
        Our online printing service was inspired by the needs and creativity of students who constantly
        juggle tight deadlines and demanding schedules. Recognizing their struggle to find convenient,
        reliable printing options, we set out to create a service that fits seamlessly into their busy lives.
        With 24/7 document uploads and a convenient 1km delivery range, our service is designed to
        provide students with the flexibility they need, ensuring that their printing needs are met anytime, anywhere.
        </p>
      </div>
    </div>
    <!-- Section: Text -->
  </div>
  <!-- Grid container -->
</footer>
<!-- Footer -->


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/ipasss.js"></script>
</body>
</html>
