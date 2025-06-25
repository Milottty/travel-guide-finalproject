<?php
session_start();
include_once "config.php";
include_once "header.php";

if (!isset($_SESSION['username'])) {
    header("Location: signinn.php");
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
$profileImage = isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])
    ? htmlspecialchars($_SESSION['profile_image'])
    : 'img/default.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shop - Lonely Travel</title>
    <link rel="stylesheet" href="css/shop.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Alatsi&family=Bebas+Neue&family=Miniver&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="icon" href="img/download-removebg-preview.png" />

    <style>
        /* Make sure nav is positioned on top */
        nav {
            position: relative;
            z-index: 9999;
        }

        .nav-item.dropdown {
            position: relative;
            z-index: 9999;
        }

        /* Style dropdown menu */
        .dropdown-menu.dropdown-menu-end.dropdown-menu-dark {
            background-color: #fff !important;
            color: #000 !important;
            border: 1px solid #000 !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            z-index: 9999 !important;
        }

        .dropdown-menu.dropdown-menu-end.dropdown-menu-dark .dropdown-item {
            color: #000 !important;
        }

        .dropdown-menu.dropdown-menu-end.dropdown-menu-dark .dropdown-item:hover,
        .dropdown-menu.dropdown-menu-end.dropdown-menu-dark .dropdown-item:focus {
            background-color: #f0f0f0 !important;
            color: #000 !important;
        }

        /* Fix for background sections being above nav */
        .first-container,
        .section {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

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
        <li><a href="index.php" class="nav-link">Destinations</a></li>
        <li><a href="planning.php" class="nav-link">Planning</a></li>
        <li><a href="shop.php" class="nav-link">Shop</a></li>
    </ul>

    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
            <a
                class="nav-link dropdown-toggle d-flex align-items-center text-white"
                href="#"
                id="userDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <img src="<?= $profileImage ?>" width="30" height="30" class="rounded-circle me-2" alt="Profile" />
                <span style="color:#000;"><?= $username ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                <li><a class="dropdown-item" href="user_dashboard.php">Profile</a></li>
                <li><a class="dropdown-item" href="watchlist.php">WatchList</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a class="dropdown-item text-primary" href="dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <li><hr class="dropdown-divider" /></li>
                <li>
                    <a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<section class="guidebooks-container">
    <h1 class="guidebooks-title">Which guidebook is right for me?</h1>
    <div class="guides">
        <!-- Classic Guides -->
        <div class="guide-category">
            <h2 class="guide-heading">CLASSIC guides</h2>
            <p class="guide-subheading">(Most Comprehensive)</p>
            <div class="guide-images">
                <img src="img/Rome.avif" alt="Italy Guide" class="guide-image" />
                <img src="img/thailand.avif" alt="Thailand Guide" class="guide-image" />
                <img src="img/new-zealand.webp" alt="New Zealand Guide" class="guide-image" />
            </div>
            <div class="guide-description">
                <div class="guide-icon">
                    <span>🔍</span>
                    <p>In-depth and Extensive</p>
                </div>
                <div class="guide-icon">
                    <span>📅</span>
                    <p>Trip duration: 2+ weeks</p>
                </div>
                <p class="guide-text">For travelers seeking the most comprehensive information, these guides will equip you to explore your destination at a deeper level.</p>
            </div>
            <div class="popup-container">
                <button type="button" class="btn shopNowButton">SHOP NOW</button>
                <div class="popup" id="popup">
                    <img src="img/404-tick.png" />
                    <h2>Thank You!</h2>
                    <p>Your details has been successfully submitted. Thanks!</p>
                    <button type="button" class="btn-close">OK</button>
                </div>
            </div>
        </div>

        <!-- Experience Guides -->
        <div class="guide-category">
            <h2 class="guide-heading">EXPERIENCE guides</h2>
            <p class="guide-subheading">(Authentic & Unique)</p>
            <div class="guide-images">
                <img src="img/Tokyo.jpg" alt="Japan Guide" class="guide-image" />
                <img src="img/death-valley.webp" alt="Andalucia Guide" class="guide-image" />
                <img src="img/Los-Angeles.avif" alt="Tokyo Guide" class="guide-image" />
            </div>
            <div class="guide-description">
                <div class="guide-icon">
                    <span>🌍</span>
                    <p>Local & Authentic Experiences</p>
                </div>
                <div class="guide-icon">
                    <span>📅</span>
                    <p>Trip duration: 1-2 weeks</p>
                </div>
                <p class="guide-text">For travelers that want to design a trip that feels unique, these guides uncover exciting new ways to explore iconic destinations.</p>
            </div>
            <div class="popup-container">
                <button type="button" class="btn shopNowButton">SHOP NOW</button>
                <div class="popup">
                    <img src="img/404-tick.png" />
                    <h2>Thank You!</h2>
                    <p>Your details has been successfully submitted. Thanks!</p>
                    <button type="button" class="btn-close">OK</button>
                </div>
            </div>
        </div>

        <!-- Pocket Guides -->
        <div class="guide-category">
            <h2 class="guide-heading">POCKET guides</h2>
            <p class="guide-subheading">(Top Highlights)</p>
            <div class="guide-images">
                <img src="img/Parking-paris.webp" alt="Berlin Guide" class="guide-image" />
                <img src="img/Berlin.avif" alt="Valencia Guide" class="guide-image" />
                <img src="img/Chattanooga-Featured.png" alt="Budapest Guide" class="guide-image" />
            </div>
            <div class="guide-description">
                <div class="guide-icon">
                    <span>✨</span>
                    <p>Highlights and Top Experiences</p>
                </div>
                <div class="guide-icon">
                    <span>📅</span>
                    <p>Trip duration: 1-7 days</p>
                </div>
                <p class="guide-text">For travelers on a short trip that want to make the most of their time, these handy-sized guides cover a city's best local experiences.</p>
            </div>
            <div class="popup-container">
                <button type="button" class="btn shopNowButton">SHOP NOW</button>
                <div class="popup">
                    <img src="img/404-tick.png" />
                    <h2>Thank You!</h2>
                    <p>Your details has been successfully submitted. Thanks!</p>
                    <button type="button" class="btn-close">OK</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script> window.chtlConfig = { chatbotId: "1162981525" } </script>
<script async data-id="1162981525" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>  
<script src="js/shop.js"></script>

<!-- ADD THIS BOOTSTRAP JS BUNDLE FOR DROPDOWN FUNCTIONALITY -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>
</html>
