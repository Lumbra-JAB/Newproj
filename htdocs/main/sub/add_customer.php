<?php
session_start();
include '../database.php';

// Initialize variables
$feedback = '';

// Validate and sanitize input function
function sanitizeInput($input) {
    // Remove whitespace from beginning and end
    $input = trim($input);
    // Remove backslashes
    $input = stripslashes($input);
    // Convert special characters to HTML entities
    $input = htmlspecialchars($input);
    return $input;
}

// Validate phone number
function validatePhoneNumber($phone) {
    // Remove non-digit characters
    $phone = preg_replace('/\D/', '', $phone);
    
    // Check if phone number is valid (adjust regex as needed)
    return (strlen($phone) >= 10 && strlen($phone) <= 15);
}

// Validate date of birth
function validateDateOfBirth($dob) {
    // Ensure date is in the past and user is at least 13 years old
    $dobTimestamp = strtotime($dob);
    $minAge = strtotime('-13 years');
    
    return ($dobTimestamp !== false && $dobTimestamp < $minAge);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $name = sanitizeInput($_POST['name']);
    $phone_number = sanitizeInput($_POST['phoneNumber']);
    $address = sanitizeInput($_POST['address']);
    $dateOfBirth = $_POST['dob'];

    // Comprehensive validation
    $errors = [];

    // Name validation
    if (empty($name) || strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters long.";
    }

    // Phone number validation
    if (!validatePhoneNumber($phone_number)) {
        $errors[] = "Invalid phone number.";
    }

    // Address validation
    if (empty($address) || strlen($address) < 5) {
        $errors[] = "Address must be at least 8 characters long.";
    }

    // Date of Birth validation
    if (!validateDateOfBirth($dateOfBirth)) {
        $errors[] = "Invalid date of birth. You must be at least 13 years old.";
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        // Use prepared statement to prevent SQL injection
        $stmt = $connection->prepare("INSERT INTO customer (Name, PhoneNumber, Address, DateOfBirth) VALUES (?, ?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("ssss", $name, $phone_number, $address, $dateOfBirth);
            
            try {
                if ($stmt->execute()) {
                    $feedback = "New customer added successfully";
                } else {
                    $feedback = "Error: Could not add customer. " . $stmt->error;
                }
            } catch (Exception $e) {
                $feedback = "Database error: " . $e->getMessage();
            }
            
            $stmt->close();
        }
    }else {
    // Compile error messages
    $feedback = "Please correct the following errors:<br>" . implode("<br>", $errors);
    }
}

// Close connection at the end of the script
if (isset($connection)) {
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="CSS/add.css">
</head>
<body>
    <h2>Add New Customer</h2>
    <button><a href="../home.php">Home</a></button>
    <button><a href="../customer.php">Back</a></button>
    <?php if (!empty($feedback)): ?>
        <div class="feedback <?php echo strpos($feedback, 'error') !== false ? 'error' : 'success'; ?>">
            <?php echo $feedback; ?>
        </div>
    <?php endif; ?>

    <div class="navigation">
        
    </div>

    <form method="POST" action="" autocomplete="off">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" 
                   required 
                   minlength="2" 
                   maxlength="50" 
                   pattern="[A-Za-z\s]+"
                   title="Name should only contain letters and spaces">
        </div>

        <div class="form-group">
            <label for="phoneNumber">Phone Number:</label>
            <input type="tel" 
                   id="phoneNumber" 
                   name="phoneNumber" 
                   required 
                   pattern="[0-9]{10,15}" 
                   title="Phone number should be 10-15 digits">
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" 
                   id="address" 
                   name="address" 
                   required 
                   minlength="5" 
                   maxlength="100">
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" 
                   id="dob" 
                   name="dob" 
                   required 
                   max="<?php echo date('Y-m-d', strtotime('-13 years')); ?>">
        </div>

        <div class="form-group">
            <input type="submit" value="Add Customer">
        </div>
        <?php if ($feedback === "New customer added successfully"): ?>
    <div class="success-modal">
        <p><?php echo htmlspecialchars($feedback); ?></p>
        <a href="../customer.php" class="btn">Back to Customer List</a>
    </div>
<?php endif; ?>
    </form>
</body>
</html>