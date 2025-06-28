<?php
include_once "config.php";

$username = 'admin1';
$email = 'admin@gmail.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$role = 'admin';
$image = 'img/default.png';

$stmt = $conn->prepare("INSERT INTO users (emri, username, email, password, role, profile_image)
                        VALUES ('Admin', :username, :email, :password, :role, :image)");

$success = $stmt->execute([
    ':username' => $username,
    ':email' => $email,
    ':password' => $password,
    ':role' => $role,
    ':image' => $image
]);

if ($success) {
    echo "✅ Admin user created";
} else {
    echo "❌ Failed to create admin";
}
