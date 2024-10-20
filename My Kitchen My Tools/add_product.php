<?php
session_start();
$timeout_duration = 1800;

// Check if the session has expired
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

// Check if user is logged in
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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user inputs to prevent SQL injection and XSS
    $product_name = htmlspecialchars($_POST['product_name']);
    $price = htmlspecialchars($_POST['price']);
    $company_name = htmlspecialchars($_POST['company_name']);
    $product_size = htmlspecialchars($_POST['product_size']);
    $product_link = htmlspecialchars($_POST['product_link']);
    $product_description = htmlspecialchars($_POST['product_description']);
    $category_name = htmlspecialchars($_POST['category_name']);
    $hotseller = isset($_POST['hotseller']) ? 1 : 0;
    $new = isset($_POST['new']) ? 1 : 0;
    $product_height = htmlspecialchars($_POST['product_height']);
    $product_length = htmlspecialchars($_POST['product_length']);
    $product_width = htmlspecialchars($_POST['product_width']);
    $product_weight = htmlspecialchars($_POST['product_weight']);
    $product_diameter = htmlspecialchars($_POST['product_diameter']);
    $product_capacity = htmlspecialchars($_POST['product_capacity']);
    $induction_base_diameter = htmlspecialchars($_POST['induction_base_diameter']);
    $product_material = htmlspecialchars($_POST['product_material']);

    // Handle image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $upload_ok = 1;

    // Check if file is an actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $upload_ok = 1;
    } else {
        echo "<script>alert('File is not an image.');</script>";
        $upload_ok = 0;
    }

    // Check file size (limit 5MB)
    if ($_FILES["image"]["size"] > 5000000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $upload_ok = 0;
    }

    // Allow certain file formats (jpg, jpeg, png, gif)
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $upload_ok = 0;
    }

    // Upload image and save product to database
    if ($upload_ok == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Prepare SQL statement
            $stmt = $conn->prepare("INSERT INTO products (product_name, price, company_name, product_size, product_link, product_description, image, category_name, hotseller, new, product_height, product_length, product_width, product_weight, product_diameter, product_capacity, induction_base_diameter, product_material) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdssssssiiisssssss", $product_name, $price, $company_name, $product_size, $product_link, $product_description, $target_file, $category_name, $hotseller, $new, $product_height, $product_length, $product_width, $product_weight, $product_diameter, $product_capacity, $induction_base_diameter, $product_material);

            // Execute the query
            if ($stmt->execute()) {
                echo "<script>alert('Product created successfully!'); window.location.href='view_product.php';</script>";
            } else {
                echo "<script>alert('Error: Unable to create product.');</script>";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
}

// Fetch categories for dropdown
$categories_list = $conn->query("SELECT name FROM categories");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product - Admin Dashboard</title>
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

/* Header styles */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
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

/* Form container styles */
.form-container {
    background-color: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 120px auto;
    margin-left: 550px; /* Align the form with sidebar */
}

/* Form header styling */
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
    display: inline-block;
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

/* Form input styling */
.form-container label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

.form-container input[type="text"],
.form-container input[type="number"],
.form-container input[type="url"],
.form-container input[type="file"],
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

/* Submit button styling */
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

/* Button styles */
.button {
    padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #45a049;
        }

/* Additional spacing for form elements */
.form-container input, 
.form-container select, 
.form-container textarea {
    margin-bottom: 20px;
}

/* Media query for responsive design */
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
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2>Create Product</h2>
                <a href="view_product.php" class="button">View All Products</a>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required>

    <label for="price">Price (RM):</label>
    <input type="number" step="0.01" id="price" name="price" required>

    <label for="company_name">Company Name:</label>
    <input type="text" id="company_name" name="company_name">

    <label for="product_size">Product Size:</label>
    <input type="text" id="product_size" name="product_size">

    <label for="product_link">Product Link:</label>
    <input type="url" id="product_link" name="product_link">

    <label for="category_name">Category:</label>
    <select id="category_name" name="category_name" required>
        <option value="">Select Category</option>
        <?php if ($categories_list->num_rows > 0): ?>
            <?php while ($category = $categories_list->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($category['name']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
            <?php endwhile; ?>
        <?php endif; ?>
    </select>

    <label for="product_description">Product Description:</label>
    <textarea id="product_description" name="product_description" rows="4"></textarea>

    <label for="image">Product Image:</label>
    <input type="file" id="image" name="image" accept="image/*" required>

    <!-- Hot Seller and New Product checkboxes -->
    <label for="hotseller">Hot Seller:</label>
    <input type="checkbox" id="hotseller" name="hotseller">

    <label for="new">New Product:</label>
    <input type="checkbox" id="new" name="new">

    <label for="product_height">Height:</label>
    <input type="text" id="product_height" name="product_height">

    <label for="product_length">Length:</label>
    <input type="text" id="product_length" name="product_length">

    <label for="product_width">Width:</label>
    <input type="text" id="product_width" name="product_width">

    <label for="product_weight">Weight:</label>
    <input type="text" id="product_weight" name="product_weight">

    <label for="product_diameter">Diameter:</label>
    <input type="text" id="product_diameter" name="product_diameter">

    <label for="product_capacity">Capacity:</label>
    <input type="text" id="product_capacity" name="product_capacity">

    <label for="induction_base_diameter">Induction Base Diameter:</label>
    <input type="text" id="induction_base_diameter" name="induction_base_diameter">

    <label for="product_material">Product Material:</label>
    <input type="text" id="product_material" name="product_material">

    <button type="submit" class="button">Create Product</button>
</form>

        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
