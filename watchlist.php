<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['id'];
$username = $_SESSION['username'];
$profileImage = $_SESSION['profile_image'] ?? 'img/default.png';

// Load watchlist
try {
    $stmt = $conn->prepare("SELECT * FROM watchlist WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $watchlistItems = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error loading watchlist: " . $e->getMessage());
}

// Load product/destination details
$details = [];
foreach ($watchlistItems as $item) {
    if ($item['item_type'] === 'product') {
        $q = $conn->prepare("SELECT * FROM products WHERE id = ?");
    } else {
        $q = $conn->prepare("SELECT * FROM places WHERE id = ?");
    }
    $q->execute([$item['item_id']]);
    $details[] = $q->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Watchlist</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include "navbar.php"; ?>

<div class="container my-5">
  <h1 class="fw-bold mb-4">My Watchlist</h1>
  <?php if (empty($details)): ?>
    <p class="text-muted">Your watchlist is empty.</p>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($details as $item): ?>
      <div class="col">
        <div class="card h-100">
          <img src="<?= htmlspecialchars($item['image'] ?? $item['place_image']) ?>" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($item['name'] ?? $item['place_name']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($item['description'] ?? $item['place_desc']) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
