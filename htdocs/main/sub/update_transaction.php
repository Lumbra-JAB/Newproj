<?php
session_start();
include '../database.php'; // Ensure the database connection is properly included

// Check if transaction ID is set in the URL
$transactionID = $_GET['transactionID'] ?? '';

if (!$transactionID) {
    die("Transaction ID is missing.");
}

// Load employees and customers for the form
$employees = $connection->query("SELECT * FROM employee");
$customers = $connection->query("SELECT * FROM customer");

// Load the existing transaction details
$transaction = $connection->query("SELECT * FROM salestransaction WHERE TransactionID='$transactionID'")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $customerID = $_POST['customerID'];
    $taxpayerID = $_POST['taxpayerID'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $quantity = $_POST['quantity'];

    // Prepare and execute the update query
    $stmt = $connection->prepare("UPDATE salestransaction 
                            SET CustomerID=?, TaxpayerID=?, Date=?, Time=?, Quantity=? 
                            WHERE TransactionID=?");
    $stmt->bind_param("sssssi", $customerID, $taxpayerID, $date, $time, $quantity, $transactionID);

    if ($stmt->execute()) {
        echo "Sales transaction updated successfully.";
    } else {
        echo "Error updating transaction: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Sales Transaction - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>Update Sales Transaction ID: <?php echo htmlspecialchars($transactionID); ?></h2>
    
    <form method="POST" action="">
        <!-- Customer Dropdown -->
        <label>Customer:</label><br>
        <select name="customerID" required>
            <option value="">Select a customer</option>
            <?php while ($row = $customers->fetch_assoc()) { ?>
                <option value="<?php echo $row['CustomerID']; ?>" 
                    <?php echo ($row['CustomerID'] == $transaction['CustomerID']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['Name']); ?>
                </option>
            <?php } ?>
        </select><br>

        <!-- Employee Dropdown -->
        <label>Employee:</label><br>
        <select name="employeeID" required>
            <option value="">Select an employee</option>
            <?php while ($row = $employees->fetch_assoc()) { ?>
                <option value="<?php echo $row['TaxpayerID']; ?>" 
                    <?php echo ($row['TaxpayerID'] == $transaction['TaxpayerID']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['Name']); ?>
                </option>
            <?php } ?>
        </select><br>

        <!-- Date Input -->
        <label>Date:</label><br>
        <input type="date" name="date" value="<?php echo $transaction['Date']; ?>" required><br>
        
        <!-- Time Input -->
        <label>Time:</label><br>
        <input type="time" name="time" value="<?php echo $transaction['Time']; ?>" required><br>

        <!-- Quantity Input -->
        <label>Quantity:</label><br>
        <input type="number" name="quantity" value="<?php echo $transaction['Quantity']; ?>" required><br>

        <!-- Submit Button -->
        <input type="submit" value="Update Transaction">
    </form>
</body>
</html>
