<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Bookstore</title>
    
    <style>
        /* General Reset */
body, h1, h2, ul, li, a {
    margin: 0;
    padding: 0;
    font-family: 'Arial', sans-serif;
    text-decoration: none;
    list-style: none;
}

/* General body styling */
body {
    background-color: #f4f4f4;
    color: #333;
    text-align: center;
}

/* Header styling */
header {
    background-color: #3e4b61;
    color: #fff;
    padding: 20px 0;
    text-align: center;
    border-bottom: 5px solid #293141;
}

/* Header Title */
header h1 {
    font-size: 2.5em;
    font-weight: bold;
}

/* Navigation menu */
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

/* Hover effect for navigation links */
.menu ul li a:hover {
    background-color: #3e4b61;
    border-radius: 5px;
}

/* Hover effect for navigation buttons */
nav ul li button:hover {
    color: aqua;
}

/* Button styling */
ul li button {
    font-size: 20px;
    color: white;
    outline: none;
    border: none;
    background: transparent;
    cursor: pointer;
    font-family: sans-serif;
}

/* Dashboard Section */
.dashboard {
    display: grid;
    grid-template-columns: 1fr; /* Full-width header on top */
    gap: 30px;
    text-align: center;
    margin-top: 20px;
}

/* Header Box Styling */
.header-box {
    padding: 30px;
    background-color: #007bff;
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    color: #fff;
    font-size: 32px; /* Large font for the title */
    font-weight: bold;
}

/* Category Box Layout */
.category-boxes {
    display: grid;
    grid-template-columns: repeat(2, 250px); /* Two columns */
    gap: 30px;
    justify-content: center; /* Center category boxes */
}

/* Individual Category Box */
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

/* Image Styling */
.box img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    margin-bottom: 15px;
    border-radius: 50%;
}

/* Box Titles */
.box h3 {
    font-size: 24px;
    color: #333;
    margin-top: 15px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    header h1 {
        font-size: 2em;
    }

    .menu ul {
        flex-direction: column;
    }

    .menu ul li {
        margin: 10px 0;
    }

    .dashboard {
        grid-template-columns: 1fr; /* Stack items vertically on smaller screens */
    }

    .category-boxes {
        grid-template-columns: 1fr; /* Stack boxes vertically */
    }
}

/* Form Styling */
form {
    width: 30%;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    border: 1px solid #333;
    border-radius: 5px;
}

/* Form Input Fields */
input[type="text"], input[type="number"] {
    width: 95%;
    padding: 5px;
    margin: 10px 0;
}

/* Submit Button */
input[type="submit"] {
    padding: 10px;
    background-color: #333;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #555;
}

/* Table Styling */
table {
    width: 60%;
    margin: 20px auto;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #333;
}

th, td {
    padding: 10px;
    text-align: left;
}

/* Button Box */
.button-box {
    width: 220px;
    margin: 35px auto;
    position: relative;
    box-shadow: 0 0 20px 9px #ff61241f;
    border-radius: 30px;
}

/* Background Image Section */
.image {
    background-image: url('https://www.pixelstalk.net/wp-content/uploads/2016/08/Old-Library-Wallpaper.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh; /* Full height */
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    text-align: center;
    position: relative;
}

    </style>
</head>
<body>
    <!-- Dashboard Container -->
    <div class="dashboard">
        <!-- Header Box with the Title -->
        <div class="header-box">
            Local Bookstore
        </div>

        <!-- Category Boxes in a Grid -->
        <div class="category-boxes">
            <a href="book.php">
                <div class="box">
                    <img src="path/to/books-image.jpg" alt="Books">
                    <h3>Books</h3>
                </div>
            </a>
            <a href="employee.php">
                <div class="box">
                    <img src="path/to/employees-image.jpg" alt="Employees">
                    <h3>Employees</h3>
                </div>
            </a>
            <a href="customer.php">
                <div class="box">
                    <img src="path/to/customer-image.jpg" alt="Customer">
                    <h3>Customer</h3>
                </div>
            </a>
            <a href="sale_transaction.php">
                <div class="box">
                    <img src="path/to/transactions-image.jpg" alt="Book Transactions">
                    <h3>Book Transactions</h3>
                </div>
            </a>
        </div>
    </div>

</body>
</html>
