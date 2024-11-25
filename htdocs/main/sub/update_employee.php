<?php 
session_start();
include '../database.php';

// Validate and sanitize Taxpayer ID
$taxpayerID = filter_input(INPUT_GET, 'taxpayerID', FILTER_SANITIZE_STRING);

if (!$taxpayerID) {
    die("Invalid Taxpayer ID provided");
}

// Fetch existing employee details
$stmt = $connection->prepare("SELECT * FROM Employee WHERE TaxpayerID = ?");
$stmt->bind_param("s", $taxpayerID);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    die("Employee not found");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $dateOfBirth = filter_input(INPUT_POST, 'dateOfBirth', FILTER_SANITIZE_STRING);
    $pseudonym = filter_input(INPUT_POST, 'pseudonym', FILTER_SANITIZE_STRING);

    // Validate inputs
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($dateOfBirth)) $errors[] = "Date of birth is required";
    if (empty($pseudonym)) $errors[] = "Pseudonym is required";

    if (empty($errors)) {
        try {
            // Prepare update statement
            $updateStmt = $connection->prepare("UPDATE Employee SET Name = ?, Address = ?, DateOfBirth = ?, Pseudonym = ? WHERE TaxpayerID = ?");
            $updateStmt->bind_param("ssssi", $name, $address, $dateOfBirth, $pseudonym, $taxpayerID);
            
            if ($updateStmt->execute()) {
                $successMessage = "Employee updated successfully";
                // Refresh employee data
                $employee = [
                    'Name' => $name,
                    'Address' => $address,
                    'DateOfBirth' => $dateOfBirth,
                    'Pseudonym' => $pseudonym
                ];
            } else {
                $errors[] = "Update failed: " . $updateStmt->error;
            }
            $updateStmt->close();
        } catch (Exception $e) {
            $errors[] = "An error occurred: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employee - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="../css/delete-styles.css">
</head>
<body>
    <div class="container">
        <h2>Update Employee ID: <?php echo htmlspecialchars($taxpayerID); ?></h2>
        
        <?php 
        // Display errors
        if (!empty($errors)) {
            echo '<div class="error-message">';
            foreach ($errors as $error) {
                echo '<p>' . htmlspecialchars($error) . '</p>';
            }
            echo '</div>';
        }

        // Display success message
        if (isset($successMessage)) {
            echo '<div class="success-message">' . htmlspecialchars($successMessage) . '</div>';
        }
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="<?php echo htmlspecialchars($employee['Name']); ?>" 
                    required
                    maxlength="255"
                >
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input 
                    type="text" 
                    id="address" 
                    name="address" 
                    value="<?php echo htmlspecialchars($employee['Address']); ?>" 
                    required
                    maxlength="255"
                >
            </div>

            <div class="form-group">
                <label for="dateOfBirth">Date of Birth:</label>
                <input 
                    type="date" 
                    id="dateOfBirth" 
                    name="dateOfBirth" 
                    value="<?php echo htmlspecialchars($employee['DateOfBirth']); ?>" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="pseud