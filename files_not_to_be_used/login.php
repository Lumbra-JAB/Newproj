<?php
session_start();
include 'database.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST ['role'];
    

    // Hash the password for validation
    $hashed_password = md5($password);

    // Check user credentials
    $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND role = ?");
    $stmt->bind_param("sss", $username, $hashed_password, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Store user information in the session
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($role === 'admin') {
            header("Location: admin_dashboard.php"); // Admin dashboard
        } elseif ($role === 'customer') {
            header("Location: customer_dashboard.php"); // Customer dashboard
        }
        exit();
    } else {
        echo "<p style='color:red;text-align:center;'>Invalid credentials. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        input, select, button {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Login</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="customer">Customer</option>
        </select>

        <button type="submit">Login</button>

        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register Now</a></p>
        </div>
    </form>
</body>
</html>
