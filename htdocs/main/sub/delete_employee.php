<?php 
session_start();
include '../database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Employee</title>
    <link rel="stylesheet" type="text/css" href="../CSS/delete.css">
</head>
<body>
    <div class="container">
        <?php
        $taxpayerID = $_GET['taxpayer_id'] ?? '';

        if ($taxpayerID) {
            // Prepare and execute the deletion query
            $sql = "DELETE FROM employee WHERE TaxpayerID = ?";
            $stmt = $connection->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("s", $taxpayerID);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        echo '<div class="message success">Employee deleted successfully</div>';
                    } else {
                        echo '<div class="message error">No employee found with this Taxpayer ID</div>';
                    }
                } else {
                    echo '<div class="message error">Error: ' . htmlspecialchars($stmt->error) . '</div>';
                }

                $stmt->close();
            } else {
                echo '<div class="message error">Error preparing statement: ' . htmlspecialchars($connection->error) . '</div>';
            }
        } else {
            echo '<div class="message error">Taxpayer ID not specified.</div>';
        }
        ?>

        <a href="../employee.php" class="btn">Back to Employees</a>
    </div>
</body>
</html>
