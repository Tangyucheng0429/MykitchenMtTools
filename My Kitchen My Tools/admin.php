<?php
if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    session_start();
    session_unset();
    session_destroy();
    session_start();
    echo "<script>
        alert('Session timed out, please log in again.');
        window.history.replaceState(null, null, window.location.pathname);
    </script>";
}

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    echo "<script>
        alert('Logout successful.');
        window.history.replaceState(null, null, window.location.pathname);
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Kitchen My Tools - Admin Login</title>
    <link rel="icon" type="image/x-icon" href="Admin/MyKitchenMyTools Logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('Admin/Background Photo.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .first_page {
            background-color: rgba(31, 31, 31, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .logo-text-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-text-container img {
            height: 50px;
            width: auto;
            margin-right: 15px;
        }

        .logo-text-container h4 {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .login-form input[type="text"],
        .login-form input[type="password"],
        .login-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
            box-sizing: border-box;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            background-color: #333;
            color: #fff;
        }

        .login-form input[type="submit"] {
            background-color: #e53935;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .login-form input[type="submit"]:hover {
            background-color: #d32f2f;
        }

        #togglePassword {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.3em;
            color: #bdbdbd;
        }
    </style>
</head>
<body>
    <section class="first_page">
        <div class="logo-text-container">
            <img src="Admin/MyKitchenMyTools Logo.png" alt="My Kitchen My Tools Logo">
            <h4>My Kitchen My Tools</h4>
        </div>

        <div class="login-form">
            <form action="admin_process.php" method="POST">
                <input type="text" name="username" placeholder="Admin Username" required>
                <div style="position: relative; width: 100%;">
                    <input type="password" id="password" name="password" placeholder="Admin Password" required>
                    <span id="togglePassword"><i class="fas fa-eye"></i></span>
                </div>
                <input type="submit" value="Login">
            </form>
        </div>
    </section>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    </script>
</body>
</html>
