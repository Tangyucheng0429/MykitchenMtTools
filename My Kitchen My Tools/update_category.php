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
    if (isset($_POST['category_id']) && isset($_POST['category_name']) && isset($_FILES['category_image'])) {
        $category_id = $_POST['category_id'];
        $category_name = $_POST['category_name'];
        $category_image = $_FILES['category_image'];

        if (!empty($category_name)) {
            $image_path = null;

            if ($category_image['error'] == UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $target_file = $target_dir . uniqid() . "_" . basename($category_image["name"]);
                if (move_uploaded_file($category_image["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                } else {
                    echo "<script>alert('Error: Unable to move uploaded file. Please check folder permissions.');</script>";
                }
            }

            if ($image_path) {
                $stmt = $conn->prepare("UPDATE categories SET name = ?, image = ? WHERE id = ?");
                $stmt->bind_param("ssi", $category_name, $image_path, $category_id);
            } else {
                $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
                $stmt->bind_param("si", $category_name, $category_id);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Category updated successfully!');</script>";
            } else {
                echo "<script>alert('Error: Unable to update category.');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Please enter a category name.');</script>";
        }
    }
}

$all_categories_list = $conn->query("SELECT id, name, image FROM categories");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Categories - Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="Admin/MyKitchenMyTools Logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
body {
    font-family: 'Roboto', sans-serif;
    background-color: #121212;
    color: #e0e0e0;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: #1f1f1f;
    color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    position: fixed;
    width: 100%;
    z-index: 1000;
}

.logo-text-container {
    display: flex;
    align-items: center;
}

.logo-text-container img {
    height: 50px;
    margin-right: 15px;
}

.logo-text-container h4 {
    margin: 0;
    font-size: 26px;
    font-weight: 700;
    color: #fff;
}

.logo-text-container h6 {
    margin: 0;
    font-size: 18px;
    font-weight: 400;
    color: #bdbdbd;
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
    transition: transform 0.3s ease;
}

.sidebar a {
    display: block;
    padding: 15px 25px;
    color: #bdbdbd;
    text-decoration: none;
    transition: background-color 0.3s;
}

.sidebar a:hover {
    background-color: #333;
}

/* Content Area */
.content {
    margin-left: 270px;
    padding: 100px 20px;
    min-height: 100vh;
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    justify-content: center;
}

.form-container {
    background-color: #2b2b2b;
    padding: 30px;
    border-radius: 15px;
    max-width: 500px;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: stretch;
}

.form-container label {
    font-weight: bold;
    color: #fff;
    margin-bottom: 10px;
}

.form-container input[type="text"],
.form-container input[type="file"],
.form-container input[type="submit"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 10px;
    border: none;
    font-size: 16px;
}

.form-container input[type="file"] {
    background-color: #444;
    color: #fff;
    cursor: pointer;
}

.form-container input[type="file"]::-webkit-file-upload-button {
    background-color: #e53935;
    color: #fff;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.form-container input[type="file"]::-webkit-file-upload-button:hover {
    background-color: #d32f2f;
}

.form-container input[type="submit"] {
    background-color: #e53935;
    color: #fff;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
    margin-top: 10px;
}

.form-container input[type="submit"]:hover {
    background-color: #d32f2f;
}

.category-list {
    background-color: #1f1f1f;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.5);
    max-width: 900px;
    width: 100%;
    transition: background-color 0.3s, box-shadow 0.3s;
}

.category-list h3 {
    margin-bottom: 20px;
    font-size: 28px;
    color: #fff;
    text-align: center;
}

.category-list ul {
    list-style: none;
    padding: 0;
}

.category-list li {
    display: flex;
    align-items: flex-start;
    gap: 30px;
    background-color: #333;
    padding: 25px;
    margin-bottom: 25px;
    border-radius: 20px;
}

.category-list img {
    width: 90px;
    height: 90px;
    border-radius: 15px;
    object-fit: cover;
}

.category-list strong {
    font-size: 22px;
    color: #fff;
    align-self: center;
}

.form-container label {
    color: #fff;
}

/* Media Queries for Responsiveness */
@media (max-width: 768px) {
            .header {
                padding: 10px;
                flex-direction: column;
                align-items: flex-start;
            }

            .logo-text-container {
                margin-bottom: 10px;
            }

            .logout {
                margin-top: 20px;
                align-self: flex-start;
                position: absolute;
                top: 20px;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .menu-toggle {
                display: block;
                font-size: 24px;
                cursor: pointer;
                color: #fff;
                background: none;
                border: none;
                margin-left: 10px;
            }

            .content {
                margin-left: 0;
                width: 100%;
            }

            .form-container{
                width: 100px;
            }
        }

        @media (min-width: 769px) {
            .menu-toggle {
                display: none;
            }
        }

    </style>
</head>
<body>
    <header class="header">
        <div class="logo-text-container">
            <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <img src="Admin/MyKitchenMyTools Logo.png" alt="Company Logo" class="company-logo">
            <div>
                <h4>My Kitchen My Tools</h4>
                <h6>Admin Dashboard</h6>
            </div>
        </div>
        <a href="logout.php" class="logout">Logout</a>
    </header>

    <div class="sidebar" id="sidebar">
        <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="add_product.php"><i class="fas fa-plus"></i> Add Product</a>
        <a href="view_product.php"><i class="fas fa-eye"></i> View Product</a>
        <a href="update_product.php"><i class="fas fa-edit"></i> Update Product</a>
        <a href="delete_product.php"><i class="fas fa-trash-alt"></i> Delete Product</a>
        <a href="add_category.php"><i class="fas fa-plus-circle"></i> Add Category</a>
        <a href="view_category.php"><i class="fas fa-eye"></i> View Category</a>
        <a href="update_category.php"><i class="fas fa-edit"></i> Update Category</a>
        <a href="delete_category.php"><i class="fas fa-trash-alt"></i> Delete Category</a>
        <a href="change_background.php"><i class="fas fa-image"></i> Change Background</a>
    </div>

    <div class="content">
        <div class="category-list">
            <h3>All Categories</h3>
            <ul>
                <?php if ($all_categories_list->num_rows > 0): ?>
                    <?php while ($row = $all_categories_list->fetch_assoc()): ?>
                        <li>
                            <?php if ($row['image']): ?>
                                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Category Image">
                            <?php else: ?>
                                <img src="placeholder.jpg" alt="Placeholder Image">
                            <?php endif; ?>
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                            <form action="" method="POST" enctype="multipart/form-data" class="form-container">
                                <input type="hidden" name="category_id" value="<?php echo $row['id']; ?>">
                                <label for="category_name_<?php echo $row['id']; ?>">Category Name:</label>
                                <input type="text" id="category_name_<?php echo $row['id']; ?>" name="category_name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                                <label for="category_image_<?php echo $row['id']; ?>">Category Image:</label>
                                <input type="file" id="category_image_<?php echo $row['id']; ?>" name="category_image" accept="image/*">
                                <input type="submit" value="Update">
                            </form>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>No categories available</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }
    </script>
</body>
</html>
