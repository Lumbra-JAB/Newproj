<?php
session_start();
include 'database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>SalesTransaction - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="../CSS/function.css">
</head>
<body>
    <button><a href="home.php">Home</a></button>
    <header>
        <div class="image">
            <div class="menu">
                <h2>Sales Transaction</h2>
                <table border="1">
                    <tr>
                        <th>TransactionID</th>
                        <th>CustomerID</th>
                        <th>TaxpayerID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    // Retrieve all sales transactions
                    $sql = "SELECT * FROM salestransaction";
                    $result = $connection->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['TransactionID']}</td>
                                <td>{$row['CustomerID']}</td>
                                <td>{$row['TaxpayerID']}</td>
                                <td>{$row['Date']}</td>
                                <td>{$row['Time']}</td>
                                <td>{$row['Quantity']}</td>
                                <td>
                                    <a href='sub/update_transaction.php?transactionID={$row['TransactionID']}'>Update</a> |
                                    <a href='sub/delete_transaction.php?transactionID={$row['TransactionID']}'>Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No transactions found</td></tr>";
                    }
                    ?>
                </table>
                <br>
                <a href="sub/add_transaction.php">Add New Sales Transaction</a>
                <a href="book_transaction.php">See Transaction Books</a>
            </div>
        </div>
    </header>
</body>
</html>
