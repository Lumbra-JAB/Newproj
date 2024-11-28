<?php
session_start();
include 'database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>SalesTransaction - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="CSS/function.css">
</head>
<body>
<nav>
    <h1><div class="logo"><b>Local Bookstore</div></h1>
    <div class="links">
        <a href="home.php">Home</a>
        <a href="book.php">Books</a>
        <a href="sale_transaction.php">Sales Transactions</a>
        <a href="customer.php">Customers</a>
        <a href="employee.php">Employees</a>
    </div>
</nav>

    
<a href="sub/add_transaction.php" class="button add-button">Add New Sales Transaction</a>
                

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
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['TransactionID']}</td>
            <td>{$row['CustomerID']}</td>
            <td>{$row['TaxpayerID']}</td>
            <td>{$row['Date']}</td>
            <td>{$row['Time']}</td>
            <td>{$row['Quantity']}</td>
            <td>
                <a href='sub/update_transaction.php?transactionID={$row['TransactionID']}' class='button update-button'>Update</a>
                <a href='sub/delete_transaction.php?transactionID={$row['TransactionID']}' class='button delete-button'>Delete</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No transactions found</td></tr>";
}
?>

                    
                </table>
                <br>
                
            </div>
        </div>
    </header>
</body>
</html>
