<?php
session_start();
include_once "config.php";

// Only admins
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch place
$stmt = $conn->prepare("SELECT * FROM places WHERE id = ?");
$stmt->execute([$id]);
$place = $stmt->fetch();

if (!$place) {
    die("Place not found.");
}

$error = '';
$uploadDir = 'uploads/'; // make sure this folder exists and is writable

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['place_name']);
    $desc = trim($_POST['place_desc']);
    $visitors = intval($_POST['visitors']);

    $imagePath = $place['image']; // keep current image by default

    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . time() . "_" . $imageName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Only JPG, PNG, GIF, and WEBP files are allowed.";
        }
    }

    if ($name && $desc && !$error) {
        $stmt = $conn->prepare("UPDATE places SET place_name = ?, place_desc = ?, visitors = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $desc, $visitors, $imagePath, $id]);

        header("Location: dashboard.php");
        exit();
    } elseif (!$error) {
        $error = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Place - Lonely Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Edit Place</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Place Name</label>
            <input type="text" name="place_name" class="form-control" value="<?= htmlspecialchars($place['place_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="place_desc" class="form-control" required><?= htmlspecialchars($place['place_desc']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Visitors</label>
            <input type="number" name="visitors" class="form-control" value="<?= htmlspecialchars($place['visitors']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="<?= htmlspecialchars($place['image']) ?>" alt="Current Image" width="200" class="mb-2"><br>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
