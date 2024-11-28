<?php
include 'database.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = md5($password);

    // Default role is set to 'customer'
    $role = 'customer';

    // Check if the username already exists
    $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p style='color:red;text-align:center;'>Username already exists. Please try a different one.</p>";
    } else {
        // Insert the new user into the database
        $stmt = $connection->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "<p style='color:green;text-align:center;'>Registration successful! You can now log in.</p>";
            header("Refresh:2; url=login.php"); // Redirect to login.php after 2 seconds
        } else {
            echo "<p style='color:red;text-align:center;'>Error registering user. Please try again later.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            width: 300px;
            margin: auto;
            padding: 50px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }
        input, button {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a {
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Register</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <!-- No role selection, defaults to 'customer' -->

        <button type="submit">Register</button>

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login Here</a></p>
        </div>
    </form>
</body>
</html>
