<?php session_start();
include '../database.php'; 
?>

<?php
// Function to generate a unique TaxpayerID
function generateTaxpayerID($connection) {
    do {
        $taxpayerID = str_pad(rand(1, 1000000000), 10, '0', STR_PAD_LEFT); // Generate a 15-digit ID
        // Check if this ID already exists
        $result = $connection->query("SELECT TaxpayerID FROM employee WHERE TaxpayerID = '$taxpayerID'");
    } while ($result->num_rows > 0);
    return $taxpayerID;
}

// Auto-generate TaxpayerID when the page loads
$autoTaxpayerID = generateTaxpayerID($connection);

$successMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taxpayerID = $_POST['taxpayerID'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $dateOfBirth = $_POST['dob'];
    $pseudonym = $_POST['pseudonym'];

    // Insert the new employee record
    $sql = "INSERT INTO employee (TaxpayerID, Name, Address, DateOfBirth, Pseudonym) 
            VALUES ('$taxpayerID', '$name', '$address', '$dateOfBirth', '$pseudonym')";

    if ($connection->query($sql) === TRUE) {
        $successMessage = "success";
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee - Local Bookstore</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        form {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
        }

        input[type="text"], 
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; /* Adjusted margin for better centering */
            padding: 20px;
            border: 1px solid #888;
            width: 400px; /* Increased width for the modal */
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .modal-content a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .modal-content a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Add New Employee</h2>
    <button><a href="../home.php">Home</a></button>
    <button><a href="../employee.php">Back</a></button></form>
    <form method="POST" action="">
        <label>Taxpayer ID (Auto-generated):</label><br>
        <input type="text" name="taxpayerID" value="<?php echo $autoTaxpayerID; ?>" readonly><br>
        <label>FullName(Lastname, FirstName):</label><br>
        <input type="text" name="name" required><br>
        <label>Address:</label><br>
        <input type="text" name="address" required><br>
        <label>Date of Birth:</label><br>
        <input type="date" name="dob" required><br>
        <label>Pseudonym:</label><br>
        <input type="text" name="pseudonym" required><br>
        <input type="submit" value="Add Employee">
    </form>

    <!-- Success Modal -->
    <?php if ($successMessage === "success"): ?>
    <div id="successModal" class="modal" style="display: block;">
        <div class="modal-content">
            <h2>Employee Added Successfully!</h2>
            <a href="../employee.php">Back to Employees</a>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>