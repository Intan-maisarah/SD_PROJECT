<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity Printing</title>
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
    <section class="hero-section" id="home">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Let Us Handle All Your Printing Needs.</h1>
            <p>Your on the go printing service!</p>
            <button class="btn btn-primary">Get Started</button>
        </div>
        <img src="imgs/sample-image.png" alt="Printing Materials" class="hero-image">
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
            <!-- About content here -->
             
        </div>
    </section>

    <section id="contact">
        <div class="container">
            <h2>Contact Us</h2>
            <!-- Contact content here -->
        </div>
    </section>

    <section id="feedback">
        <div class="container">
            <h2>Feedback</h2>
            <!-- Feedback content here -->
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/ipasss.js"></script>
</body>
</html>
