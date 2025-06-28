<?php
session_start();
include_once "config.php";

// Restrict to admins
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php"); exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT place_image FROM places WHERE id = ?");
    $stmt->execute([$id]);
    if ($img = $stmt->fetchColumn()) {
        $stmt = $conn->prepare("DELETE FROM places WHERE id = ?");
        $stmt->execute([$id]);
        if (file_exists($img)) unlink($img);
        $_SESSION['success'] = "Place deleted.";
    } else {
        $_SESSION['error'] = "Place not found.";
    }
}

header("Location: admin_dashboard.php");
exit();
