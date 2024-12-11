<?php
session_start();
include '../database.php'; // Ensure the database connection is properly included

// Check if transaction ID is set in the URL
$transactionID = $_GET['transactionID'] ?? '';

if (!$transactionID) {
    die("Transaction ID is missing.");
}

$customers = $connection->query("SELECT * FROM customer");

// Load the existing transaction details
$transaction = $connection->query("SELECT * FROM salestransaction WHERE TransactionID='$transactionID'")->fetch_assoc();

$successMessage = ""; // Variable to hold the success message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data
    $customerID = $_POST['customerID'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $quantity = $_POST['quantity'];

    // Prepare and execute the update query
    $stmt = $connection->prepare("UPDATE salestransaction 
                                  SET CustomerID=?, Date=?, Time=?, Quantity=? 
                                  WHERE TransactionID=?");
    $stmt->bind_param("ssssi", $customerID, $date, $time, $quantity, $transactionID);

    if ($stmt->execute()) {
        $successMessage = "Transaction updated successfully.";
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
    <link rel="stylesheet" type="text/css" href="../CSS/update.css">
</head>
<body>
    <div class="container">
        <h2>Update Sales Transaction ID: <?php echo htmlspecialchars($transactionID); ?></h2>
        
        <!-- Display success message -->
        <?php if (!empty($successMessage)) { ?>
            <div class="success-message">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php } ?>

        <form method="POST" action="">
            <!-- Customer Dropdown -->
            <div class="form-group">
                <label for="customerID">Customer:</label>
                <select name="customerID" id="customerID" required>
                    <option value="">Select a customer</option>
                    <?php while ($row = $customers->fetch_assoc()) { ?>
                        <option value="<?php echo $row['CustomerID']; ?>" 
                            <?php echo ($row['CustomerID'] == $transaction['CustomerID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['Name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Date Input -->
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" value="<?php echo $transaction['Date']; ?>" required>
            </div>

            <!-- Time Input -->
            <div class="form-group">
                <label for="time">Time:</label>
                <input type="time" name="time" id="time" value="<?php echo $transaction['Time']; ?>" required>
            </div>

            <!-- Quantity Input -->
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" value="<?php echo $transaction['Quantity']; ?>" required>
            </div>

            <!-- Submit Button -->
            <input type="submit" value="Update Transaction" class="btn">
            <a href="../sale_transaction.php" class="btn">Back to Transactions</a>
        </form>
    </div>
</body>
</html>
