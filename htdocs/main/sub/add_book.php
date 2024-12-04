<?php 
session_start();
include '../database.php';

// Fetch employees for the dropdown
function getEmployees($connection) {
    return $connection->query("SELECT TaxpayerID, Name FROM employee");
}

// Insert a new book into the database
function addBook($connection, $isbn, $title, $year, $publisher, $imagePath) {
    $stmt = $connection->prepare("INSERT INTO book (ISBN, Title, Year, Publisher, Image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $isbn, $title, $year, $publisher, $imagePath);
    return $stmt->execute();
}

$feedback = "";
$bookAdded = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $year = $_POST['year'];
    $employeeId = $_POST['employee'];

    // Fetch publisher's name
    $stmt = $connection->prepare("SELECT Name FROM employee WHERE TaxpayerID = ?");
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    $publisher = $stmt->get_result()->fetch_assoc()['Name'] ?? null;

    // Handle image upload
    $imagePath = null;
    if (!empty($_FILES['image_path']['name']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image_path'];
        $fileName = basename($file['name']);
        $fileSize = $file['size'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

        // Define allowed file types and size limit (1 MB)
        $allowedTypes = ['jpg', 'jpeg', 'png'];
        $maxSize = 1 * 1024 * 1024; // 1 MB

        if (in_array($fileExt, $allowedTypes) && $fileSize <= $maxSize) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            // Create unique file name and upload file
            $newFileName = uniqid() . '.' . $fileExt;
            $imagePath = $targetDir . $newFileName;

            if (!move_uploaded_file($file['tmp_name'], $imagePath)) {
                $feedback = "Error uploading the image.";
            }
        } else {
            $feedback = "Invalid file type or size exceeded. Allowed types: jpg, jpeg, png. Max size: 1 MB.";
        }
    }

    // Validate and insert book
    if (strlen($isbn) === 13 && $publisher && empty($feedback)) {
        if (addBook($connection, $isbn, $title, $year, $publisher, $imagePath)) {
            $feedback = "New book added successfully.";
            $bookAdded = true;
        } else {
            $feedback = "Error adding book.";
        }
    } else if (empty($feedback)) {
        $feedback = "Error: ISBN must be 13 digits and publisher must be valid.";
    }
}

$employees = getEmployees($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="../CSS/add.css">
</head>
<body>
    <h2>Add New Book</h2>
    <button><a href="../book.php">Back</a></button>

    <!-- Display feedback -->
    <?php if ($feedback): ?>
        <p><?php echo htmlspecialchars($feedback); ?></p>
        <?php if ($bookAdded): ?>
            <a href="../book.php" class="btn">Back to Book List</a>
        <?php endif; ?>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label>ISBN:</label><br>
        <input type="text" name="isbn" required><br>
        <label>Title:</label><br>
        <input type="text" name="title" required><br>
        <label>Year:</label><br>
        <input type="number" name="year" min="1800" max="9999" required><br>
        <label>Author (Employee):</label><br>
        <select name="employee" required>
            <?php while ($row = $employees->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['TaxpayerID']); ?>">
                    <?php echo htmlspecialchars($row['Name']); ?>
                </option>
            <?php endwhile; ?>
        </select><br>
        <label>Image (Max: 1MB):</label><br>
        <input type="file" name="image_path" accept=".png, .jpg, .jpeg"><br>
        <input type="submit" value="Add Book">
    </form>
</body>
</html>
