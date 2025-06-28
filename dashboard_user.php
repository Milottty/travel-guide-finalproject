<?php
session_start();
include_once "config.php";

// Admin-only access
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

// Flash messages
$successMsg = $_SESSION['success'] ?? '';
$errorMsg = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Fetch users
$stmt = $conn->prepare("SELECT id, username, email, role FROM users ORDER BY id ASC");
$stmt->execute();
$users = $stmt->fetchAll();

// Fetch places
$stmt = $conn->prepare("SELECT * FROM places ORDER BY id ASC");
$stmt->execute();
$places = $stmt->fetchAll();

// Fetch shop purchases
$stmt = $conn->prepare("
    SELECT o.id, u.username, p.name AS product_name, o.quantity, o.price, o.total, o.order_date
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN products p ON o.product_id = p.id
    ORDER BY o.order_date DESC
");
$stmt->execute();
$purchases = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - Lonely Travel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2 px-3 mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="index.php">Lonely <span>Travel</span></a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><span class="nav-link">Logged in as: <?= htmlspecialchars($_SESSION['username']) ?></span></li>
      <li class="nav-item"><a href="logout.php" class="nav-link text-danger" onclick="return confirm('Logout?')">Logout</a></li>
    </ul>
  </div>
</nav>
<div class="container mb-5">
  <h1 class="text-center mb-4">Admin Dashboard</h1>
  <?php if ($successMsg): ?>
  <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
  <?php endif; ?>
  <?php if ($errorMsg): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
  <?php endif; ?>

  <!-- Users -->
  <h3>Users</h3>
  <table class="table table-striped">
    <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= htmlspecialchars($u['id']) ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <td>
          <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="delete_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Delete this user?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Places -->
  <h3 class="mt-5">Places</h3>
  <table class="table table-striped">
    <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Visitors</th><th>Price</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($places as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p['id']) ?></td>
        <td><?= htmlspecialchars($p['place_name']) ?></td>
        <td><?= htmlspecialchars($p['place_desc']) ?></td>
        <td><?= htmlspecialchars($p['visitors']) ?></td>
        <td>$<?= number_format($p['price'],2) ?></td>
        <td>
          <a href="edit_place.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="delete_place.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Delete this place?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Add Place -->
  <h3 class="mt-5">Add New Place</h3>
  <form method="POST" action="add_place.php" class="row g-3 mb-5">
    <div class="col-md-6"><label>Name</label><input name="place_name" class="form-control" required></div>
    <div class="col-md-6"><label>Image URL</label><input name="place_image" class="form-control" required></div>
    <div class="col-12"><label>Description</label><textarea name="place_desc" class="form-control" required></textarea></div>
    <div class="col-md-4"><label>Visitors</label><input type="number" name="visitors" class="form-control" min="0" required></div>
    <div class="col-md-4"><label>Price ($)</label><input type="number" step="0.01" name="price" class="form-control" min="0" required></div>
    <div class="col-12"><button type="submit" class="btn btn-success">Add Place</button></div>
  </form>

  <!-- Shop Purchases -->
  <h3>Shop Purchases</h3>
  <?php if ($purchases): ?>
  <table class="table table-striped">
    <thead><tr><th>Order ID</th><th>User</th><th>Product</th><th>Qty</th><th>Price Each</th><th>Total</th><th>Date</th></tr></thead>
    <tbody>
    <?php foreach ($purchases as $o): ?>
      <tr>
        <td><?= htmlspecialchars($o['id']) ?></td>
        <td><?= htmlspecialchars($o['username']) ?></td>
        <td><?= htmlspecialchars($o['product_name']) ?></td>
        <td><?= htmlspecialchars($o['quantity']) ?></td>
        <td>$<?= number_format($o['price'],2) ?></td>
        <td>$<?= number_format($o['total'],2) ?></td>
        <td><?= htmlspecialchars($o['order_date']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p>No purchases yet.</p>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
