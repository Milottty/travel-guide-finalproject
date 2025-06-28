<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}

$userId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);

    if ($product_id <= 0) {
        header("Location: cart.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $product_id]);
        $_SESSION['success'] = "Item removed from cart.";
        header("Location: cart.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: cart.php");
        exit();
    }
} else {
    header("Location: cart.php");
    exit();
}
