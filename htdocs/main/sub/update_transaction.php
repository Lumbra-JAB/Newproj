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
    // Sanitize and validate form data
    $customerID = $_POST['customerID'];
    $employeeID = $_POST['employeeID'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $quantity = $_POST['quantity'];

    // Prepare and execute the update query
    $stmt = $connection->prepare("UPDATE salestransaction 
                                  SET CustomerID=?, TaxpayerID=?, Date=?, Time=?, Quantity=? 
                                  WHERE TransactionID=?");
    $stmt->bind_param("sssssi", $customerID, $employeeID, $date, $time, $quantity, $transactionID);

    if ($stmt->execute()) {
        echo "<p>Sales transaction updated successfully.</p>";
    } else {
        echo "<p>Error updating transaction: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Sales Transaction - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="../CSS/transac.css">
</head>
<body>

    <div class="container">
        <h2>Update Sales Transaction ID: <?php echo htmlspecialchars($transactionID); ?></h2>
        
        <form method="POST" action="">

            <!-- Customer Dropdown -->
            <label for="customerID">Customer:</label>
            <select name="customerID" id="customerID" required>
                <option value="">Select a customer</option>
                <?php while ($row = $customers->fetch_assoc()) { ?>
                    <option value="<?php echo $row['CustomerID']; ?>" 
                        <?php echo ($row['CustomerID'] == $transaction['CustomerID']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['Name']); ?>
                    </option>
                <?php } ?>
            </select><br>

            <!-- Employee Dropdown -->
            <label for="employeeID">Employee:</label>
            <select name="employeeID" id="employeeID" required>
                <option value="">Select an employee</option>
                <?php while ($row = $employees->fetch_assoc()) { ?>
                    <option value="<?php echo $row['TaxpayerID']; ?>" 
                        <?php echo ($row['TaxpayerID'] == $transaction['TaxpayerID']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['Name']); ?>
                    </option>
                <?php } ?>
            </select><br>

            <!-- Date Input -->
            <label for="date">Date:</label>
            <input type="date" name="date" id="date" value="<?php echo $transaction['Date']; ?>" required><br>

            <!-- Time Input -->
            <label for="time">Time:</label>
            <input type="time" name="time" id="time" value="<?php echo $transaction['Time']; ?>" required><br>

            <!-- Quantity Input -->
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="<?php echo $transaction['Quantity']; ?>" required><br>

            <!-- Submit Button -->
            <input type="submit" value="Update Transaction">

        </form>
    </div>

</body>
</html>
