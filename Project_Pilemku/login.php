<?php
require 'classes/Database.php';
require 'classes/User.php';

use Classes\Database;
use Classes\User;

session_start();
$db = new Database();
$user = new User($db->pdo);

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loggedInUser = $user->login($username, $password);
    if ($loggedInUser) {
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['role'] = $loggedInUser['role'];
        $_SESSION['username'] = $loggedInUser['username'];

        if ($loggedInUser['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
    } else {
        $_SESSION['error_message'] = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: 'Segoe UI';
            background-image: linear-gradient(to right top, #131127, #16142c, #191731, #1d1936, #211c3b, #2e2148, #3e2555, #4f2960, #752b70, #9e287a, #c81f7c, #f11376);
            background-repeat: no-repeat;
            height: 100vh;
            justify-items: center;
        }

        form {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            justify-content: center;
            text-align: center;
            color: white;
            width: 400px;
            height: 400px;
            padding: 30px;
            margin-top: 10vh;
        }

        input {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            width: 370px;
            padding: 15px;
            transition: background-color 0.3s ease;
        }

        input::placeholder {
            color: white;
        }

        input:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        button {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #B14BDF;
            padding: 15px;
            color: white;
            font-weight: bold;
            font-size: 14pt;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #8214B5;
        }

        a {
            text-decoration: underline;
            color: #8D9AFC;
        }

        .logo {
            width: 10%;
            cursor: pointer;
            padding: 20px;
        }
    </style>
</head>
<body>
<img class="logo" src="uploads/fotoProfile/Logo_Pilemku1.png" alt="">
<form method="POST">
<h1>LOGIN</h1>
<!-- Display error message if there is one -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <input type="text" name="username" placeholder="Username" required>
    <br>
    <input type="password" name="password" placeholder="Password" required>
    <p>jika anada belum punya akun <a href="register.php">daftar disini</a></p>
    <br>
    <button type="submit" name="login">Login</button>
</form>
</body>
</html>