<?php 
include 'database.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="CSS/function.css">
</head>
<body>
<nav>
    <h1><div class="logo"><b>Local Bookstore</div></b></h1>
    <div class="links">
        <a href="home.php">Home</a>
        <a href="book.php">Books</a>
        <a href="sale_transaction.php">Sales Transactions</a>
        <a href="customer.php">Customers</a>
        <a href="employee.php">Employees</a>
    </div>
</nav>

   
    
    <a href="sub/add_customer.php" class="button add-button">Add New Customer</a>
     <h2>Customers</h2>
    <table border="1">
        <tr>
            <th>CustomerID</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Date of Birth</th>
            <th>Actions</th>
        </tr>
        <?php
        $sql = "SELECT * FROM customer ORDER BY Name ASC";  
        $result = $connection->query($sql);

        // Check if there are any customers
        if ($result->num_rows > 0) {
            // Loop through each customer
            while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['CustomerID'] . '</td>';
                echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['PhoneNumber']) . '</td>';
                echo '<td>' . $row['Address'] . '</td>';
                echo '<td>' . $row['DateOfBirth'] . '</td>';
                echo '<td>
                        <a href="sub/delete_customer.php?customer_id=' . $row['CustomerID'] . '"class="button delete-button">Delete</a> 
                        <a href="sub/update_customer.php?customer_id=' . $row['CustomerID'] . '"class="button update-button">Update</a>
                      </td>';
                echo '</tr>';
            }
        } else {
            // If no customers found
            echo '<tr><td colspan="6">No customers found</td></tr>';
        }
        ?>
    </table>

    
</body>
</html>

<?php
// Close database connection
$connection->close();
?>