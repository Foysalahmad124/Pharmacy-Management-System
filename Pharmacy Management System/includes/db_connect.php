<?php
session_start();

// ইউজার লগইন চেক
if (!isset($_SESSION['username'])) {
    header("Location: /JP/login.php");
    exit();
}

// সব সময় এই ডাটাবেস ব্যবহার হবে
$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'jppharmacy';

// DB সংযোগ
$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: session variable ধরে রাখলেও হবে
$_SESSION['database_name'] = $db_name;
?>