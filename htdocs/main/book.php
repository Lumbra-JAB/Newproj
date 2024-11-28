<?php 
session_start();
include 'database.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Books - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="CSS/for_book.css"> <!-- Reference to books-specific CSS -->
</head>
<body>
<nav>
    <h1><div class="logo"><b>Local Bookstore</b></div></h1>
    <div class="links">
        <a href="home.php">Home</a>
        <a href="book.php">Books</a>
        <a href="sale_transaction.php">Sales Transactions</a>
        <a href="customer.php">Customers</a>
        <a href="employee.php">Employees</a>
    </div>
</nav>


<a href="sub/add_book.php">Add New Book</a>
<header>
    <h2>Books</h2>
    <div class="books-container">
        <?php
        $sql = "SELECT * FROM book ORDER BY Title ASC";  
        $result = $connection->query($sql);

        // Error handling for the query
        if ($result === false) {
            echo "<p>Error executing query: " . htmlspecialchars($connection->error) . "</p>";
        } else {
            // Check if there are books to display
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Set the image path or a default placeholder
                    $imagePath = !empty($row['Image']) && file_exists($row['Image']) ? htmlspecialchars($row['Image']) : 'uploads/default_image.jpg';

                    echo '
                    <div class="book-item">
                        <img src="' . $imagePath . '" alt="' . htmlspecialchars($row['Title']) . '" style="width: 150px; height: auto;">
                        <div class="book-info">
                            <p><strong>ISBN:</strong> ' . htmlspecialchars($row['ISBN']) . '</p>
                            <p><strong>Title:</strong> ' . htmlspecialchars($row['Title']) . '</p>
                            <p><strong>Year:</strong> ' . htmlspecialchars($row['Year']) . '</p>
                            <p><strong>Publisher:</strong> ' . htmlspecialchars($row['Publisher']) . '</p>
                            <a href="sub/delete_book.php?isbn=' . urlencode($row['ISBN']) . '">Delete</a>
                            <a href="sub/update_book.php?isbn=' . urlencode($row['ISBN']) . '">Update</a>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>No books found.</p>";
            }
        }
        ?>
    </div>
</header>
</body>
</html>
