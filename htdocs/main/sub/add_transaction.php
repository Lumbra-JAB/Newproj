<?php 
session_start();
include '../database.php';
?>

<?php
    // Function to generate a unique TransactionID
    function generateTransactionID($connection) {
        do {
            $transactionID = str_pad(rand(0, 99999999999), 11, '0', STR_PAD_LEFT); // Generates a unique transaction ID
            // Check if this ID already exists
            $result = $connection->query("SELECT TransactionID FROM salestransaction WHERE TransactionID = '$transactionID'");
        } while ($result->num_rows > 0);
        return $transactionID;
    }

    // Auto-generate TransactionID when the page loads
    $autoTransactionID = generateTransactionID($connection);

    // Load employees and customers for the form
    $employee = $connection->query("SELECT * FROM employee");
    $customer = $connection->query("SELECT * FROM customer");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Sales Transaction - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="books.css">
</head>
<body>
    <h2>Add New Sales Transaction</h2>
    <button><a href="../home.php">Home</a></button>
    <button><a href="../sale_transaction.php">Back</a></button>
    <form method="POST" action="add_transaction.php">
        <label>Transaction ID (Auto-generated):</label><br>
        <input type="text" name="transactionID" value="<?php echo $autoTransactionID; ?>" readonly><br>
        
        <label>Customer:</label><br>
        <select name="customerID" required>
            <option value="">Select a customer</option>
            <?php while($row = $customer->fetch_assoc()) { ?>
                <option value="<?php echo $row['CustomerID']; ?>"><?php echo $row['Name']; ?></option>
            <?php } ?>
        </select><br>

        <label>Employee:</label><br>
        <select name="employeeID" required>
            <option value="">Select an employee</option>
            <?php while($row = $employee->fetch_assoc()) { ?>
                <option value="<?php echo $row['TaxpayerID']; ?>"><?php echo $row['Name']; ?></option>
            <?php } ?>
        </select><br>

        <label>Date:</label><br>
        <input type="date" name="date" required><br>
        
        <label>Time:</label><br>
        <input type="time" name="time" required><br>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" required><br>

        <input type="submit" value="Add Transaction">
    </form>
</body>
</html>