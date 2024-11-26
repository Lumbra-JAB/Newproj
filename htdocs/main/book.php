<?php 
session_start();
include 'database.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Books - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="../CSS/function.css">
</head>
<body>
    <button><a href="home.php">Home</a></button>
    <header>
        <h2>Books</h2>
        <div class="books-container">
            <?php
            $sql = "SELECT * FROM book ORDER BY Title ASC";  
            $result = $connection->query($sql);

            // Error handling for the query
            if ($result === false) {
                echo "<p>Error executing query: " . $connection->error . "</p>";
            } else {
                // Check if there are books to display
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="book-item">
                            <img src="' . 
                                ((!empty($row['Image']) && $row['Image'] !== null) 
                                    ? htmlspecialchars($row['Image']) 
                                    : 'path/to/default/image.jpg') 
                            . '" alt="' . htmlspecialchars($row['Title']) . '">
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
        <a href="sub/add_book.php">Add New Book</a>
    </header>
</body>
</html>
