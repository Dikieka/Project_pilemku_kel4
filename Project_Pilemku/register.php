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
    $role = "user"; // 'user'

    try {
        $user->register($username, $password, $email, $role);
        $statusMessage = "<span class='success'>Pendaftaran berhasil! Silakan login.</span>";
    } catch (Exception $e) {
        $statusMessage = "<span class='error'>Pendaftaran gagal: " . htmlspecialchars($e->getMessage()) . "</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilemku</title>
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
            height: 500px;
            padding: 30px;
            margin-top: 5vh;
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

        select {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #F52955;
            color: white;
            padding: 10px;
            transition: background-color 0.3s ease;
        }

        select:hover {
            background-color: #9C0046;
        }

        option {
            background-color: #262042;
            color: white;
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
        <h1>REGISTER</h1>
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
            <a href="login.php"><button type="button">Login</button></a>
            <button class="btn-register" type="submit" name="register">Register</button>
        </div>
    </form>
</body>

</html>