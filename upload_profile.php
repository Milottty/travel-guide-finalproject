<?php
session_start();
include_once "config.php";



// start session if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include navbar
include 'navbar.php';



// Only admin can upload profile images for users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Get user ID from URL
if (!isset($_GET['id'])) {
    die("User ID not specified.");
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $file = $_FILES['profile_image'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code " . $file['error']);
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
        die("Only JPG, PNG, and GIF images are allowed.");
    }

    // Validate file size (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        die("File size must be less than 2MB.");
    }

    // Generate unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid() . "." . $ext;

    // Move uploaded file
    $destination = __DIR__ . "/uploads/" . $newFileName;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        die("Failed to move uploaded file.");
    }

    // Update profile_image for specified user
    $sql = "UPDATE users SET profile_image = :profile_image WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':profile_image', $newFileName);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: edit.php?id=$id&msg=Profile image updated successfully");
        exit();
    } else {
        die("Failed to update profile image in database.");
    }
} else {
    die("No file uploaded.");
}
?>
