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

// Get parameters and sanitize
$destination = isset($_GET['destination']) ? htmlspecialchars($_GET['destination']) : '';
$date = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';
$tickets = isset($_GET['tickets']) ? (int)$_GET['tickets'] : 1;

// Simple validation
$errors = [];
if (!$destination) $errors[] = "Destination not specified.";
if (!$date) $errors[] = "Date not specified.";
if ($tickets < 1) $errors[] = "Invalid number of tickets.";

function getPrice($destination) {
    // Simple static price lookup - extend as needed
    $prices = [
        'Rome' => 150,
        'Tokyo' => 200,
        'Paris' => 120,
    ];
    return $prices[$destination] ?? 0;
}

$pricePerTicket = getPrice($destination);
$totalPrice = $pricePerTicket * $tickets;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Confirm Booking - Lonely Travel</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />

  <link rel="icon" href="img/download-removebg-preview.png" />
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
        <li class="nav-item"><a class="nav-link" href="planning.php">Planning</a></li>
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

<!-- MAIN CONTENT -->
<div class="container my-5">
  <h1 class="mb-4">Confirm Your Booking</h1>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
          <li><?= $error ?></li>
        <?php endforeach; ?>
      </ul>
      <a href="planning.php" class="btn btn-secondary mt-3">Go Back</a>
    </div>
  <?php else: ?>
    <div class="card p-4 shadow-sm">
      <p><strong>Destination:</strong> <?= $destination ?></p>
      <p><strong>Date:</strong> <?= $date ?></p>
      <p><strong>Tickets:</strong> <?= $tickets ?></p>
      <p><strong>Price per Ticket:</strong> $<?= number_format($pricePerTicket, 2) ?></p>
      <p><strong>Total Price:</strong> $<?= number_format($totalPrice, 2) ?></p>

      <form action="purchase.php" method="post" class="mt-4">
        <input type="hidden" name="destination" value="<?= $destination ?>">
        <input type="hidden" name="date" value="<?= $date ?>">
        <input type="hidden" name="tickets" value="<?= $tickets ?>">
        <input type="hidden" name="total_price" value="<?= $totalPrice ?>">

        <button type="submit" class="btn btn-success">Confirm Purchase</button>
        <a href="planning.php" class="btn btn-secondary ms-2">Cancel</a>
      </form>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
    