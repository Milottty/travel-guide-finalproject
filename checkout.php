<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}

$userId = $_SESSION['id'];

// Get cart items
$stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll();

if (empty($cartItems)) {
    $_SESSION['error'] = "Your cart is empty!";
    header("Location: cart.php");
    exit();
}

// Save each item as an order
try {
    $conn->beginTransaction();

    foreach ($cartItems as $item) {
        $stmtOrder = $conn->prepare("
            INSERT INTO orders (user_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmtOrder->execute([
            $userId,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        ]);
    }

    // Empty the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);

    $conn->commit();
    $_SESSION['success'] = "Purchase successful! Thank you.";
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['error'] = "Purchase failed: " . $e->getMessage();
}

header("Location: shop.php");
exit();
?>

