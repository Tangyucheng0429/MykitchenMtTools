<?php
session_start();
$timeout_duration = 1800;

if (isset($_SESSION['last_activity'])) {
    $duration = time() - $_SESSION['last_activity'];
    if ($duration > $timeout_duration) {
        session_unset();
        session_destroy();
        header("Location: admin.php?timeout=1");
        exit();
    }
}

$_SESSION['last_activity'] = time();
if (!isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "mykitchenmytools_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categories_list = $conn->query("SELECT * FROM categories");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Administration - Admin Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="Admin\MyKitchenMyTools Logo.png">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        .logo-text-container {
            display: flex;
            align-items: center;
        }
        .logo-text-container img {
            height: 50px;
            width: auto;
            margin-right: 15px;
        }
        .logo-text-container h4, .logo-text-container h6 {
            margin: 0;
        }
        .logo-text-container h4 {
            font-size: 26px;
            font-weight: 700;
            color: #333;
        }
        .logo-text-container h6 {
            font-size: 18px;
            font-weight: 400;
            color: #666;
        }
        .logout {
            font-size: 16px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            background-color: #e53935;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
            position: absolute;
            top: 20px;
            right: 90px;
        }
        .logout:hover {
            background-color: #d32f2f;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 250px;
            background-color: #fff;
            padding-top: 100px;
            color: #333;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            color: #666;
            display: block;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background-color: #f0f0f0;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .form-container {
                margin-left: 0;
                max-width: 100%;
            }

            .header {
                left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
    <a href="admin_dashboard.php"><i class="fas fa-home"></i> Home</a>
        <a href="view_category.php"><i class="fas fa-list"></i> Categories</a>
        <a href="view_product.php"><i class="fas fa-box"></i> Products</a>
    </div>
    <header class="header">
        <div class="logo-text-container">
            <img src="Admin/MyKitchenMyTools Logo.png" alt="Company Logo" class="company-logo">
            <div>
                <h4>My Kitchen My Tools</h4>
                <h6>Admin Dashboard</h6>
            </div>
        </div>
        <a href="logout.php" class="logout">Logout</a>
    </header>
</body>
</html>
