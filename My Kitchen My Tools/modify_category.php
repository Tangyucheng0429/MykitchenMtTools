<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "mykitchenmytools_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if category_id is provided in URL
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Fetch the existing category details from the database
    $result = $conn->query("SELECT * FROM categories WHERE id = $category_id");
    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        echo "Category not found.";
        exit();
    }

    // Handle form submission to update the category
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = htmlspecialchars($_POST['name']);
        $image = $category['image'];  // Keep current image unless updated

        // Handle file upload if a new image is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($image_file_type, $allowed_types)) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image = $target_file;  // Update image path if upload is successful
                }
            }
        }

        // Update the category in the database
        $stmt = $conn->prepare("UPDATE categories SET name = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $image, $category_id);

        if ($stmt->execute()) {
            echo "<script>alert('Category updated successfully!'); window.location.href='view_category.php';</script>";
        } else {
            echo "<script>alert('Error updating category.');</script>";
        }
    }
} else {
    echo "No category ID provided.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Category - Admin Dashboard</title>
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

        .logo-text-container h4 {
            font-size: 26px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .logo-text-container h6 {
            font-size: 18px;
            font-weight: 400;
            color: #666;
            margin: 0;
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

        /* Sidebar styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 250px;
            background-color: #1f1f1f;
            padding-top: 100px;
            color: #fff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            color: #bdbdbd;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #333;
        }

        .form-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 120px auto;
            margin-left: auto;
            margin-right: auto;
        }

        .form-container label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .form-container input[type="text"],
        .form-container input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        img {
            display: block;
            margin-bottom: 20px;
            height: 100px;
            width: 100px;
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

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <h2>Modify Category</h2>

            <label for="name">Category Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>

            <label for="image">Category Image:</label>
            <input type="file" id="image" name="image">
            <img src="<?php echo htmlspecialchars($category['image']); ?>" alt="Category Image">

            <input type="submit" value="Update Category">
        </form>
    </div>
</body>
</html>
