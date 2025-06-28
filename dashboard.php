<?php
session_start();
include_once "config.php";

// Only admins allowed
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

$successMsg = $_SESSION['success'] ?? '';
$errorMsg = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Fetch users
$stmtUsers = $conn->prepare("SELECT id, username, email, role FROM users ORDER BY id ASC");
$stmtUsers->execute();
$users = $stmtUsers->fetchAll();

// Fetch places
$stmtPlaces = $conn->prepare("SELECT * FROM places ORDER BY id ASC");
$stmtPlaces->execute();
$places = $stmtPlaces->fetchAll();

// Fetch purchases
$stmtPurchases = $conn->prepare("
    SELECT o.id, u.username, p.name AS product_name, o.quantity, o.price, o.total, o.order_date
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN products p ON o.product_id = p.id
    ORDER BY o.order_date DESC
");
$stmtPurchases->execute();
$purchases = $stmtPurchases->fetchAll();
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
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">Logged in as: <?= htmlspecialchars($_SESSION['username']) ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php" onclick="return confirm('Logout?')">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <h1 class="mb-4 text-center">Admin Dashboard</h1>

    <?php if ($successMsg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>
    <?php if ($errorMsg): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <!-- Users Table -->
    <h3>Users</h3>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Places Table -->
    <h3 class="mt-5">Places</h3>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th><th>Place Name</th><th>Description</th><th>Visitors</th><th>Price</th><th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($places as $place): ?>
            <tr>
                <td><?= htmlspecialchars($place['id']) ?></td>
                <td><?= htmlspecialchars($place['place_name']) ?></td>
                <td><?= htmlspecialchars($place['place_desc']) ?></td>
                <td><?= htmlspecialchars($place['visitors']) ?></td>
                <td>$<?= number_format($place['price'], 2) ?></td>
                <td>
                    <a href="edit_place.php?id=<?= $place['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete_place.php?id=<?= $place['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this place?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add Place Form -->
    <h3 class="mt-5">Add New Place</h3>
    <form action="add_place.php" method="POST" enctype="multipart/form-data" class="row g-3 mb-5">
        <div class="col-md-6">
            <label for="place_name" class="form-label">Place Name</label>
            <input type="text" id="place_name" name="place_name" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="place_desc" class="form-label">Description</label>
            <input type="text" id="place_desc" name="place_desc" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label for="visitors" class="form-label">Visitors</label>
            <input type="number" id="visitors" name="visitors" class="form-control" min="0" value="0" required>
        </div>
        <div class="col-md-4">
            <label for="price" class="form-label">Price ($)</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" value="0" required>
        </div>
        <div class="col-md-4">
            <label for="image" class="form-label">Image (optional)</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Add Place</button>
        </div>
    </form>

    <!-- Shop Purchases Table -->
    <h3 class="mt-5">Shop Purchases</h3>
    <?php if (count($purchases) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Order ID</th><th>User</th><th>Product</th><th>Quantity</th><th>Price Each</th><th>Total</th><th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($purchases as $purchase): ?>
                <tr>
                    <td><?= htmlspecialchars($purchase['id']) ?></td>
                    <td><?= htmlspecialchars($purchase['username']) ?></td>
                    <td><?= htmlspecialchars($purchase['product_name']) ?></td>
                    <td><?= htmlspecialchars($purchase['quantity']) ?></td>
                    <td>$<?= number_format($purchase['price'], 2) ?></td>
                    <td>$<?= number_format($purchase['total'], 2) ?></td>
                    <td><?= htmlspecialchars($purchase['order_date']) ?></td>
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
