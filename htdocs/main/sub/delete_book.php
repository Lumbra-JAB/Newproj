<?php 
session_start();
include '../database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Book</title>
    <link rel="stylesheet" type="text/css" href="delete.css">
</head>
<body>
    <div class="container">
        <?php
        // Sanitize and validate ISBN
        $bookISBN = filter_input(INPUT_GET, 'isbn', );

        if ($bookISBN) {
            // Use prepared statement for secure deletion
            $sql = "DELETE FROM book WHERE ISBN = ?";
            $stmt = $connection->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("s", $bookISBN);
                
                if ($stmt->execute()) {
                    // Check if any rows were actually deleted
                    if ($stmt->affected_rows > 0) {
                        echo '<div class="message success">Book deleted successfully</div>';
                    } else {
                        echo '<div class="message error">No book found with this ISBN</div>';
                    }
                } else {
                    echo '<div class="message error">Error deleting book: ' . $stmt->error . '</div>';
                }
                
                $stmt->close();
            } else {
                echo '<div class="message error">Preparation error: ' . $connection->error . '</div>';
            }
        } else {
            echo '<div class="message error">Invalid or missing Book ISBN</div>';
        }
        ?>

        <a href="../book.php" class="btn">Back to Books</a>
    </div>
</body>
</html>