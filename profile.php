<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: signinn.php");
    exit();
}

$userid = $_SESSION['id'];

// Fetch current user info
$stmt = $conn->prepare("SELECT emri, email, profile_image FROM users WHERE id = ?");
$stmt->execute([$userid]);
$user = $stmt->fetch();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emri = $_POST['emri'] ?? '';
    $email = $_POST['email'] ?? '';

    if (!$emri || !$email) {
        $error = "Name and email cannot be empty.";
    } else {
        $update = $conn->prepare("UPDATE users SET emri = ?, email = ? WHERE id = ?");
        if ($update->execute([$emri, $email, $userid])) {
            $success = "Profile updated successfully.";
            $_SESSION['username'] = $emri; // update session username if you want
        } else {
            $error = "Error updating profile.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Profile - Lonely Travel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<nav><!-- nav here --></nav>

<div class="container my-5">
  <h1>Edit Profile</h1>
  <?php if ($success): ?>
    <div class="alert alert-success"><?=htmlspecialchars($success)?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
  <?php endif; ?>

  <form method="post" action="profile.php">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="emri" class="form-control" value="<?=htmlspecialchars($user['emri'])?>" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?=htmlspecialchars($user['email'])?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Profile</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
