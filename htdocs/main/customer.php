<?php 
include 'database.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer - Local Bookstore</title>
</head>
<body>
    <h1>Customers</h1>
    <button><a href="home.php">Home</a></button><br>
    <a href="sub/add_customer.php">Add New Customer</a>
    
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
        // Query to fetch all customers
        $sql = "SELECT * FROM customer";
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
                        <a href="sub/delete_customer.php?customer_id=' . $row['CustomerID'] . '">Delete</a> 
                        <a href="sub/update_customer.php?customer_id=' . $row['CustomerID'] . '">Update</a>
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