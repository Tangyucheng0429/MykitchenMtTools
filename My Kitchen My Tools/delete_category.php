<?php
session_start();

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

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        echo "<script>alert('Category deleted successfully!'); window.location.href='view_category.php';</script>";
    } else {
        echo "<script>alert('Error: Could not delete the category.'); window.location.href='view_category.php';</script>";
    }

    $stmt->close();
} else {
    echo "No category ID provided.";
    exit();
}

$conn->close();
?>
