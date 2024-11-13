<link rel="stylesheet" href="../assets/ipasss.css">
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><img src="../assets/images/logo.png" alt="Logo" style="width: 100px; height: auto;"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <nav>
                    <a href="../index.php" class="active">Home</a>
                    <a href="servicepage.php">Services</a>
                    <a href="about.php">About Us</a>
                    <a href="contact.php">Contact Us</a>
                </nav>
                <?php if (!isset($_SESSION['signin'])) { ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded sm-4" onclick="window.location.href='signin.php'">Log In</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded sm-4" onclick="window.location.href='signup.php'">Sign Up</button>
                    </div>
                <?php } else { ?>
                    <a href="order_history.php">History</a>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded sm-4" onclick="window.location.href='../logout.php'">Log Out</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded sm-4" onclick="window.location.href='view_profile.php'">Profile</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </nav>