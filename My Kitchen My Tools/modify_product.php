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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $company_name = $_POST['company_name'];
    $product_size = $_POST['product_size'];
    $product_link = $_POST['product_link'];
    $category_name = $_POST['category_name'];
    $product_description = $_POST['product_description'];
    $hotseller = isset($_POST['hotseller']) ? 1 : 0;
    $new = isset($_POST['new']) ? 1 : 0;
    $product_height = $_POST['product_height'];
    $product_length = $_POST['product_length'];
    $product_width = $_POST['product_width'];
    $product_weight = $_POST['product_weight'];
    $product_diameter = $_POST['product_diameter'];
    $product_capacity = $_POST['product_capacity'];
    $induction_base_diameter = $_POST['induction_base_diameter'];
    $product_material = $_POST['product_material'];

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $image = $_FILES['product_image'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image["name"]);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_file_type, $allowed_types)) {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                echo "<script>alert('Error uploading the file.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
        }
    }

    if (isset($image_path)) {
        $stmt = $conn->prepare("UPDATE products SET product_name=?, price=?, company_name=?, product_size=?, product_link=?, category_name=?, product_description=?, hotseller=?, new=?, product_height=?, product_length=?, product_width=?, product_weight=?, product_diameter=?, product_capacity=?, induction_base_diameter=?, product_material=?, image=? WHERE product_id=?");
        $stmt->bind_param("sdssssssisssssssssi", $product_name, $price, $company_name, $product_size, $product_link, $category_name, $product_description, $hotseller, $new, $product_height, $product_length, $product_width, $product_weight, $product_diameter, $product_capacity, $induction_base_diameter, $product_material, $image_path, $product_id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET product_name=?, price=?, company_name=?, product_size=?, product_link=?, category_name=?, product_description=?, hotseller=?, new=?, product_height=?, product_length=?, product_width=?, product_weight=?, product_diameter=?, product_capacity=?, induction_base_diameter=?, product_material=? WHERE product_id=?");
        $stmt->bind_param("sdssssssissssssssi", $product_name, $price, $company_name, $product_size, $product_link, $category_name, $product_description, $hotseller, $new, $product_height, $product_length, $product_width, $product_weight, $product_diameter, $product_capacity, $induction_base_diameter, $product_material, $product_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href='view_product.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to update product.');</script>";
    }

    $stmt->close();
} else if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: view_product.php");
    exit();
}

$categories_list = $conn->query("SELECT name FROM categories");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Product - Admin Dashboard</title>
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
            transition: background 0.3s;
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
            margin-left: 550px;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-header h2 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .form-header .button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .form-header .button:hover {
            background-color: #0056b3;
        }

        .form-container label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="url"],
        .form-container input[type="file"],
        .form-container input[type="checkbox"],
        .form-container textarea,
        .form-container select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-container textarea {
            resize: vertical;
        }

        .form-container input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
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

        .form-container input, 
        .form-container select, 
        .form-container textarea {
            margin-bottom: 20px;
        }

        .button {
            font-size: 16px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
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
        <div class="form-header">
            <h2>Modify Product</h2>
            <a href="view_product.php" class="button">View All Products</a>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
    
    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">

    <label for="price">Price:</label>
    <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">

    <label for="company_name">Company Name:</label>
    <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($product['company_name']); ?>">

    <label for="product_size">Product Size:</label>
    <input type="text" id="product_size" name="product_size" value="<?php echo htmlspecialchars($product['product_size']); ?>">

    <label for="product_link">Product Link:</label>
    <input type="text" id="product_link" name="product_link" value="<?php echo htmlspecialchars($product['product_link']); ?>">

    <label for="category_name">Category:</label>
    <select id="category_name" name="category_name">
        <option value="">Select Category</option>
        <?php if ($categories_list->num_rows > 0): ?>
            <?php while ($category = $categories_list->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($category['name']); ?>" <?php echo ($category['name'] == $product['category_name']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
            <?php endwhile; ?>
        <?php endif; ?>
    </select>

    <label for="product_description">Product Description:</label>
    <textarea id="product_description" name="product_description" rows="4"><?php echo htmlspecialchars($product['product_description']); ?></textarea>

    <label for="product_image">Product Image:</label>
    <input type="file" id="product_image" name="product_image" accept="image/*">

    <label for="hotseller">Hot Seller:</label>
    <input type="checkbox" id="hotseller" name="hotseller" <?php echo $product['hotseller'] ? 'checked' : ''; ?>>

    <label for="new">New Product:</label>
    <input type="checkbox" id="new" name="new" <?php echo $product['new'] ? 'checked' : ''; ?>>

    <label for="product_height">Height:</label>
    <input type="text" id="product_height" name="product_height" value="<?php echo htmlspecialchars($product['product_height']); ?>">

    <label for="product_length">Length:</label>
    <input type="text" id="product_length" name="product_length" value="<?php echo htmlspecialchars($product['product_length']); ?>">

    <label for="product_width">Width:</label>
    <input type="text" id="product_width" name="product_width" value="<?php echo htmlspecialchars($product['product_width']); ?>">

    <label for="product_weight">Weight:</label>
    <input type="text" id="product_weight" name="product_weight" value="<?php echo htmlspecialchars($product['product_weight']); ?>">

    <label for="product_diameter">Diameter:</label>
    <input type="text" id="product_diameter" name="product_diameter" value="<?php echo htmlspecialchars($product['product_diameter']); ?>">

    <label for="product_capacity">Capacity:</label>
    <input type="text" id="product_capacity" name="product_capacity" value="<?php echo htmlspecialchars($product['product_capacity']); ?>">

    <label for="induction_base_diameter">Induction Base Diameter:</label>
    <input type="text" id="induction_base_diameter" name="induction_base_diameter" value="<?php echo htmlspecialchars($product['induction_base_diameter']); ?>">

    <label for="product_material">Product Material:</label>
    <input type="text" id="product_material" name="product_material" value="<?php echo htmlspecialchars($product['product_material']); ?>">

    <input type="submit" value="Update Product">
</form>

    </div>
</body>
</html>
