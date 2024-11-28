<?php
session_start(); // Start session to access user data
include 'database.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Local Bookstore</title>
    <style>
        /* Include your existing styles here */
        body, h1, h2, ul, li, a {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            text-decoration: none;
            list-style: none;
        }
        body {
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
        }
        .menu nav {
            background-color: #293141;
        }
        .menu ul {
            display: flex;
            justify-content: center;
            padding: 10px;
        }
        .menu ul li {
            margin: 0 15px;
        }
        .menu ul li a {
            color: #fff;
            font-size: 1.2em;
            padding: 10px 15px;
            transition: background-color 0.3s ease;
        }
        .menu ul li a:hover {
            background-color: #3e4b61;
            border-radius: 5px;
        }
        .dashboard {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            text-align: center;
            margin-top: 20px;
        }
        .header-box {
            padding: 30px;
            background-color: #007bff;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            color: #fff;
            font-size: 32px;
            font-weight: bold;
        }
        .category-boxes {
            display: grid;
            grid-template-columns: repeat(2, 250px);
            gap: 30px;
            justify-content: center;
        }
        .box {
            padding: 30px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }
        .box:hover {
            background-color: #e8f4fc;
            transform: translateY(-8px);
        }
        .box img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 15px;
            border-radius: 50%;
        }
        .box h3 {
            font-size: 24px;
            color: #333;
            margin-top: 15px;
        }
        /* Styling the logout button */
        .logout-button {
            background-color: #ff4d4d;
            color: white;
            padding: 10px 20px;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .logout-button:hover {
            background-color: #e63946;
        }
    </style>
</head>
<body>
    
        
        

    <!-- Main Content -->
    <div class="dashboard">
        <!-- Header Box with the Title -->
        <div class="header-box">
            Local Bookstore
        </div>

        <!-- Category Boxes in a Grid -->
        <div class="category-boxes">
            <a href="book.php">
                <div class="box">
                    <img src="https://static.vecteezy.com/system/resources/thumbnails/002/219/582/small/illustration-of-book-icon-free-vector.jpg" alt="Books">
                    <h3>Books</h3>
                </div>
            </a>

            <!-- No role-based check anymore, everyone can access the following -->
            <a href="sale_transaction.php">
                <div class="box">
                    <img src="https://static.vecteezy.com/system/resources/thumbnails/000/290/969/small/3__2835_29.jpg" alt="Transactions">
                    <h3>Transactions</h3>
                </div>
            </a>
            <a href="employee.php">
                <div class="box">
                    <img src="https://static.vecteezy.com/system/resources/thumbnails/005/972/881/small/business-team-employees-user-icon-free-vector.jpg" alt="Employees">
                    <h3>Employees</h3>
                </div>
            </a>
            <a href="customer.php">
                <div class="box">
                    <img src="https://static.vecteezy.com/system/resources/thumbnails/004/406/111/small/courier-icon-design-illustration-with-symbol-customer-buyer-client-postman-product-for-advertising-business-free-vector.jpg" alt="Customer">
                    <h3>Customers</h3>
                </div>
            </a>
        </div>
    </div>

    
</body>
</html>
