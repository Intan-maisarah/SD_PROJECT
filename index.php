<?php
session_start(); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'services.php'; // This should include the PHP code that populates $services
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
            <a class="navbar-brand" href="#"><img src="assets/images/logo.png" alt="Logo" style="width: 100px; height: auto;"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <nav>
                    <div id="marker"></div>
                    <a href="#home" class="active">Home</a>
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
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='view_profile.php'">Profile</button>
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
            <div class="service-container">
            <?php
            if (!empty($services)) {
                foreach ($services as $service) {
                    echo '<div class="service-item">';
                    echo '<h2>' . htmlspecialchars($service["service_name"]) . '</h2>';
                    echo '<p>' . htmlspecialchars($service["service_description"]) . '</p>';
                    if (!empty($service["image"])) {
                        echo '<img src="' . htmlspecialchars($service["image"]) . '" alt="' . htmlspecialchars($service["service_name"]) . '">';
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

    <section id="about">
  <div class="container">
    <h2>About Us</h2>
    <h6>Address: Gurney Mall, Lot 1-30, Jln Maktab, 54000 Kuala Lumpur</h6>

    <div class="about-content">
      <div class="map-container">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d434.727282914923!2d101.7217383527438!3d3.1720723536050173!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc37a3f442ec45%3A0xc85fb666f112d900!2sInfinity%20Printing%20and%20Stationery!5e0!3m2!1sen!2smy!4v1725715700058!5m2!1sen!2smy"
          width="100%" 
          height="100%" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
      <div class="text-content">
        <p>
          Welcome to <strong>Infinity Printing & Stationery Service</strong>, your go-to solution for remote document printing and delivery.
          Our platform allows you to easily upload your documents, track order statuses, and choose between convenient pickup appointments or delivery within a 1km range.
          With a focus on reducing wait times and improving service quality, we offer a streamlined experience for customers, staff, and administrators alike. 
          Whether you need quick access to printed materials or efficient management of your printing needs, Infinity Printing is here to serve you anytime, anywhere.
        </p>
      </div>
    </div>
  </div>
</section>




<section id="contact">
    <div class="contact-section">
    <div class="contact-header">
            <h2>Contact Us</h2>
        </div>
        <!-- Full-screen background image -->
        <img src="assets/images/contactreal.jpg" alt="Contact Us Image" class="contact-img">

        <!-- Interactive Popups -->
        <div class="popup-container">
            <div class="contact-popup" id="phone-popup">
                <p>Call us:</p>
                <a href="tel:+60142272646">+6014 2272-646</a>
            </div>
            <div class="contact-popup" id="email-popup">
                <p>Email us:</p>
                <a href="mailto:infinity.utmkl@gmail.com">infinity.utmkl@gmail.com</a>
            </div>
            <div class="contact-popup" id="bhours-popup" >
                <p>Business hours:</p>
                <a href="">Mon-Fri: 9am - 6pm</a>
            </div>
        </div>
    </div>
</section>





<section id="feedback">
    <div class="container">
        <h2>Feedback</h2>
        <!-- Feedback Form -->
        <form id="feedbackForm">
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
    <script>
    document.getElementById('feedbackForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission
        
        // Collect form data
        var formData = new FormData(this);
        
        // Send form data using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'send_feedback.php', true);
        
        // Handle the response
        xhr.onload = function () {
            if (xhr.status === 200) {
                // If successful, display the pop-up message
                alert('Thank you for your feedback!');
                
                // Clear the form fields after successful submission
                document.getElementById('feedbackForm').reset();
            } else {
                // If an error occurs, display an error message
                alert('Failed to send feedback. Please try again.');
            }
        };
        
        // Send the request
        xhr.send(formData);
    });

    document.addEventListener('DOMContentLoaded', function () {
        let phonePopup = document.getElementById('phone-popup');
        let emailPopup = document.getElementById('email-popup');
        let contactSection = document.querySelector('.contact-section');

        // Show the phone popup when the logo area is clicked (adjust click areas as needed)
        contactSection.addEventListener('click', function (event) {
            let isPhoneArea = event.clientX < window.innerWidth * 0.3; // Adjust the percentage based on logo position
            let isEmailArea = event.clientX > window.innerWidth * 0.5; // Adjust the percentage based on logo position

            if (isPhoneArea) {
                phonePopup.classList.toggle('show');
                emailPopup.classList.remove('show');
            } else if (isEmailArea) {
                emailPopup.classList.toggle('show');
                phonePopup.classList.remove('show');
            }
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        const marker = document.getElementById("marker");
        const navLinks = document.querySelectorAll("nav a");
        const sections = document.querySelectorAll("section"); // Ensure all your sections have the correct IDs like #home, #services, etc.

        // Function to move the marker
        function moveMarker(element) {
            marker.style.width = element.offsetWidth + "px";
            marker.style.left = element.offsetLeft + "px";
        }

        // IntersectionObserver callback
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Get the active section ID
                    const sectionId = entry.target.id;

                    // Find the corresponding nav link
                    const activeLink = document.querySelector(`nav a[href="#${sectionId}"]`);

                    // Remove active class from all links and add to the current one
                    navLinks.forEach(link => link.classList.remove("active"));
                    activeLink.classList.add("active");

                    // Move the marker
                    moveMarker(activeLink);
                }
            });
        }, {
            threshold: 0.5  // Trigger when 50% of the section is visible
        });

        // Observe each section
        sections.forEach(section => observer.observe(section));

        // Add click event listeners to each navigation link for click-based marker movement
        navLinks.forEach(link => {
            link.addEventListener("click", function() {
                navLinks.forEach(nav => nav.classList.remove("active"));
                this.classList.add("active");
                moveMarker(this);
            });
        });
    });
    </script>
</body>
</html>
