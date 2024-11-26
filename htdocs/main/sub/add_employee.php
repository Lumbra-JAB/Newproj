<?php session_start(); 
include '../database.php'; 
?>

<?php
$successMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $dateOfBirth = $_POST['dob'];
    $pseudonym = $_POST['pseudonym'];

    // Insert the new employee record (without specifying the TaxpayerID as it's auto-incremented)
    $sql = "INSERT INTO employee (Name, Address, DateOfBirth, Pseudonym) 
            VALUES ('$name', '$address', '$dateOfBirth', '$pseudonym')";

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
    <link rel="stylesheet" type="text/css" href="../CSS/add.css">
</head>
<body>
    <h2>Add New Employee</h2>
    
    <button><a href="../employee.php">Back</a></button></form>
    <form method="POST" action="">
        <label>Full Name (Lastname, FirstName):</label><br>
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
