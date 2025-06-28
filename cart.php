<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}

$userId = $_SESSION['id'];

// Fetch cart items with product details
try {
    $stmt = $conn->prepare("
        SELECT c.product_id, c.quantity, p.name, p.price, p.image 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error loading cart: " . $e->getMessage());
}

// Calculate total
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Cart - Lonely Travel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2 px-3">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="index.php">Lonely <span>Travel</span></a>
  </div>
</nav>

<div class="container py-4">
  <h1>Your Shopping Cart</h1>

  <?php if (empty($cartItems)): ?>
    <p>Your cart is empty. <a href="shop.php">Shop now!</a></p>
  <?php else: ?>
  <table class="table align-middle">
    <thead>
      <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cartItems as $item): ?>
      <tr>
        <td>
          <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 60px; height: 40px; object-fit: cover;" />
          <?= htmlspecialchars($item['name']) ?>
        </td>
        <td>$<?= number_format($item['price'], 2) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        <td>
          <form method="post" action="remove_from_cart.php" onsubmit="return confirm('Remove this item?');">
            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>" />
            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="3" class="text-end fw-bold">Total:</td>
        <td colspan="2" class="fw-bold">$<?= number_format($total, 2) ?></td>
      </tr>
    </tbody>
  </table>
  <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
