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
                    <a href="#home">home</a>
                    <a href="#services">services</a>
                    <a href="#about">about</a>
                    <a href="#contact">contact</a>
                    <a href="#feedback">feedback</a>
                </nav>
                <?php if (!isset($_SESSION['user_logged_in'])): ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='signinsignup.html?mode=login'">Log In</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='signinsignup.html?mode=signup'">Sign Up</button>
                    </div>
                <?php else: ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='logout.php'">Log Out</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <section id="home">Home Section</section>
    <section id="services">Services Section</section>
    <section id="about">About Us Section</section>
    <section id="contact">Contact Us Section</section>
    <section id="feedback">Feedback Section</section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/ipasss.js"></script>
</body>
</html>
