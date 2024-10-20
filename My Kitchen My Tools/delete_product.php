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

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("Location: view_product.php?deleted=1");
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
