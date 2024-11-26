<?php 
session_start();
include '../database.php';

// Validate ISBN from the URL
$bookISBN = isset($_GET['isbn']) ? $_GET['isbn'] : null;

if (!$bookISBN) {
    die("Invalid ISBN provided");
}

// Fetch book details
$stmt = $connection->prepare("SELECT * FROM book WHERE ISBN = ?");
$stmt->bind_param("s", $bookISBN);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    die("Book not found");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $title = $_POST['title'] ?? '';
    $year = $_POST['year'] ?? '';
    $publisher = $_POST['publisher'] ?? '';

    // Validate inputs
    $errors = [];
    if (empty($title)) $errors[] = "Title is required";
    if (!$year || $year < 1000 || $year > date('Y')) $errors[] = "Invalid year";
    if (empty($publisher)) $errors[] = "Publisher is required";

    if (empty($errors)) {
        try {
            // Prepare update statement
            $updateStmt = $connection->prepare("UPDATE book SET Title = ?, Year = ?, Publisher = ? WHERE ISBN = ?");
            $updateStmt->bind_param("siss", $title, $year, $publisher, $bookISBN);
            
            if ($updateStmt->execute()) {
                $successMessage = "Book updated successfully";
                // Refresh book data
                $book = [
                    'Title' => $title,
                    'Year' => $year,
                    'Publisher' => $publisher
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
    <title>Update Book - Local Bookstore</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        .success-message {
            color: green;
            margin-bottom: 15px;
        }

        .isbn-info {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Book</h2>
        
        <div class="isbn-info">
            <strong>ISBN:</strong> <?php echo htmlspecialchars($bookISBN); ?>
        </div>

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
                <label for="title">Title:</label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="<?php echo htmlspecialchars($book['Title']); ?>" 
                    required
                    maxlength="255"
                >
            </div>

            <div class="form-group">
                <label for="year">Year:</label>
                <input 
                    type="number" 
                    id="year" 
                    name="year" 
                    value="<?php echo htmlspecialchars($book['Year']); ?>" 
                    required
                    min="1000"
                    max="<?php echo date('Y'); ?>"
                >
            </div>

            <div class="form-group">
                <label for="publisher">Publisher:</label>
                <input 
                    type="text" 
                    id="publisher" 
                    name="publisher" 
                    value="<?php echo htmlspecialchars($book['Publisher']); ?>" 
                    required
                    maxlength="255"
                >
            </div>

            <button type="submit" class="btn">Update Book</button>
            <a href="../book.php" class="btn" style="background-color: #6c757d; margin-left: 10px;">Cancel</a>
        </form>
    </div>
</body>
</html>
