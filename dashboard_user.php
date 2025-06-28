<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}

$userId = $_SESSION['id'];
$username = htmlspecialchars($_SESSION['username']);
$profileImage = !empty($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'img/default.png';

// Fetch user's orders with product info
try {
    $stmt = $conn->prepare("
        SELECT o.id AS order_id, p.name AS product_name, o.quantity, p.price, o.order_date,
               (o.quantity * p.price) AS total_price
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.order_date DESC
    ");
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Your Purchases - Lonely Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .profile-img {
            width: 60px; height: 60px; border-radius: 50%; object-fit: cover;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2 px-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="index.php">Lonely <span>Travel</span></a>
        <ul class="navbar-nav ms-auto align-items-center d-flex">
            <li class="nav-item me-3">
                <a href="cart.php" class="nav-link position-relative" title="Cart">
                    <i class="ri-shopping-cart-line" style="font-size: 1.5rem;"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="profile-img me-2" />
                    <?= $username ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                    <li><a class="dropdown-item" href="dashboard_user.php">Profile</a></li>
                    <li><a class="dropdown-item" href="watchlist.php">Watchlist</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('Logout?')">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Your Purchases</h2>

    <?php if (count($orders) === 0): ?>
        <p>You have not bought anything yet.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price Each</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                    <td><?= htmlspecialchars($order['product_name']) ?></td>
                    <td><?= htmlspecialchars($order['quantity']) ?></td>
                    <td>$<?= number_format($order['price'], 2) ?></td>
                    <td>$<?= number_format($order['total_price'], 2) ?></td>
                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
