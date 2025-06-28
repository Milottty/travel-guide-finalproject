<?php
// Before HTML, get cart count for logged-in user
$cartCount = 0;
if (isset($_SESSION['id'])) {
    try {
        $stmt = $conn->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
        $stmt->execute([$_SESSION['id']]);
        $cartCount = $stmt->fetchColumn() ?: 0;
    } catch (PDOException $e) {
        $cartCount = 0;
    }
}
?>
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
        <li class="nav-item"><a class="nav-link active" href="index.php">Destinations</a></li>
        <li class="nav-item"><a class="nav-link" href="planning.php">Planning</a></li>
        <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link text-primary" href="dashboard.php">Admin Dashboard</a></li>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav ms-auto align-items-center">
        <!-- Cart Icon with badge -->
        <li class="nav-item me-3">
          <a href="cart.php" class="nav-link position-relative" title="Your Cart">
            <i class="ri-shopping-cart-line" style="font-size: 1.5rem;"></i>
            <?php if ($cartCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $cartCount ?></span>
            <?php endif; ?>
          </a>
        </li>

        <!-- User Profile Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="profile-img me-2" />
            <?= htmlspecialchars($username) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <?php if (isset($_SESSION['id'])): ?>
              <li><a class="dropdown-item" href="settings.php">Settings</a></li>
              <li><a class="dropdown-item" href="user_dashboard.php">Profile</a></li>
              <li><a class="dropdown-item" href="watchlist.php">Watchlist</a></li>
              <li><hr class="dropdown-divider"></li>
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
