<?php
session_start();
include_once "config.php";

// Check if user is logged in
$username = $_SESSION['username'] ?? 'Guest';
$userId = $_SESSION['id'] ?? 0;
$profileImage = $_SESSION['profile_image'] ?? 'img/default.png';

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);

    if ($userId) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$quantity, $userId, $productId]);
        } else {
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $productId, $quantity]);
        }

        $_SESSION['success'] = "Product added to cart!";
    } else {
        $_SESSION['error'] = "Please login to add to cart.";
    }

    header("Location: shop.php");
    exit();
}

// Handle Buy Now (direct order)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'buy_now') {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);

    if ($userId) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $productId, $quantity]);

        $_SESSION['success'] = "Purchase successful!";
    } else {
        $_SESSION['error'] = "Please login to buy.";
    }

    header("Location: shop.php");
    exit();
}

// Fetch products
try {
    $stmt = $conn->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error loading products: " . $e->getMessage());
}

// Fetch cart count
$cartCount = 0;
if ($userId) {
    $stmt = $conn->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    $cartCount = $stmt->fetchColumn() ?: 0;
}

// Messages
$successMsg = $_SESSION['success'] ?? '';
$errorMsg = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shop - Lonely Travel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="icon" href="img/download-removebg-preview.png" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
  <style>
    body {
        background-color: #f8f9fa;
    }
    .navbar-brand span {
        color: #000080;
    }
    .card img {
        object-fit: cover;
        height: 200px;
    }
    .profile-img {
        width: 32px;
        height: 32px;
        object-fit: cover;
        border-radius: 50%;
    }
    .btn-watchlist {
        border: 1px solid #ccc;
        background-color: white;
        color: #dc3545;
    }
    .btn-watchlist:hover {
        background-color: #dc3545;
        color: white;
    }
  </style>
</head>
<body>

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
        <li class="nav-item"><a class="nav-link active" href="shop.php">Shop</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li class="nav-item"><a class="nav-link text-primary" href="dashboard.php">Admin Dashboard</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-3">
          <a href="cart.php" class="nav-link position-relative">
            <i class="ri-shopping-cart-line" style="font-size: 1.5rem;"></i>
            <?php if ($cartCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $cartCount ?></span>
            <?php endif; ?>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="profile-img me-2" />
            <?= htmlspecialchars($username) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <?php if ($userId): ?>
            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
            <li><a class="dropdown-item" href="user_dashboard.php">Profile</a></li>
            <li><a class="dropdown-item" href="watchlist.php">Watchlist</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('Logout?')">Logout</a></li>
            <?php else: ?>
            <li><a class="dropdown-item" href="signin.php">Login</a></li>
            <li><a class="dropdown-item" href="signup.php">Register</a></li>
            <?php endif; ?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5">
  <h1 class="mb-4 fw-bold text-center">Shop</h1>

  <?php if ($successMsg): ?>
  <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
  <?php endif; ?>
  <?php if ($errorMsg): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
  <?php endif; ?>

  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($products as $product): ?>
    <div class="col">
      <div class="card h-100 shadow-sm">
        <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
          <p class="card-text text-muted mb-2">$<?= number_format($product['price'], 2) ?></p>

          <?php if ($userId): ?>
          <form method="post" class="mt-auto mb-2">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="hidden" name="action" value="add_to_cart">
            <div class="input-group input-group-sm">
              <input type="number" name="quantity" class="form-control" value="1" min="1" required>
              <button type="submit" class="btn btn-primary">Add to Cart</button>
            </div>
          </form>

          <form method="post" class="mt-1">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="action" value="buy_now">
            <button type="submit" class="btn btn-success w-100">Buy Now</button>
          </form>

          <a href="add_to_watchlist.php?item_type=product&item_id=<?= $product['id'] ?>" class="btn btn-watchlist w-100 mt-2">
            ❤️ Save to Watchlist
          </a>
          <?php else: ?>
          <p class="text-muted">Login to purchase or save to watchlist.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<footer class="bg-white text-center py-3 border-top mt-auto">
  <p class="mb-0 text-muted">© 2025 Lonely Travel. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
