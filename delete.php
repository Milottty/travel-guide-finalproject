<?php
session_start();

include_once "config.php";

// Check if user is admin (adjust as needed)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Admins only.");
}

// Check if id is provided and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: dashboard.php");
    exit();
}

$userId = (int)$_GET['id'];

// Optional: Prevent admin from deleting themselves
if ($userId === (int)$_SESSION['user_id']) {
    $_SESSION['error'] = "You cannot delete your own account.";
    header("Location: dashboard.php");
    exit();
}

// Prepare delete statement
$stmt = $conn->prepare("DELETE FROM users WHERE id = :id");

if ($stmt->execute([':id' => $userId])) {
    $_SESSION['success'] = "User deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete user.";
}

header("Location: dashboard.php");
exit();


// start session if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include navbar
include 'navbar.php';

