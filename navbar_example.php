<?php
session_start();






// start session if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include navbar
include 'navbar.php';
 


// Example: Simulate a logged-in user (for testing)
// Remove this in your real pages, because session is set on login
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'john_doe';
    $_SESSION['profile_image'] = 'img/default.png'; // Change to actual image path if needed
    $_SESSION['role'] = 'user'; // Change to 'admin' to test admin menu
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Navbar Example - Lonely Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
    <a class="navbar-brand" href="#">Lonely <span style="color: #0d6efd;">Travel</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="index.php">Destinations</a></li>
            <li class="nav-item"><a class="nav-link" href="planning.php">Planning</a></li>
            <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
        </ul>

        <?php if (isset($_SESSION['username'])): ?>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" 
                   data-bs-toggle="dropdown" aria-expanded="false" style="color:#000;">
                    <img src="<?= htmlspecialchars($_SESSION['profile_image'] ?? 'img/default.png') ?>" alt="Profile" class="rounded-circle me-2" width="30" height="30" />
                    <?= htmlspecialchars($_SESSION['username']) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                    <li><a class="dropdown-item" href="user_dashboard.php">Profile</a></li>
                    <li><a class="dropdown-item" href="watchlist.php">WatchList</a></li>

                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a class="dropdown-item text-primary" href="dashboard.php">Admin Dashboard</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item text-primary" href="dashboard_user.php">My Dashboard</a></li>
                    <?php endif; ?>

                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
                </ul>
            </li>
        </ul>
        <?php endif; ?>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
