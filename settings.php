<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}

$userId = $_SESSION['id'];
$successMsg = $_SESSION['success'] ?? '';
$errorMsg = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

try {
    // Fetch user data
    $stmt = $conn->prepare("SELECT emri, username, email, profile_image FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if (!$user) {
        die("User not found.");
    }
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emri = trim($_POST['emri'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($emri) || empty($username) || empty($email)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: settings.php");
        exit();
    }

    // Handle profile image upload if any
    $profileImage = $user['profile_image'];
    if (!empty($_FILES['profile_image']['name'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir);

        $tmpName = $_FILES['profile_image']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (!in_array($ext, $allowed)) {
            $_SESSION['error'] = "Invalid image format.";
            header("Location: settings.php");
            exit();
        }

        $newFilename = $uploadDir . 'profile_' . $userId . '.' . $ext;
        if (move_uploaded_file($tmpName, $newFilename)) {
            $profileImage = $newFilename;
        } else {
            $_SESSION['error'] = "Failed to upload image.";
            header("Location: settings.php");
            exit();
        }
    }

    // Update user data
    try {
        $stmt = $conn->prepare("UPDATE users SET emri = ?, username = ?, email = ?, profile_image = ? WHERE id = ?");
        $stmt->execute([$emri, $username, $email, $profileImage, $userId]);

        // Update session username and profile image to reflect changes immediately
        $_SESSION['username'] = $username;
        $_SESSION['profile_image'] = $profileImage;

        $_SESSION['success'] = "Profile updated successfully.";
        header("Location: settings.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating profile: " . $e->getMessage();
        header("Location: settings.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Settings - Lonely Travel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
.profile-img-preview {
    width: 100px; height: 100px; object-fit: cover; border-radius: 50%;
}
</style>
</head>
<body>
<div class="container py-4">
    <h1>Edit Profile</h1>

    <?php if ($successMsg): ?>
    <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>
    <?php if ($errorMsg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="emri" class="form-label">Name</label>
            <input type="text" id="emri" name="emri" class="form-control" required value="<?= htmlspecialchars($user['emri']) ?>" />
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" required value="<?= htmlspecialchars($user['username']) ?>" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>" />
        </div>

        <div class="mb-3">
            <label for="profile_image" class="form-label">Profile Image</label><br />
            <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile Image" class="profile-img-preview mb-2" />
            <input type="file" id="profile_image" name="profile_image" accept="image/*" class="form-control" />
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
       <button class="btn btn-primary" type="submit" >
            <a href="index.php">
                Done
            </a>

       </button>
    </form>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
