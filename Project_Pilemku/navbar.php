<?php
require_once 'classes/Database.php';
require_once 'classes/User.php';

session_start();

use Classes\Database;
use Classes\User;

$db = new Database();
$user = new User($db->pdo);

// Mengecek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']);
$currentUser = $isLoggedIn ? $user->getCurrentUser($_SESSION['user_id']) : null;

$homeLink = 'index.php';
if ($isLoggedIn) {
    $homeLink = ($currentUser['role'] === 'admin') ? 'admin_dashboard.php' : 'user_dashboard.php';
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
            margin: 0 auto;
            font-family: 'Segoe UI';
        }

        nav {
            display: flex;
            justify-content: space-between;
            justify-items: center;
            padding: 12px 30px 10px 30px;
            background-color: #131127;
            color: white;
        }

        .logo {
            width: 23px;
            cursor: pointer;
        }

        h2 {
            font-family: 'Segoe UI Black';
            font-style: italic;
            margin: 0 auto;
        }

        ul {
            display: flex;
            list-style: none;
            gap: 30px;
            margin: 0;
            padding: 0;
        }

        .nav-logo {
            display: flex;
            gap: 5px;
        }

        ul li a {
            color: white;
            text-decoration: none;
        }

        .search-bar {
            padding: 10px;
            width: 400px;
            border: none;
            color: whitesmoke;
            border-radius: 10px;
            margin: 5px 0px 5px 0px;
            padding-left: 10px;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .nav-search {
            margin-left: 30px;
        }

        .search-bar::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .btn-login {
            font-weight: bold;
            color: white;
            height: 30px;
            width: 100px;
            margin: 5px;
            border: none;
            border-radius: 10px;
            background-color: #F11376;
            cursor: pointer;
            transition: background-color 0.4s ease;
        }

        .btn-nav {
            padding: 5px;
        }

        .btn-logout a {
            background-color: #F52955;
            /* Warna latar belakang tombol logout */
            transition: background-color 0.4s ease;
        }

        .btn-logout a:hover {
            background-color: #9C0046;
            /* Warna latar saat di-hover */
        }

        .btn-nav:hover {
            text-decoration: underline;
            text-underline-offset: 5px;
            color: #F11376;
        }

        .btn-login:hover {
            background-color: #9C0046;
        }


        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            padding: 10px;
            z-index: 1;
            border-radius: 5px;
            display: none;
            /* Menggunakan Grid Layout */
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            /* Kolom dinamis */
            max-height: 300px;
            /* Maksimal tinggi dropdown */
            overflow-y: auto;
            /* Scroll jika item terlalu banyak */
            gap: 10px;
            width: 400px;
            /* Lebar dropdown */
        }

        .dropdown-content a {
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            text-align: center;
            background-color: #B14BDF;
            border-radius: 3px;
            white-space: nowrap;
            transition: background-color 0.3s ease;
        }

        .dropdown-content a:hover {
            background-color: #8214B5;
        }

        .profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }

        .profile-dropdown {
            position: absolute;
            right: 0;
            /* Muncul dari sisi kanan */
            background-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            padding: 10px;
            z-index: 1;
            border-radius: 5px;
            display: none;
            /* Sembunyikan secara default */
            width: 200px;
            /* Atur lebar dropdown */
        }

        .profile-dropdown a {
            display: block;
            color: white;
            gap: 10px;
            border-radius: 5px;
            padding: 5px 10px;
            text-decoration: none;
            text-align: center;
        }
    </style>
</head>

<body>
    <nav>
        <ul class="nav-logo">
            <li><img class="logo" src="uploads/fotoProfile/Logo_Pilemku.png" alt=""></li>
            <li>
                <h2>Pilemku</h2>
            </li>
            <ul class="nav-search">
                <li>
                    <input class="search-bar" type="text" placeholder="Search...">
                </li>
            </ul>
        </ul>
        <ul>
            <li class="btn-nav"><a href="<?= $homeLink ?>">Home</a></li>
            <div class="dropdown">
                <li class="btn-nav"><a href="#" id="genre-menu">Genre</a></li>
                <div class="dropdown-content" id="genre-dropdown">
                    <a href="#">Action</a>
                    <a href="#">Comedy</a>
                    <a href="#">Drama</a>
                    <a href="#">Thriller</a>
                    <a href="#">Sci-Fi</a>
                    <a href="#">Fantasy</a>
                    <a href="#">Romance</a>
                    <a href="#">Horror</a>
                    <a href="#">Adventure</a>
                    <a href="#">Animation</a>
                    <a href="#">Documentary</a>
                </div>
            </div>
            <?php if ($currentUser && $currentUser['role'] === 'user'): ?>
                <li class="btn-nav"><a href="mylist.php">Mylist</a></li>
            <?php endif; ?>
            <?php if ($currentUser && $currentUser['role'] === 'admin'): ?>
                <li class="btn-nav"><a href="tambah_admin.php">Tambah Admin</a></li>
            <?php endif; ?>
            <?php if ($isLoggedIn): ?>
                <div class="dropdown">
                    <?php
                    $profilePicture = (!empty($currentUser['profile_picture']) && file_exists($currentUser['profile_picture']))
                        ? htmlspecialchars($currentUser['profile_picture'])
                        : 'uploads/fotoProfile/default.jpg';
                    ?>
                    <img src="<?= $profilePicture ?>" alt="Profile" class="profile-picture" id="profile-menu">
                    <div class="profile-dropdown" id="profile-dropdown">
                        <a href="edit_profile.php">
                            <?= htmlspecialchars($currentUser['username']); ?>
                            <br>
                            <?= htmlspecialchars($currentUser['email']); ?>
                        </a>
                        <span class="btn-logout"><a href="logout.php">Logout</a></span>
                    </div>
                </div>
            <?php else: ?>
                <li><a href="login.php"><button class="btn-login">Login</button></a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Genre Dropdown
            const genreMenu = document.getElementById('genre-menu');
            const genreDropdown = document.getElementById('genre-dropdown');

            genreMenu.addEventListener('click', (event) => {
                event.preventDefault();
                genreDropdown.style.display =
                    genreDropdown.style.display === 'grid' ? 'none' : 'grid';
            });

            document.addEventListener('click', (event) => {
                if (!genreMenu.contains(event.target) &&
                    !genreDropdown.contains(event.target)) {
                    genreDropdown.style.display = 'none';
                }
            });

            // Profile Dropdown
            const profileMenu = document.getElementById('profile-menu');
            const profileDropdown = document.getElementById('profile-dropdown');

            profileMenu.addEventListener('click', (event) => {
                event.preventDefault();
                profileDropdown.style.display =
                    profileDropdown.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', (event) => {
                if (!profileMenu.contains(event.target) &&
                    !profileDropdown.contains(event.target)) {
                    profileDropdown.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>