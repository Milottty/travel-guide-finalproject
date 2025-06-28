<?php
session_start();


// start session if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include navbar
include 'navbar.php';




include_once "config.php";

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim inputs
    $emri = trim($_POST['emri'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (!$emri) $errors[] = "Name is required.";
    if (!$username) $errors[] = "Username is required.";
    if (!$email) $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (!$password) $errors[] = "Password is required.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    // Check if username or email already exist
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Username or email already taken.";
        }
    }

    // Handle profile image upload (optional)
    $profileImagePath = 'img/default.png'; // Default image
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

    // Insert user if no errors
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';

        $insert = $conn->prepare("INSERT INTO users (emri, username, email, password, role, profile_image) 
                                  VALUES (:emri, :username, :email, :password, :role, :profile_image)");

        $success = $insert->execute([
            ':emri' => $emri,
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role,
            ':profile_image' => $profileImagePath
        ]);

        if ($success) {
            header("Location: signinn.php?registered=1");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - Lonely Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Create an Account</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="emri" class="form-label">Name</label>
            <input type="text" name="emri" id="emri" class="form-control" value="<?= htmlspecialchars($_POST['emri'] ?? '') ?>" required />
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required />
        </div>

        <div class="mb-3">
            <label for="profile_image" class="form-label">Profile Image (optional)</label>
            <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*" />
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <p class="text-center mt-3">Already have an account? <a href="signinn.php">Sign In</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
