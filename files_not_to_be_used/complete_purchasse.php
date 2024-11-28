<?php
session_start();
include 'database.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isbn'], $_POST['quantity'])) {
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];
    $customerId = $_SESSION['username']; // Assuming username is the customer ID

    // Get the current date and time
    $date = date('Y-m-d');
    $time = date('H:i:s');

    // Insert the purchase details into the transaction table
    $sql = "INSERT INTO salestransaction (CustomerID, ISBN, Quantity, Date, Time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('ssiss', $customerId, $isbn, $quantity, $date, $time);

    if ($stmt->execute()) {
        echo "<p>Thank you for your purchase! Your transaction has been completed.</p>";
    } else {
        echo "<p>Error completing the purchase. Please try again.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
