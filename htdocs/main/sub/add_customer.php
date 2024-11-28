<?php
session_start();
include '../database.php'; // Database connection

// Feedback message initialization
$feedback = '';

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input values
    $name = htmlspecialchars(trim($_POST['name']));
    $phone_number = htmlspecialchars(trim($_POST['phoneNumber']));
    $address = htmlspecialchars(trim($_POST['address']));
    $dateOfBirth = $_POST['dob'];

    // Validation
    $errors = [];
    if (strlen($name) < 2) $errors[] = "Name must be at least 2 characters.";
    if (!preg_match('/^[0-9]{10,15}$/', $phone_number)) $errors[] = "Invalid phone number.";
    if (strlen($address) < 5) $errors[] = "Address must be at least 5 characters.";
    if (strtotime($dateOfBirth) > strtotime('-13 years')) $errors[] = "You must be at least 13 years old.";

    // Check if phone number already exists
    $stmt = $connection->prepare("SELECT COUNT(*) FROM customer WHERE PhoneNumber = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) $errors[] = "A customer with this phone number already exists.";

    // If no errors, insert the new customer
    if (empty($errors)) {
        $stmt = $connection->prepare("INSERT INTO customer (Name, PhoneNumber, Address, DateOfBirth) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone_number, $address, $dateOfBirth);
        
        if ($stmt->execute()) {
            $feedback = "New customer added successfully!";
        } else {
            $feedback = "Error: Could not add customer.";
        }
        $stmt->close();
    } else {
        $feedback = "Errors: " . implode("<br>", $errors);
    }
}

// Close the database connection
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="../CSS/add.css">
</head>
<body>
    <h2>Add New Customer</h2>
    <button><a href="../customer.php">Back</a></button>
    
    <?php if (!empty($feedback)): ?>
        <div class="feedback <?php echo strpos($feedback, 'Error') !== false ? 'error' : 'success'; ?>">
            <?php echo $feedback; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" autocomplete="off">
        <div class="form-group">
            <label for="name">Full Name(Lastname, FirstName):</label>
            <input type="text" id="name" name="name" required minlength="2" maxlength="50" pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces">
        </div>

        <div class="form-group">
            <label for="phoneNumber">Phone Number:</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" required pattern="[0-9]{10,15}" title="Phone number should be 10-15 digits">
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required minlength="5" maxlength="100">
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required max="<?php echo date('Y-m-d', strtotime('-13 years')); ?>">
        </div>

        <div class="form-group">
            <input type="submit" value="Add Customer">
        </div>
    </form>
</body>
</html>
