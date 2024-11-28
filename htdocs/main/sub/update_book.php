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
    $stmt->bind_param("ssiss", $title, $year, $publisher, $imagePath, $isbn);
    return $stmt->execute();
}

$feedback = "";
$bookUpdated = false;

// Get the book ISBN from the URL parameter for updating
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Fetch the existing book details
    $stmt = $connection->prepare("SELECT ISBN, Title, Year, Publisher, Image FROM book WHERE ISBN = ?");
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();

    // If book doesn't exist, redirect
    if (!$book) {
        header("Location: ../book.php");
        exit();
    }

    // Fetch the employees for the dropdown
    $employees = getEmployees($connection);
}

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
    $imagePath = $book['Image']; // Keep the old image if no new one is uploaded
    if (!empty($_FILES['image_path']['name']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/"; 
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $imagePath = $targetDir . uniqid() . '_' . basename($_FILES['image_path']['name']);
        if (!move_uploaded_file($_FILES['image_path']['tmp_name'], $imagePath)) {
            $feedback = "Error uploading the image.";
        }
    }

    // Validate and update book
    if ($publisher) {
        if (updateBook($connection, $isbn, $title, $year, $publisher, $imagePath)) {
            $feedback = "Book updated successfully.";
            $bookUpdated = true;
        } else {
            $feedback = "Error updating book.";
        }
    } else {
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
    <link rel="stylesheet" type="text/css" href="../CSS/add.css">
</head>
<body>
    <h2>Update Book</h2>
    <button><a href="../book.php">Back</a></button>

    <!-- Display feedback -->
    <?php if ($feedback): ?>
        <p><?php echo htmlspecialchars($feedback); ?></p>
        <?php if ($bookUpdated): ?>
            <a href="../book.php" class="btn">Back to Book List</a>
        <?php endif; ?>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label>ISBN (Read-only):</label><br>
        <input type="text" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>" readonly><br>
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($book['Title']); ?>" required><br>
        <label>Year:</label><br>
        <input type="number" name="year" value="<?php echo htmlspecialchars($book['Year']); ?>" min="1800" max="9999" required><br>
        <label>Author (Employee):</label><br>
        <select name="employee" required>
            <?php while ($row = $employees->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['TaxpayerID']); ?>" <?php echo $book['Publisher'] === $row['Name'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['Name']); ?>
                </option>
            <?php endwhile; ?>
        </select><br>
        <label>Image:</label><br>
        <input type="file" name="image_path" accept=".png, .jpg, .jpeg"><br>
        <?php if ($book['Image']): ?>
            <img src="<?php echo htmlspecialchars($book['Image']); ?>" alt="Book Image" width="100"><br>
        <?php endif; ?>
        <input type="submit" value="Update Book">
    </form>
</body>
</html>
