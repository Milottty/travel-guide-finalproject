<?php
session_start();


// start session if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include navbar
include 'navbar.php';



include_once "config.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$userId = (int)$_GET['id'];
$errors = [];
$success = false;

// Fetch user info to pre-fill form
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emri = trim($_POST['emri'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (!$emri) $errors[] = "Name is required.";
    if (!$username) $errors[] = "Username is required.";
    if (!$email) $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";

    // Password update validation
    if ($password || $confirm_password) {
        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }
    }

    // Check if username or email are taken by others
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE (username = :username OR email = :email) AND id != :id");
        $stmt->execute([':username' => $username, ':email' => $email, ':id' => $userId]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Username or email already taken by another user.";
        }
    }

    // Handle profile image upload
    $profileImagePath = $user['profile_image']; // default to old image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['profile_image']['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Only JPG, PNG, GIF, or WEBP images are allowed.";
        } else {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
                $profileImagePath = $targetFilePath;
            } else {
                $errors[] = "Failed to upload profile image.";
            }
        }
    }

    // Update user if no errors
    if (empty($errors)) {
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET emri = :emri, username = :username, email = :email, password = :password, profile_image = :profile_image WHERE id = :id";
            $params = [
                ':emri' => $emri,
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':profile_image' => $profileImagePath,
                ':id' => $userId
            ];
        } else {
            $sql = "UPDATE users SET emri = :emri, username = :username, email = :email, profile_image = :profile_image WHERE id = :id";
            $params = [
                ':emri' => $emri,
                ':username' => $username,
                ':email' => $email,
                ':profile_image' => $profileImagePath,
                ':id' => $userId
            ];
        }

        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($params);

        if ($success) {
            // Refresh user data after update
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute([':id' => $userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $errors[] = "Failed to update user.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4">Edit User</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif ($success): ?>
        <div class="alert alert-success">
            User updated successfully.
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="emri" class="form-label">Name</label>
            <input type="text" name="emri" id="emri" class="form-control" value="<?= htmlspecialchars($user['emri']) ?>" required />
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password (leave blank to keep current)</label>
            <input type="password" name="password" id="password" class="form-control" />
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" />
        </div>

        <div class="mb-3">
            <label>Current Profile Image:</label><br/>
            <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;" />
        </div>

        <div class="mb-3">
            <label for="profile_image" class="form-label">Change Profile Image (optional)</label>
            <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*" />
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="dashboard.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
