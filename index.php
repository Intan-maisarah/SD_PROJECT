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
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
    <div class="hero-content">
        <div class="hero-img">
        <img src="assets/images/printing.gif" class="hero-img" alt="Printing GIF">
        </div>
        <?php if (!isset($_SESSION['signin'])) { ?>
        <div class="hero-text">
            <h1 class="hero-title">Let Us Handle All Your Printing Needs.</h1>            
            <p class="hero-par">Your on-the-go printing service!</p>
            <button class="hero-btn" onclick="window.location.href='user/signup.php'">Get Started</button>
        </div>
        <?php } else { ?>
            <div class="hero-text">
                <h1 class="hero-title">Let Us Handle All Your Printing Needs.</h1>            
                <p class="hero-par">Your on-the-go printing service!</p>
                <form id="upload-form" action="Admin_Dashboard/service/upload.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="file" id="file" required onchange="submitForm()" style="display:none;">
                    <button type="button" class="hero-btn" onclick="document.getElementById('file').click();">Upload Document</button>               
                </form>
            </div>

        <?php } ?>
        <div class="arrow">
            <img src="assets/images/arrow.png">
        </div>
    </div>
    <div class="icon-container">
    <i class="fa fa-print icon"></i>
    <i class="fa fa-file icon"></i>
    <i class="fa fa-paperclip icon"></i>
    <i class="fa fa-envelope icon"></i>
    <i class="fa fa-cloud icon"></i>
    <i class="fa fa-paper-plane icon"></i>
    <i class="fa fa-cogs icon"></i>
    <i class="fa fa-laptop icon"></i>
    <i class="fa fa-printer icon"></i>
    <i class="fa fa-camera icon"></i>
    <i class="fa fa-image icon"></i>
    <i class="fa fa-pencil-alt icon"></i>
    <i class="fa fa-users icon"></i>
    <i class="fa fa-globe icon"></i>
    <i class="fa fa-check-circle icon"></i>
    <i class="fa fa-thumbs-up icon"></i>
    <i class="fa fa-heart icon"></i>
    <i class="fa fa-star icon"></i>
    <i class="fa fa-bell icon"></i>
    <i class="fa fa-music icon"></i>
    <i class="fa fa-calendar icon"></i>
    <i class="fa fa-clock icon"></i>
    <i class="fa fa-shopping-cart icon"></i>
    <i class="fa fa-bolt icon"></i>
    <i class="fa fa-chart-line icon"></i>
    <i class="fa fa-map icon"></i>
    <i class="fa fa-folder-open icon"></i>
    <i class="fa fa-signal icon"></i>
    <i class="fa fa-users-cog icon"></i>
    <i class="fa fa-comments icon"></i>
    <i class="fa fa-lightbulb icon"></i>
    <i class="fa fa-code icon"></i>
    </div>
</section>

<div class="wave-container">
  <svg class="wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffd166" d="M0,64L48,85.3C96,107,192,149,288,176C384,203,480,213,576,218.7C672,224,768,224,864,213.3C960,203,1056,181,1152,181.3C1248,181,1344,203,1392,213.3L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
  </svg>
</div>

<section class="extra-section" id="extra">
    <div class="extra-content">
        <h1>The best solution for your work.</h1>
        <h3>Experience high-quality, professional printing services designed to meet 
            your specific needs. From business materials to personal projects,
             we provide customized solutions with precision and care.</h3>
    </div>
    <div class="goals-content">
    <div class="goals-header">
        <div class="moon">
            <img src="assets/images/moon.png" alt="Moon Image">
        </div>
        <h1>Our goals.</h1>
    </div>
        <div class="goals-cards-container">
            <!-- Goal Card 1 -->
            <div class="goal-card">
                <img src="assets/images/goal1.jpg" alt="Goal 1 Image">
                <p>We make printing simple and fast, so you can focus on what matters.</p>
            </div>
            <!-- Goal Card 2 -->
            <div class="goal-card">
                <img src="assets/images/goal2.jpg" alt="Goal 2 Image">
                <p>Every document is handled with care to ensure vibrant colors, crisp text, and long-lasting quality.</p>
            </div>
            <!-- Goal Card 3 -->
            <div class="goal-card">
                <img src="assets/images/goal3.jpg" alt="Goal 3 Image">
                <p>We offer competitive pricing with no hidden fees, making professional printing accessible for everyone.</p>
            </div>
        </div>
        <div class="wave-container2">
  <svg class="wave2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#d6d1f0" d="M0,64L48,85.3C96,107,192,149,288,176C384,203,480,213,576,218.7C672,224,768,224,864,213.3C960,203,1056,181,1152,181.3C1248,181,1344,203,1392,213.3L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
  </svg>
</div>
</section>
    <!-- Footer -->
    <footer class="text-white" style="background-color: #A7C7E7; padding: 40px 0;">
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
    <script >
    function submitForm() {
    const form = document.getElementById('upload-form');
    const loadingMessage = document.createElement('p'); // Create a loading message
    loadingMessage.textContent = 'Uploading...'; // Set the message
    document.querySelector('.hero-text').appendChild(loadingMessage); // Append it to the hero text area

    form.submit(); // Submit the form automatically
    }
    </script>

</body>
</html>
