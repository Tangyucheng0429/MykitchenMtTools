<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mykitchenmytools_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: admin_dashboard.php");
        exit(); 
    } else {
        echo "<script>alert('Invalid Username or Password'); window.location.href = 'admin.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
