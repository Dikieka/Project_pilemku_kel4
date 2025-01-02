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
            font-family: 'Segoe UI';
            background-repeat: no-repeat;
            height: 80vh;
            justify-items: center;
            background-color: #262042;
            color: white;
        }

        .form-registerAdmin {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            justify-content: center;
            text-align: center;
            color: white;
            width: 500px;
            height: 450px;
            padding: 30px;
            margin-top: 8vh;
        }

        .input {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            width: 470px;
            padding: 15px;
            transition: background-color 0.3s ease;
        }

        .input::placeholder {
            color: white;
        }

        .input:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        h1 {
            margin-bottom: 50px;
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
            flex: 1;
            /* Membuat tombol sama lebar */
            transition: background-color 0.3s ease;
        }

        .btn-register {
            background-color: #8D9AFC;
            transition: background-color 0.3s ease;
        }

        .btn-register:hover {
            background-color: #5A68D1;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            /* Mengatur jarak antara tombol */
            gap: 10px;
            /* Menambahkan jarak antar tombol */
            margin-top: 20px;
            /* Menambahkan jarak dari elemen sebelumnya */
        }

    </style>
</head>

<body>
    <form class="form-registerAdmin" method="POST">
        <h1>REGISTER ADMIN</h1>
        <!-- Tampilkan pesan di sini -->
        <?php if (!empty($statusMessage)): ?>
            <div id="status-message"><?= $statusMessage; ?></div>
        <?php endif; ?>
        <input class="input" type="text" name="username" placeholder="Username" required>
        <br>
        <input class="input" type="password" name="password" placeholder="Password" required>
        <br>
        <input class="input" type="email" name="email" placeholder="Email" required>
        <br>
        <div class="button-container">
            <button class="btn-register" type="submit" name="register">Register</button>
        </div>
    </form>
</body>

</html>