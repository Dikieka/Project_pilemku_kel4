<?php
require 'classes/Database.php';
require 'classes/User.php';

use Classes\Database;
use Classes\User;

$db = new Database();
$user = new User($db->pdo);

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role']; // 'admin' atau 'user'

    try {
        $user->register($username, $password, $email, $role);
        echo "Pendaftaran berhasil!";
    } catch (Exception $e) {
        echo "Pendaftaran gagal: " . $e->getMessage();
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
    <h1>REGISTER</h1>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <br>
    <input type="password" name="password" placeholder="Password" required>
    <br>
    <input type="email" name="email" placeholder="Email" required>
    <br>
    <select name="role">
        <option value="">role</option>
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
    <br>
    <button type="submit" name="register">Register</button>
</form>
</body>
</html>

