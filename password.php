<?php
session_start();


// start session if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include navbar
include 'navbar.php';




include_once "config.php";
include_once "header.php";

if (!isset($_SESSION['username'])) {
    header("Location: sign.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Lonely Travel</title>
    <link rel="stylesheet" href="css/sign.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alatsi&family=Bebas+Neue&family=Miniver&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="img/download-removebg-preview.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />

</head>
<body>
    <!-- Navbar Section -->
    <nav>
        <div class="nav-header">
            <div class="nav-logo">
                <a href="#">Lonely <span>Travel</span></a>
            </div>
            <div class="nav-menu-btn" id="menu-btn">
                <span><i class="ri-menu-line"></i></span>
            </div>
        </div>
        <ul class="nav-links" id="nav-links">
            <li><a href="index.html" class="nav-link">Destinations</a></li>
            <li><a href="planning.html" class="nav-link">Planning</a></li>
            <li><a href="shop.html" class="nav-link">Shop</a></li>
        </ul>
        <div class="nav-btn">
            <button class="btn sign-up"><a href="sign.html">Sign Up</a></button>
            <button class="btn sign-in"><a href="signinn.html">Sign In</a></button>
        </div>
    </nav>
    

    <div class="div-container">
        <div class="div-img">
            <img src="img/GettyImages-1061872058.avif" alt="Forgot Password Image">
        </div>
        <div class="sign-up-container">
            <h2>Forgot Password</h2>
            <p>Please enter your email address to receive a password reset link.</p>
            <form action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>

                <button type="submit" class="btn submit-btn"><a href="verify.html">Send Reset Link</a></button>
            </form>
        </div>
    </div>

  
    <script> window.chtlConfig = { chatbotId: "1162981525" } </script>
    <script async data-id="1162981525" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
    <script src="js/sign.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
