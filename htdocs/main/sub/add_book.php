<?php 
session_start();
include '../database.php';

// Function to generate an ISBN
function generateISBN() {
    $isbnBase = str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
    return $isbnBase . calculateISBNCheckDigit($isbnBase);
}

// Function to calculate the check digit for ISBN-13
function calculateISBNCheckDigit($isbnBase) {
    $sum = 0;
    for ($i = 0; $i < 12; $i++) {
        $digit = intval($isbnBase[$i]);
        $sum += ($i % 2 === 0) ? $digit : $digit * 3;
    }
    $remainder = $sum % 10;
    return ($remainder === 0) ? 0 : 10 - $remainder;
}

// Fetch employees for the dropdown
function getEmployees($connection) {
    $stmt = $connection->prepare("SELECT TaxpayerID, Name FROM employee");
    $stmt->execute();
    return $stmt->get_result();
}

// Insert a new book into the database
function addBook($connection, $isbn, $title, $year, $publisher, $imagePath) {
    $stmt = $connection->prepare("INSERT INTO book (ISBN, Title, Year, Publisher, Image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $isbn, $title, $year, $publisher, $imagePath);
    
}

// Handle form submission
$feedback = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $year = $_POST['year'];
    $employeeId = $_POST['employee'];

    // Fetch publisher's name
    $stmt = $connection->prepare("SELECT Name FROM employee WHERE TaxpayerID = ?");
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $publisherRow = $result->fetch_assoc();
    $publisher = $publisherRow['Name'] ?? null;

    // Handle image upload
    $imagePath = null;
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        // Create uploads directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imageFile = $targetDir . uniqid() . '_' . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imageFile)) {
            $imagePath = $imageFile;
        } else {
            $feedback = "Error uploading the image.";
        }
    }

    // Validate and insert book
    if (strlen($isbn) === 13 && $publisher) {
        if (addBook($connection, $isbn, $title, $year, $publisher, $imagePath)) {
            $feedback = "New book added successfully.";
        } 
    } else {
        $feedback = "Error: ISBN must be 13 digits and publisher must be valid.";
    }
}


// Auto-generate an ISBN
$autoISBN = generateISBN();
$employees = getEmployees($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book - Local Bookstore</title>
    <link rel="stylesheet" href="../add.css">
</head>
<body>
    <h2>Add New Book</h2>
    <button><a href="../home.php">Home</a></button>
    <button><a href="../book.php">Back</a></button>
    <?php if ($feedback): ?>
        <p><?php echo htmlspecialchars($feedback); ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label>ISBN (Auto-generated):</label><br>
        <input type="text" name="isbn" value="<?php echo htmlspecialchars($autoISBN); ?>" readonly><br>
        <label>Title:</label><br>
        <input type="text" name="title" required><br>
        <label>Year:</label><br>
        <input type="number" name="year" min="1000" max="9999" required><br>
        <label>Author (Employee):</label><br>
        <select name="employee" required>
            <?php while($row = $employees->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['TaxpayerID']); ?>"><?php echo htmlspecialchars($row['Name']); ?></option>
            <?php endwhile; ?>
        </select><br>
        <label>Image:</label><br>
        <input type="file" name="image" accept="image/*"><br>
        <input type="submit" value="Add Book">
        <?php if ($feedback === "New book added successfully."): ?>
    <div class="success-modal">
        <p><?php echo htmlspecialchars($feedback); ?></p>
        <a href="../book.php" class="btn">Back to Book List</a>
    </div>
<?php endif; ?>
    </form>
</body>
</html>