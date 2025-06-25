<?php
session_start();
include_once "config.php";
include_once "header.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: user_dashboard.php");
    exit();
}

// Sanitize session values
$username = htmlspecialchars($_SESSION['username']);
$profileImage = isset($_SESSION['profile_image']) && !empty($_SESSION['uploads/defalt.php'])
    ? htmlspecialchars($_SESSION['profile_image'])
    : 'img/default.png';

// Fetch users
$sql = "SELECT * FROM users";
$getUsers = $conn->prepare($sql);
$getUsers->execute();
$users = $getUsers->fetchAll();
?>

<style>
    nav {
        position: relative;
        z-index: 9999;
    }

    .nav-item.dropdown {
        position: relative;
        z-index: 9999;
    }

    .dropdown-menu {
        background-color: #fff !important;
        color: #000 !important;
        border: 1px solid #000 !important;
        z-index: 9999 !important;
    }

    .dropdown-menu .dropdown-item {
        color: #000 !important;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f0f0f0 !important;
        color: #000 !important;
    }

    .container,
    .table-responsive {
        position: relative;
        z-index: 1;
    }
</style>

<!-- IDENTICAL NAVBAR TO index.php -->
<nav>
    <div class="nav-header">
        <div class="nav-logo">
            <a href="#">Lonely <span>Travel</span></a>
        </div>
        <div class="nav-menu-btn" id="menu-btn">
            <span><i class="ri-menu-line"></i></span>
        </div>
    </div>

    <ul class="nav-links" id="nav-links">
        <li><a href="index.php" class="nav-link">Destinations</a></li>
        <li><a href="planning.php" class="nav-link">Planning</a></li>
        <li><a href="shop.php" class="nav-link">Shop</a></li>
    </ul>

    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center text-white"
               href="#"
               id="userDropdown"
               data-bs-toggle="dropdown"
               aria-expanded="false">
                <img src="<?= $profileImage ?>" width="30" height="30" class="rounded-circle me-2" alt="Profile" />
                <span><?= $username ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                <li><a class="dropdown-item" href="user_dashboard.php">Profile</a></li>
                <li><a class="dropdown-item" href="watchlist.php">WatchList</a></li>
                <li><a class="dropdown-item text-primary" href="dashboard.php">Dashboard</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                    <a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<!-- ADMIN DASHBOARD CONTENT -->
<div class="container mt-5">
    <h1 class="mb-4 text-primary text-center">Admin User Dashboard</h1>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary text-center">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['emri']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td class="text-center">
                            <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                            <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Update</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php"; ?>
