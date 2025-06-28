<?php
session_start();
include_once "config.php";
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') header("Location: signin.php");
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id !== $_SESSION['id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "User deleted.";
    } else {
        $_SESSION['error'] = "Cannot delete yourself.";
    }
}
header("Location: admin_dashboard.php");
exit();
    