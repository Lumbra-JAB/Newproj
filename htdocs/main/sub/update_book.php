<?php 
session_start();
include '../database.php';

// Fetch employees for the dropdown
function getEmployees($connection) {
    return $connection->query("SELECT TaxpayerID, Name FROM employee");
}

// Update an existing book in the database
function updateBook($connection, $isbn, $title, $year, $publisher, $imagePath) {
    $stmt = $connection->prepare("UPDATE book SET Title = ?, Year = ?, Publisher = ?, Image = ? WHERE ISBN = ?");
    $stmt->bind_param("sssss", $title, $year, $publisher, $imagePath, $isbn);
    return $stmt->execute();
}

$feedback = "";
$bookUpdated = false;

// Get the book ISBN from the URL parameter
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Fetch existing book details
    $stmt = $connection->prepare("SELECT ISBN, Title, Year, Publisher, Image FROM book WHERE ISBN = ?");
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();

    // Redirect if book not found
    if (!$book) {
        header("Location: ../book.php");
        exit();
    }

    // Fetch employees for the dropdown
    $employees = getEmployees($connection);
} else {
    header("Location: ../book.php");
    exit();
}

// Handle form submission
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
    $imagePath = $book['Image']; // Retain old image if no new one is uploaded
    if (!empty($_FILES['image_path']['name']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['image_path']['size'] <= 1048576) { // 1 MB = 1,048,576 bytes
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $imagePath = $targetDir . uniqid() . '_' . basename($_FILES['image_path']['name']);
            if (!move_uploaded_file($_FILES['image_path']['tmp_name'], $imagePath)) {
                $feedback = "Error uploading the image.";
            }
        } else {
            $feedback = "Error: The image file size must not exceed 1 MB.";
        }
    }

    // Validate and update book
    if ($publisher && empty($feedback)) {
        if (updateBook($connection, $isbn, $title, $year, $publisher, $imagePath)) {
            $feedback = "Book updated successfully.";
            $bookUpdated = true;
        } else {
            $feedback = "Error updating book.";
        }
    } else if (empty($feedback)) {
        $feedback = "Error: Publisher must be valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Book - Local Bookstore</title>
    <link rel="stylesheet" type="text/css" href="../CSS/update.css">
</head>
<body>
    <div class="container">
        <h2>Update Book - ISBN: <?php echo htmlspecialchars($book['ISBN']); ?></h2>

        <!-- Display feedback messages -->
        <?php if ($feedback): ?>
            <div class="message <?php echo $bookUpdated ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($feedback); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['Title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="year">Year:</label>
                <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($book['Year']); ?>" min="1800" max="9999" required>
            </div>

            <div class="form-group">
                <label for="employee">Author (Employee):</label>
                <select name="employee" id="employee" required>
                    <?php while ($row = $employees->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['TaxpayerID']); ?>" 
                            <?php echo $book['Publisher'] === $row['Name'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['Name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image_path">Image (Max: 1MB):</label>
                <input type="file" name="image_path" id="image_path" accept=".png, .jpg, .jpeg">
            </div>

            <?php if ($book['Image']): ?>
                <div class="form-group">
                    <img src="<?php echo htmlspecialchars($book['Image']); ?>" alt="Book Image" width="100">
                </div>
            <?php endif; ?>

            <button type="submit">Update Book</button>
        </form>

        <a href="../book.php" class="btn">Back to Books</a>
    </div>
</body>
</html>
