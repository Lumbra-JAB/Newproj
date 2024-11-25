<?php
session_start();
include 'database.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>SalesTransaction - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="books.css">
</head>
<body>
    <header>
        <div class="image">
            <div class="menu">
    
        <h2>SalesTransaction</h2>
    <table>
        <tr>
            <th>TransactionID</th>
            <th>CustomerID</th>
            <th>EmployeeID</th>
            <th>Date</th>
            <th>Time</th>
            <th>Quantity</th>
        </tr>
        <?php
            $sql = "SELECT * FROM salestransaction";
            $result = $connection->query(query: $sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['TransactionID']}</td>
                            <td>{$row['CustomerID']}</td>
                            <td>{$row['EmployeeID']}</td>
                            <td>{$row['Date']}</td>
                            <td>{$row['Time']}</td>
                            <td>{$row['Quantity']}</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No Transaction found</td></tr>";
            }
        ?>
    </table>
    <a href="sub/add_transaction.php">Add New Sales Transaction</a>
    <a href="book_transaction.php">See Transaction Books</a>
            </div>
        </div>
    </header>
    
</body>
</html>