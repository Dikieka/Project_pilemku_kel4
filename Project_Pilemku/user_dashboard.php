<?php
require 'navbar.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

use Classes\Database;
use Classes\User;

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$user = new User($db->pdo);
$currentUser = $user->getCurrentUser($_SESSION['user_id']);

// Pastikan hanya user yang dapat mengakses halaman ini
if ($currentUser['role'] !== 'user') {
    header('Location: admin_dashboard.php');
    exit;
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
            background-color: #262042;
        }
    </style>
</head>

<body>
    <h1>Welcome, <?php echo "{$currentUser['username']}"; ?></h1>
</body>

</html>