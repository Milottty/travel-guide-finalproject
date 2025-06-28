<?php
session_start();
include_once "config.php";

$username = $_SESSION['username'] ?? 'Guest';
$profileImage = $_SESSION['profile_image'] ?? 'img/default.png';
$userId = $_SESSION['id'] ?? 0;

// Try fetching destinations from DB
try {
    $stmt = $conn->prepare("SELECT * FROM places ORDER BY visitors DESC LIMIT 6");
    $stmt->execute();
    $destinations = $stmt->fetchAll();
} catch (PDOException $e) {
    $destinations = [];
}

// Fallback sample destinations if DB is empty
if (empty($destinations)) {
    $destinations = [
        [
            'id' => 1,
            'place_name' => 'Rome, Italy',
            'place_desc' => 'Rome is a timeless city with ancient ruins, charming piazzas, and mouthwatering Italian cuisine.',
            'place_image' => 'Rome.avif',
            'visitors' => 5000,
        ],
        [
            'id' => 2,
            'place_name' => 'Tokyo, Japan',
            'place_desc' => 'Tokyo offers an exhilarating blend of ultramodern skyscrapers and historic temples.',
            'place_image' => 'Tokyo.jpg',
            'visitors' => 7000,
        ],
        [
            'id' => 3,
            'place_name' => 'Paris, France',
            'place_desc' => 'Paris, the City of Lights, is renowned for its art, fashion, and romantic ambiance.',
            'place_image' => 'Parking-paris.webp',
            'visitors' => 6500,
        ],
        [
            'id' => 4,
            'place_name' => 'New York, USA',
            'place_desc' => 'The city that never sleeps offers iconic landmarks like Times Square and Central Park.',
            'place_image' => 'newyork.jpg',
            'visitors' => 8000,
        ],
        [
            'id' => 5,
            'place_name' => 'Sydney, Australia',
            'place_desc' => 'Sydney is famous for its stunning harbor, Opera House, and beautiful beaches.',
            'place_image' => 'sydney.jpg',
            'visitors' => 4000,
        ],
        [
            'id' => 6,
            'place_name' => 'Cape Town, South Africa',
            'place_desc' => 'Nestled between mountains and sea, Cape Town offers breathtaking views and wildlife safaris.',
            'place_image' => 'capetown.jpg',
            'visitors' => 3500,
        ],
    ];
}

// Fetch cart count for navbar
$cartCount = 0;
if ($userId) {
    $stmt = $conn->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    $cartCount = $stmt->fetchColumn() ?: 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lonely Travel - Explore the World</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="icon" href="img/download-removebg-preview.png" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />

    <style>
        body {
            background-color: #e6f2ff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand span {
            color: #000080;
        }
        .card img {
            object-fit: cover;
            height: 180px;
            border-radius: 0.375rem 0.375rem 0 0;
        }
        .destination-title {
            font-weight: 600;
            font-size: 1.25rem;
        }
        .card:hover {
            box-shadow: 0 0 15px rgba(0, 0, 128, 0.3);
            transform: translateY(-5px);
            transition: 0.3s ease;
        }
        .profile-img {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 50%;
        }
        footer {
            background-color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: auto;
            border-top: 1px solid #ddd;
            font-size: 0.9rem;
            color: #555;
        }
        .nav-link.active {
            font-weight: 600;
            color: #000080 !important;
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2 px-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">
                Lonely <span>Travel</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Destinations</a></li>
                    <li class="nav-item"><a class="nav-link" href="planning.php">Planning</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link text-primary" href="dashboard.php">Admin Dashboard</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <a href="cart.php" class="nav-link position-relative" title="Cart">
                            <i class="ri-shopping-cart-line" style="font-size: 1.5rem;"></i>
                            <?php if ($cartCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $cartCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="watchlist.php" class="nav-link">
                            <i class="ri-heart-line" style="font-size: 1.5rem;"></i> Watchlist
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="profile-img me-2" /> <?= htmlspecialchars($username) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <?php if ($userId): ?>
                                <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                                <li><a class="dropdown-item" href="user_dashboard.php">Profile</a></li>
                                <li><a class="dropdown-item" href="watchlist.php">Watchlist</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('Logout?')">Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="signin.php">Login</a></li>
                                <li><a class="dropdown-item" href="signup.php">Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="py-5 text-center bg-white mb-4 shadow-sm">
        <div class="container">
            <h1 class="fw-bold mb-3">Explore Amazing Destinations</h1>
            <p class="lead text-muted mb-0">Discover your next adventure with Lonely Travel</p>
        </div>
    </section>

    <!-- DESTINATIONS GRID -->
    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            <?php foreach ($destinations as $dest):
                $image = !empty($dest['image']) ? '' . htmlspecialchars($dest['image']) : 'img/default.png';
            ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="<?= $image ?>" class="card-img-top" alt="<?= htmlspecialchars($dest['place_name']) ?>" />
                    <div class="card-body d-flex flex-column">
                        <h5 class="destination-title mb-2"><?= htmlspecialchars($dest['place_name']) ?></h5>
                        <p class="text-muted flex-grow-1"><?= htmlspecialchars($dest['place_desc']) ?></p>
                        <div class="mt-3">
                            <a href="add_to_watchlist.php?item_type=destination&item_id=<?= $dest['id'] ?>" class="btn btn-outline-danger w-100">
                                ❤️ Save to Watchlist
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p class="mb-0 text-muted">© 2025 Lonely Travel. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
