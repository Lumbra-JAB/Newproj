<?php
session_start() ;
include '../database.php'; ?>

<?php
$transactionID = $_GET['transactionID'] ?? '';

if ($transactionID) {
    // Delete the sales transaction
    $sql = "DELETE FROM salestransaction WHERE TransactionID='$transactionID'";

    if ($connection->query($sql) === TRUE) {
        echo "Sales transaction deleted successfully";
    } else {
        echo "Error: " . $connection->error;
    }
} else {
    echo "Transaction ID not specified.";
}
?>

<a href="../sale_transaction.php">Back to Transactions</a>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Employee</title>
    <link rel="stylesheet" type="text/css" href="../CSS/delete.css">
</head>
<body></body>

</html>