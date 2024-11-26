<?php
session_start();
include 'database.php';

// Check if database connection is established
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Pagination setup
try {
    // Count total records
    $countQuery = "SELECT COUNT(*) as count FROM employee";
    $result = mysqli_query($connection, $countQuery);

    $totalRecords = $result->fetch_assoc()['count'];
    // Pagination variables
    $recordsPerPage = 10;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $recordsPerPage;
    $totalPages = ceil($totalRecords / $recordsPerPage);

    // Fetch employees with pagination
    $stmt = $connection->prepare("SELECT * FROM employee LIMIT ? OFFSET ?");
    
    $stmt->bind_param("ii", $recordsPerPage, $offset);
    
    if (!$stmt->execute()) {
        throw new Exception("Error executing query: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
} catch (Exception $e) {
    // Log the error and show a user-friendly message
    error_log($e->getMessage());
    $errorMessage = "An error occurred while retrieving employees. Please try again later.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employees - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="../CSS/function.css">
</head>
<body>
     
    <div class="container">
        <header>
            <nav>
                <a href="sub/add_employee.php">Add New Employee</a>
            </nav>

            <h2>Employees</h2>
            <button><a href="home.php">Home</a></button>
            <?php if (isset($errorMessage)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php else: ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Taxpayer ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Date of Birth</th>
                            <th>Pseudonym</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM employee ORDER BY Name ASC";  
                        $result = $connection->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['TaxpayerID']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Address']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['DateOfBirth']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Pseudonym']) . '</td>';
                                echo '<td>
                                    <a href="sub/delete_employee.php?taxpayer_id=' . $row['TaxpayerID'] . '">Delete</a> 
                                    <a href="sub/update_employee.php?taxpayer_id=' . $row['TaxpayerID'] . '">Update</a> 
                                </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="6">No employees found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

                
            <?php endif; ?>
        </header>
    </div>

    <?php
    // Close database connection
    if (isset($stmt)) {
        $stmt->close();
    }
    $connection->close();
    ?>
</body>
</html>