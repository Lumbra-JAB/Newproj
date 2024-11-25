<?php 
session_start();
include '../database.php';

// Validate Customer ID
$customerID = $_GET['customer_id'] ?? '';

if (empty($customerID)) {
    die("Invalid Customer ID provided");
}

// Fetch existing customer details
$stmt = $connection->prepare("SELECT * FROM customer WHERE CustomerID = ?");
$stmt->bind_param("s", $customerID);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    die("Customer not found");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get inputs directly without sanitization
    $name = $_POST['name'] ?? '';
    $phoneNumber = $_POST['phoneNumber'] ?? '';
    $address = $_POST['address'] ?? '';
    $dateOfBirth = $_POST['dateOfBirth'] ?? '';

    // Validate inputs
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($phoneNumber)) $errors[] = "Phone number is required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($dateOfBirth)) $errors[] = "Date of birth is required";

    if (empty($errors)) {
        try {
            // Prepare update statement
            $updateStmt = $connection->prepare("UPDATE Customer SET Name = ?, PhoneNumber = ?, Address = ?, DateOfBirth = ? WHERE CustomerID = ?");
            $updateStmt->bind_param("ssssi", $name, $phoneNumber, $address, $dateOfBirth, $customerID);
            
            if ($updateStmt->execute()) {
                $successMessage = "Customer updated successfully";
                // Refresh customer data
                $customer = [
                    'Name' => $name,
                    'PhoneNumber' => $phoneNumber,
                    'Address' => $address,
                    'DateOfBirth' => $dateOfBirth
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
    <title>Update Customer - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="../css/delete-styles.css">
</head>
<body>
    <div class="container">
        <h2>Update Customer ID: <?php echo htmlspecialchars($customerID); ?></h2>
        
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
                    value="<?php echo htmlspecialchars($customer['Name']); ?>" 
                    required
                    maxlength="255"
                >
            </div>

            <div class="form-group">
                <label for="phoneNumber">Phone Number:</label>
                <input 
                    type="text" 
                    id="phoneNumber" 
                    name="phoneNumber" 
                    value="<?php echo htmlspecialchars($customer['PhoneNumber']); ?>" 
                    required
                    maxlength="15"
                >
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input 
                    type="text" 
                    id="address" 
                    name="address" 
                    value="<?php echo htmlspecialchars($customer['Address']); ?>" 
                    required
                    maxlength="255"
                >
            </div>

            <div class="form-group">
                <label for="dateOfBirth">Date of Birth:</label>
                <input 
                    type="date" 
                    id="dateOfBirth" 
                    name="date