<?php
session_start();
include_once "config.php";

// Only admin check (optional but recommended)
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $place_name = $_POST['place_name'] ?? '';
    $place_desc = $_POST['place_desc'] ?? '';
    $visitors = intval($_POST['visitors'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);

    // Image upload handling (optional)
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $filename = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . time() . "_" . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    // Insert new place
    $stmt = $conn->prepare("INSERT INTO places (place_name, place_desc, visitors, price, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$place_name, $place_desc, $visitors, $price, $imagePath]);

    $_SESSION['success'] = "Place added successfully!";
    header("Location: shop.php");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
