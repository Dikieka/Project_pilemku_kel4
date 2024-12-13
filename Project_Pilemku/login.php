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
        echo "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>LOGIN</h1>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <br>
    <input type="password" name="password" placeholder="Password" required>
    <p>jika anada belum punya akun <a href="register.php">daftar disini</a></p>
    <br>
    <button type="submit" name="login">Login</button>
</form>
</body>
</html>
