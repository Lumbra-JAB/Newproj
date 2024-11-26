<?php 
session_start();
include '../database.php';


if (!isset($_GET['taxpayer_id']) || empty(trim($_GET['taxpayer_id']))) {
    die("Invalid or missing Taxpayer ID.");
}

$taxpayerID = htmlspecialchars(trim($_GET['taxpayer_id']));



// Fetch existing employee details
$stmt = $connection->prepare("SELECT * FROM employee WHERE TaxpayerID = ?");
$stmt->bind_param("s", $taxpayerID);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    die("Employee not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data directly
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $dateOfBirth = $_POST['dateOfBirth'] ?? '';
    $pseudonym = $_POST['pseudonym'] ?? '';

    // Validate inputs
    $errors = [];
    if (empty($name)) $errors[] = "Name is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (empty($dateOfBirth)) $errors[] = "Date of birth is required.";
    if (empty($pseudonym)) $errors[] = "Pseudonym is required.";

    if (empty($errors)) {
        // Update employee details
        $stmt = $connection->prepare("UPDATE employee SET Name = ?, Address = ?, DateOfBirth = ?, Pseudonym = ? WHERE TaxpayerID = ?");
        $stmt->bind_param("sssss", $name, $address, $dateOfBirth, $pseudonym, $taxpayerID);

        if ($stmt->execute()) {
            $successMessage = "Employee updated successfully.";
            // Refresh employee data
            $employee = [
                'Name' => $name,
                'Address' => $address,
                'DateOfBirth' => $dateOfBirth,
                'Pseudonym' => $pseudonym
            ];
        } else {
            $errors[] = "Update failed: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employee - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="../css/.css">
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
                <label for="pseudonym">Pseudonym:</label>
                <input 
                    type="text" 
                    id="pseudonym" 
                    name="pseudonym" 
                    value="<?php echo htmlspecialchars($employee['Pseudonym']); ?>" 
                    required
                    maxlength="255"
                >
            </div>

            <button type="submit">Update Employee</button>
        </form>

        <a href="../employee.php" class="btn">Back to Employees</a>
    </div>
</body>
</html>
