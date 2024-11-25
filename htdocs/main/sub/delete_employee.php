<?php 
session_start();
include '../database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Employee</title>
    <link rel="stylesheet" type="text/css" href="delete.css">
</head>
<body>
    <div class="container">
        <?php
        // Sanitize and validate Taxpayer ID using modern method
        $taxpayerID = isset($_GET['TaxpayerID']) ? trim($_GET['TaxpayerID']) : null;

        // Additional validation
        if ($taxpayerID && preg_match('/^[a-zA-Z0-9-]+$/', $taxpayerID)) {
            try {
                // Begin transaction for safer deletion
                $connection->begin_transaction();

                // Prepare statement for deletion
                $sql = "DELETE FROM employee WHERE TaxpayerID = ?";
                $stmt = $connection->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param("s", $taxpayerID);
                    
                    if ($stmt->execute()) {
                        // Check if any rows were actually deleted
                        if ($stmt->affected_rows > 0) {
                            // Commit transaction
                            $connection->commit();
                            
                            echo '<div class="message success">Employee deleted successfully</div>';
                            echo '<div class="details">Taxpayer ID: ' . htmlspecialchars($taxpayerID) . '</div>';
                        } else {
                            // Rollback transaction
                            $connection->rollback();
                            
                            echo '<div class="message error">No employee found with this Taxpayer ID</div>';
                        }
                    } else {
                        // Rollback transaction
                        $connection->rollback();
                        
                        echo '<div class="message error">Error deleting employee: ' . htmlspecialchars($stmt->error) . '</div>';
                    }
                    
                    $stmt->close();
                } else {
                    // Rollback transaction
                    $connection->rollback();
                    
                    echo '<div class="message error">Preparation error: ' . htmlspecialchars($connection->error) . '</div>';
                }
            } catch (Exception $e) {
                // Rollback transaction in case of any exception
                $connection->rollback();
                
                echo '<div class="message error">An unexpected error occurred: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        } else {
            echo '<div class="message error">Invalid or missing Taxpayer ID</div>';
        }
        ?>

        <a href="../employee.php" class="btn">Back to Employees</a>
    </div>
</body>
</html>