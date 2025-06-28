<?php
session_start();
include_once "config.php";

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: signinn.php");
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
$profileImage = !empty($_SESSION['profile_image']) ? htmlspecialchars($_SESSION['profile_image']) : 'img/default.png';
$role = $_SESSION['role'] ?? 'user';

// Prices per destination
$prices = [
    'Rome' => 150,
    'Tokyo' => 200,
    'Paris' => 120,
];

// Grab and clear messages from session
$successMsg = $_SESSION['success'] ?? '';
$errorMsg = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Planning - Lonely Travel</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />

  <link rel="icon" href="img/download-removebg-preview.png" />

  <style>
    .navbar-brand span {
      color: #000080;
    }
    .card img {
      object-fit: cover;
      height: 200px;
    }
    .badge-price {
      font-size: 0.9rem;
    }
    .form-label {
      font-size: 0.85rem;
      margin-bottom: 0.2rem;
    }
  </style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2 px-3">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="index.php">
      Lonely <span>Travel</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Destinations</a></li>
        <li class="nav-item"><a class="nav-link active" href="planning.php">Planning</a></li>
        <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
        <?php if ($role === 'admin'): ?>
          <li class="nav-item"><a class="nav-link text-primary" href="dashboard.php">Admin Dashboard</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= $profileImage ?>" alt="Profile" class="rounded-circle me-2" width="30" height="30" />
            <?= $username ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
            <li><a class="dropdown-item" href="user_dashboard.php">Profile</a></li>
            <li><a class="dropdown-item" href="watchlist.php">WatchList</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('Logout?')">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5">
  <h1 class="text-center fw-bold mb-4">Plan Your Perfect Trip</h1>
  <p class="text-center text-muted mb-5">Pick dates and see when it's cheaper! Choose number of tickets and book easily.</p>

  <!-- Display messages -->
  <?php if ($successMsg): ?>
    <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
  <?php endif; ?>
  <?php if ($errorMsg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
  <?php endif; ?>

  <div class="row row-cols-1 row-cols-md-3 g-4">

    <!-- Rome Card -->
    <div class="col">
      <div class="card h-100 shadow-sm">
        <img src="img/Rome.avif" class="card-img-top" alt="Rome">
        <div class="card-body d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-title mb-0">Rome, Italy</h5>
            <span class="badge bg-success badge-price">$<?= $prices['Rome'] ?></span>
          </div>
          <p class="text-muted small mb-3">Cheapest next date: <strong>2025-07-10</strong></p>
          <form method="post" action="purchase.php" class="mt-auto">
            <input type="hidden" name="destination" value="Rome" />
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label" for="rome_date">Date</label>
                <input id="rome_date" name="travel_date" type="date" class="form-control form-control-sm" required>
              </div>
              <div class="col-6">
                <label class="form-label" for="rome_tickets">Tickets</label>
                <input id="rome_tickets" name="tickets" type="number" class="form-control form-control-sm" min="1" value="1" required>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Book Now</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Tokyo Card -->
    <div class="col">
      <div class="card h-100 shadow-sm">
        <img src="img/Tokyo.jpg" class="card-img-top" alt="Tokyo">
        <div class="card-body d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-title mb-0">Tokyo, Japan</h5>
            <span class="badge bg-success badge-price">$<?= $prices['Tokyo'] ?></span>
          </div>
          <p class="text-muted small mb-3">Cheapest next date: <strong>2025-08-05</strong></p>
          <form method="post" action="purchase.php" class="mt-auto">
            <input type="hidden" name="destination" value="Tokyo" />
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label" for="tokyo_date">Date</label>
                <input id="tokyo_date" name="travel_date" type="date" class="form-control form-control-sm" required>
              </div>
              <div class="col-6">
                <label class="form-label" for="tokyo_tickets">Tickets</label>
                <input id="tokyo_tickets" name="tickets" type="number" class="form-control form-control-sm" min="1" value="1" required>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Book Now</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Paris Card -->
    <div class="col">
      <div class="card h-100 shadow-sm">
        <img src="img/Parking-paris.webp" class="card-img-top" alt="Paris">
        <div class="card-body d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-title mb-0">Paris, France</h5>
            <span class="badge bg-success badge-price">$<?= $prices['Paris'] ?></span>
          </div>
          <p class="text-muted small mb-3">Cheapest next date: <strong>2025-07-20</strong></p>
          <form method="post" action="purchase.php" class="mt-auto">
            <input type="hidden" name="destination" value="Paris" />
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label" for="paris_date">Date</label>
                <input id="paris_date" name="travel_date" type="date" class="form-control form-control-sm" required>
              </div>
              <div class="col-6">
                <label class="form-label" for="paris_tickets">Tickets</label>
                <input id="paris_tickets" name="tickets" type="number" class="form-control form-control-sm" min="1" value="1" required>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Book Now</button>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<footer class="bg-white text-center py-3 border-top mt-5">
  <p class="mb-0 text-muted">© 2025 Lonely Travel. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
