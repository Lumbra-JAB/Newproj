<?php
session_start();
include 'database.php'; // Start session to access user information

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: home.php"); // Redirect to home.php if not an admin
    exit;
}

// Admin is valid; redirect to home.php
header("Location: home.php");
exit;
?>
