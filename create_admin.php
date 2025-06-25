<?php
include_once "config.php";

$adminUsername = 'admin';
$adminEmail = 'admin@gmail.com';
$adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
$adminRole = 'admin';

// Check if admin exists
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$adminUsername]);
if ($stmt->rowCount() == 1) {
    echo "Admin user already exists.";
} else {
    // Removed 'is_admin'
    $insert = $conn->prepare("INSERT INTO users (username, email, password, role, profile_image) VALUES (?, ?, ?, ?, ?)");
    if ($insert->execute([$adminUsername, $adminEmail, $adminPassword, $adminRole, 'default.png'])) {
        echo "Admin user created successfully.";
    } else {
        echo "Failed to create admin user.";
    }
}
