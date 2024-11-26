<?php 
session_start();
include '../database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Customer</title>
    <link rel="stylesheet" type="text/css" href="../CSS/delete.css">
</head>
<body>
    <div class="container">
        <?php
        $customerID = $_GET['customer_id'] ?? '';

        if ($customerID) {
            // Delete the customer
            $sql = "DELETE FROM customer WHERE CustomerID=?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("s", $customerID);

            if ($stmt->execute()) {
                echo '<div class="message success">Customer deleted successfully</div>';
            } else {
                echo '<div class="message error">Error: ' . $connection->error . '</div>';
            }
            $stmt->close();
        } else {
            echo '<div class="message error">Customer ID not specified.</div>';
        }
        ?>

        <a href="../customer.php" class="btn">Back to Customers</a>
    </div>
</body>
</html>