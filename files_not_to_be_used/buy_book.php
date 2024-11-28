<?php
session_start();
include 'database.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Fetch book details from the database
    $sql = "SELECT * FROM book WHERE ISBN = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('s', $isbn);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book) {
        // Display the book details and a form to complete the purchase
        echo "<h2>Buy Book</h2>";
        echo "<p>Title: " . htmlspecialchars($book['Title']) . "</p>";
        echo "<p>Price: $" . htmlspecialchars($book['Price']) . "</p>";
        echo "<form action='complete_purchase.php' method='POST'>
                <input type='hidden' name='isbn' value='" . htmlspecialchars($book['ISBN']) . "'>
                <label for='quantity'>Quantity:</label>
                <input type='number' name='quantity' min='1' value='1'>
                <button type='submit'>Complete Purchase</button>
              </form>";
    } else {
        echo "<p>Book not found.</p>";
    }
} else {
    echo "<p>No book selected.</p>";
}
?>
