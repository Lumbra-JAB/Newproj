<?php
session_start(); // Start session to access user information
include 'database.php';
// Check if the user is logged in and is a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: home.php"); // Redirect to home.php if not a customer
    exit;
}

// Customer is valid; redirect to home.php
header("Location: home.php");
exit;
?>
