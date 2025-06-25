<?php
session_start();

// Redirect to sign-in page if user not logged in
if (!isset($_SESSION['username'])) {
    header("Location: signinn.php");
    exit();
}

// Sanitize and assign session variables for display
$username = htmlspecialchars($_SESSION['username']);

// Use profile image from session or fallback to default
$profileImage = isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])
    ? htmlspecialchars($_SESSION['profile_image'])
    : 'img/default.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lonely Travel</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Alatsi&family=Bebas+Neue&family=Miniver&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
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
                    <span style="color: #000;"><?= $username ?></span>
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

    <!-- Rest of your page content -->

    <div class="first-container">
        <h1 class="main-h1">Discover story-worthy travel moments</h1>

        <div class="section" style="background-image: url('img/GettyImages-1061872058.avif');" onclick="navigateTo('getty.php')">
            <div class="content">
                <h2 class="h2-text2">How to spend a perfect weekend in Porto</h2>
            </div>
        </div>

        <div class="section" style="background-image: url('img/shutterstock1636820080.avif');" onclick="navigateTo('train.php')">
            <div class="content">
                <h2 class="h2-text2 h2-text111">11 bucket-list Big Sur beaches</h2>
            </div>
        </div>

        <div class="section" style="background-image: url('img/Los-Angeles.avif');" onclick="navigateTo('uk.php')">
            <div class="content">
                <h2 class="h2-text2">A first-time guide to the Galápagos Islands</h2>
            </div>
        </div>
    </div>

    <h1 class="h1-text">RECOMMENDED DESTINATIONS​</h1>
    <p class="p-text">Find out more about destinations that take care of their place and their local community. Here’s four great examples:</p>
    <div class="grid-container">
        <div class="card">
            <img src="img/study-in-london.jpg" class="London" alt="London" />
            <div class="card-content">
                <p class="location">ENGLAND</p>
                <h3 class="city">London</h3>
            </div>
        </div>
        <!-- Add other cards similarly -->
    </div>

    <div class="wrapper">
        <div class="container-stats">
            <i class="ri-building-line ri"></i>
            <span class="num" date-val="177">000</span>
            <span class="text">Countries To Fly</span>
        </div>
        <div class="container-stats">
            <i class="ri-emotion-happy-fill ri"></i>
            <span class="num" date-val="3000">000</span>
            <span class="text">Happy Customers</span>
        </div>
        <div class="container-stats">
            <i class="ri-team-fill ri"></i>
            <span class="num" date-val="3500">000</span>
            <span class="text">Customers in a year</span>
        </div>
        <div class="container-stats">
            <i class="ri-star-fill ri"></i>
            <span class="num" date-val="2800">000</span>
            <span class="text">Five Stars</span>
        </div>
    </div>

    <div class="container">
        <footer class="py-3 my-4">
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                <li class="nav-item"><a href="index.php" class="nav-link px-2 text-body-secondary">Destinations</a></li>
                <li class="nav-item"><a href="planning.php" class="nav-link px-2 text-body-secondary">Planning</a></li>
                <li class="nav-item"><a href="shop.php" class="nav-link px-2 text-body-secondary">Shop</a></li>
            </ul>
            <p class="text-center text-body-secondary">© 2024 Company, Inc</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        window.chtlConfig = {
            chatbotId: "1162981525"
        };
    </script>
    <script async data-id="1162981525" id="chatling-embed-script" src="https://chatling.ai/js/embed.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
