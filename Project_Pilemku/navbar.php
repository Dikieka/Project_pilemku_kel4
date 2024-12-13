<?php
require_once 'classes/Database.php';
require_once 'classes/User.php';

use Classes\Database;
use Classes\User;

session_start();
$db = new Database();
$user = new User($db->pdo);

// Mengecek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']);
$currentUser = $isLoggedIn ? $user->getCurrentUser($_SESSION['user_id']) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilemku</title>
    <style>
        body {
            margin: 0 auto;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: white;
        }

        h1 {
            margin: 0;
        }

        ul {
            display: flex;
            list-style: none;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        ul li a {
            color: white;
            text-decoration: none;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            padding: 10px;
            z-index: 1;
            border-radius: 5px;
        }

        .dropdown-content a {
            display: block;
            color: black;
            padding: 5px 10px;
            text-decoration: none;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <nav>
        <h1>PILEMKU</h1>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php">Movies</a></li>
            <li><a href="index.php">New & Popular</a></li>
        </ul>
        <ul>
            <?php if ($isLoggedIn): ?>
                <div class="dropdown">
                    <!-- Menampilkan Foto Profil -->
                    <img src="<?= htmlspecialchars($currentUser['profile_picture']) ?>" alt="Profile" class="profile-picture">
                    <div class="dropdown-content">
                        <a href="edit_profile.php">Edit Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>
