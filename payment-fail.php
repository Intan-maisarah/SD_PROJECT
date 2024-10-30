<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>

    <!-- External Styles and Fonts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Inline Styles -->
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 100px;
            text-align: center;
        }

        .error-icon {
            font-size: 50px;
            color: #e07b7b;
        }

        .btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h1>Payment Failed</h1>
        <p>We're sorry, but your payment could not be processed at this time. Please try again later or contact support.</p>
        
        <!-- Link to go back to checkout or home -->
        <a href="checkout.php" class="btn btn-primary">Try Again</a>
        <a href="index.php" class="btn btn-secondary">Go to Home</a>

        <footer class="mt-5">
            <p>&copy; 2024 Infinity Printing. All rights reserved.</p>
            <p><span class="contact-icon"><i class="fas fa-phone"></i></span> +6010-5190074, +6014-2272-646</p>
            <p><span class="contact-icon"><i class="fas fa-envelope"></i></span> <a href="mailto:infinity.utmkl@gmail.com">infinity.utmkl@gmail.com</a></p>
        </footer>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
