<?php
ob_start(); // Start output buffering
require_once '../navbar.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';

use Classes\Database;
use Classes\User;

$db = new Database();
$user = new User($db->pdo);

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../profil/index.php');
    exit;
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = "admin"; // 'admin'

    try {
        $user->register($username, $password, $email, $role);
        $statusMessage = "<span class='success'>Admin registration successful!</span>";
    } catch (Exception $e) {
        $statusMessage = "<span class='error'>Registration failed: " . htmlspecialchars($e->getMessage()) . "</span>";
    }
}

ob_end_flush(); // Flush the output buffer
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <style>
        body {
            background-color: #262042;
            color: white;
        }

        main {
            padding: 20px 30px;
        }
    </style>
</head>

<body>
    <main>
        <form method="POST">
            <h1>REGISTER ADMIN</h1>
            <!-- Tampilkan pesan di sini -->
            <?php if (!empty($statusMessage)): ?>
                <div id="status-message"><?= $statusMessage; ?></div>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Username" required>
            <br>
            <input type="password" name="password" placeholder="Password" required>
            <br>
            <input type="email" name="email" placeholder="Email" required>
            <br>
            <div class="button-container">
                <button class="btn-register" type="submit" name="register">Register</button>
            </div>
        </form>
    </main>
</body>

</html>