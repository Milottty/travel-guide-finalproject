<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    $_SESSION['error'] = "Please log in to save items to your watchlist.";
    header("Location: shop.php");
    exit();
}

$user_id = $_SESSION['id'];
$item_type = $_GET['item_type'] ?? '';
$item_id = intval($_GET['item_id'] ?? 0);

if (!in_array($item_type, ['destination', 'product']) || $item_id <= 0) {
    $_SESSION['error'] = "Invalid watchlist item.";
    header("Location: shop.php");
    exit();
}

// Prevent duplicates
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM watchlist WHERE user_id = ? AND item_type = ? AND item_id = ?");
    $stmt->execute([$user_id, $item_type, $item_id]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        $_SESSION['success'] = "Item already in your watchlist!";
    } else {
        $stmt = $conn->prepare("INSERT INTO watchlist (user_id, item_type, item_id) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $item_type, $item_id]);
        $_SESSION['success'] = "Item added to your watchlist!";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
}

header("Location: shop.php");
exit();
