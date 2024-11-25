<?php 
session_start();
include 'database.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Books - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="books.css">
</head>
<body>
    <header>
        <div class="image">           
            <div class="menu">               
        <a href="home.php">Home</a>
        <h2>Books</h2>
    <table>
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Year</th>
            <th>Publisher</th>
        </tr>
        <?php
        $sql = "SELECT * FROM book";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['ISBN']}</td>
                        <td>{$row['Title']}</td>
                        <td>{$row['Year']}</td>
                        <td>{$row['Publisher']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No books found</td></tr>";
        }
        ?>
    </table>
    <a href="sub/add_book.php">Add New Book</a>
            </div>
        </div>
    </header>
    
</body>
</html>