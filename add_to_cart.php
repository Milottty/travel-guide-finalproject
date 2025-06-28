<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    // Not logged in
    header("Location: signin.php");
    exit();
}

$userId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = max(1, intval($_POST['quantity'] ?? 1));

    if ($product_id <= 0) {
        $_SESSION['error'] = "Invalid product.";
        header("Location: shop.php");
        exit();
    }

    try {
        // Check if product exists
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if (!$product) {
            $_SESSION['error'] = "Product not found.";
            header("Location: shop.php");
            exit();
        }

        // Check if product already in cart
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $product_id]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Update quantity
            $newQuantity = $existing['quantity'] + $quantity;
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$newQuantity, $userId, $product_id]);
        } else {
            // Insert new row
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $product_id, $quantity]);
        }

        $_SESSION['success'] = "Added to cart!";
        header("Location: shop.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: shop.php");
        exit();
    }
} else {
    header("Location: shop.php");
    exit();
}
