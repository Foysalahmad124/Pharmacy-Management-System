<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: /JP/login.php");
    exit();
}

// Database connection settings
$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'jppharmacy'; // 

// Connect to database
$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Database Connection Error: " . htmlspecialchars($db_name) . " - " . $conn->connect_error);
}

// Optional: session variable 
$_SESSION['database_name'] = $db_name; // future use
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= isset($_SESSION['shop_name']) ? htmlspecialchars($_SESSION['shop_name']) : "Pharmacy"; ?></title>
    <link rel="icon" type="image/x-icon" href="/JP/images/janata.ico">
    <link rel="stylesheet" href="/JP/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <h1><?= isset($_SESSION['shop_name']) ? htmlspecialchars($_SESSION['shop_name']) : "Pharmacy Management"; ?></h1>
    <?php if (isset($_SESSION['username'])): ?>
        <div class="welcome">
            <p>Welcome, <strong><?= isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : htmlspecialchars($_SESSION['username']); ?></strong> (<?= htmlspecialchars($_SESSION['role']); ?>) | <a href="/JP/logout.php" style="color:white; text-decoration:underline;">Logout</a></p>
        </div>
    <?php endif; ?>
</header>
<main>