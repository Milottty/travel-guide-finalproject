<?php
session_start();
include_once "config.php";

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: signinn.php");
    exit();
}

// Prices per destination
$prices = [
    'Rome' => 150,
    'Tokyo' => 200,
    'Paris' => 120,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'] ?? null; // Assuming user id stored in session as 'id'
    $destination = $_POST['destination'] ?? '';
    $travel_date = $_POST['travel_date'] ?? '';
    $tickets = intval($_POST['tickets'] ?? 0);

    // Validate inputs
    if (!$user_id || empty($destination) || empty($travel_date) || $tickets < 1) {
        $_SESSION['error'] = "Please fill in all required fields correctly.";
        header("Location: planning.php");
        exit();
    }

    if (!isset($prices[$destination])) {
        $_SESSION['error'] = "Invalid destination selected.";
        header("Location: planning.php");
        exit();
    }

    $total_price = $prices[$destination] * $tickets;
    $created_at = date('Y-m-d H:i:s');

    try {
        // Use your config PDO connection variables ($dsn, $user, $pass, $options)
        $pdo = new PDO($dsn, $user, $pass, $options);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Assuming you have a bookings table with these columns:
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, destination, travel_date, tickets, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $destination, $travel_date, $tickets, $total_price, $created_at]);

        $_SESSION['success'] = "Booking successful for $destination on $travel_date. Total price: $$total_price";
        header("Location: planning.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Error saving booking: " . $e->getMessage();
        header("Location: planning.php");
        exit();
    }
} else {
    header("Location: planning.php");
    exit();
}
