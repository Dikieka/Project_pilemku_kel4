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

$homeLink = '../profil/index.php';
if ($isLoggedIn) {
    $homeLink = ($currentUser['role'] === 'admin') ? '../admin/admin_dashboard.php' : '../user/user_dashboard.php';
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
    $searchResults = $user->searchMovies($searchQuery);
} else {
    $searchResults = []; // Kosongkan hasil jika tidak ada pencarian
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilemku</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         body {
            margin: 0 auto;
            font-family: 'Segoe UI';
            padding-top: 80px;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 10px 10px 10px;
            background-color: #131127;
            color: white;
            position: fixed; /* Navbar tetap di atas */
            top: 0; /* Posisi di bagian atas layar */
            left: 0;
            width: 100%; /* Memastikan lebar penuh */
            z-index: 1000; /* Supaya navbar berada di depan elemen lain */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan untuk efek estetika */
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
            margin-left: 40px;
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

        /* Wrapper untuk search bar */
        .search-container {
            position: relative;
            display: inline-block;
            width: 400px; /* Lebar search bar */
        }

        /* Gaya untuk kotak input */
        .search-container .search-bar {
            width: 100%; /* Agar input menyesuaikan container */
            padding: 10px 0 10px 15px; /* Ruang untuk ikon di sebelah kanan */
            border: none;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            color: whitesmoke;
        }

        /* Placeholder styling */
        .search-container .search-bar::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Gaya untuk ikon pencarian */
        .search-container .fa-search {
            position: absolute;
            right: 15px; /* Jarak dari tepi kanan */
            top: 50%;
            transform: translateY(-50%); /* Pusatkan ikon secara vertikal */
            color: rgba(255, 255, 255, 0.7); /* Warna ikon */
            pointer-events: none; /* Agar ikon tidak dapat diklik */
        }

        .btn-login {
            font-weight: bold;
            color: white;
            height: 30px;
            width: 100px;
            margin: 5px;
            margin-right: 40px;
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
            background-color: rgba(255, 255, 255, 0.5);
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
            width: 600px;
            /* Lebar dropdown */
        }

        .dropdown-content a {
            color: white;
            padding: 10px 0px;
            text-decoration: none;
            text-align: center;
            background-color: #262042;
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
            margin-right: 40px;
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
            <li><img class="logo" src="../uploads/img/Logo_Pilemku.png" alt="Logo"></li>
            <li>
                <h2>Pilemku</h2>
            </li>
            <ul class="nav-search">
                <li>
                <form method="GET" action="../user/search.php">
                    <div class="search-container">
                            <input class="search-bar" type="text" name="search" placeholder="Search...">
                            <i class="fas fa-search"></i>
                    </div>
             </form>
        
                </li>
            </ul>
        </ul>
        <ul>
            <li class="btn-nav"><a href="<?= $homeLink ?>"><i class="fas fa-home"></i> Home</a></li>
            <div class="dropdown">
                <li class="btn-nav"><a href="#" id="genre-menu"><i class="fas fa-film"></i> Genre</a></li>
                <div class="dropdown-content" id="genre-dropdown">
                    <a href="#Action"><i class="fas fa-fist-raised"></i> Action</a>
                    <a href="#Comedy"><i class="fas fa-laugh"></i> Comedy</a>
                    <a href="#Drama"><i class="fas fa-theater-masks"></i> Drama</a>
                    <a href="#Thriller"><i class="fas fa-skull-crossbones"></i> Thriller</a>
                    <a href="#Sci-Fi"><i class="fas fa-robot"></i> Sci-Fi</a>
                    <a href="#Fantasi"><i class="fas fa-hat-wizard"></i> Fantasy</a>
                    <a href="#Romance"><i class="fas fa-heart"></i> Romance</a>
                    <a href="#Horror"><i class="fas fa-ghost"></i> Horror</a>
                    <a href="#Adventure"><i class="fas fa-map"></i> Adventure</a>
                    <a href="#Animation"><i class="fas fa-film"></i> Animation</a>
                    <a href="#Documentary"><i class="fas fa-book"></i> Documentary</a>
                    <a href="#Zombie"><i class="fa-solid fa-biohazard"></i> Zombie</a>
                </div>
            </div>
            <?php if ($currentUser && $currentUser['role'] === 'user'): ?>
                <div class="dropdown">
                    <li class="btn-nav"><a href="#" id="mylist-menu"><i class="fas fa-list"></i> Mylist</a></li>
                    <div class="dropdown-content" id="mylist-dropdown">
                        <a href="mylist.php?tipe=watching"><i class="fas fa-play-circle"></i> Watching</a>
                        <a href="mylist.php?tipe=completed"><i class="fas fa-check-circle"></i> Completed</a>
                        <a href="mylist.php?tipe=on-hold"><i class="fas fa-pause-circle"></i> On-Hold</a>
                        <a href="mylist.php?tipe=dropped"><i class="fas fa-times-circle"></i> Dropped</a>
                        <a href="mylist.php?tipe=plan-to-watch"><i class="fas fa-calendar-alt"></i> Plan-to-Watch</a>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($currentUser && $currentUser['role'] === 'admin'): ?>
                <li class="btn-nav"><a href="../admin/tambah_admin.php"><i class="fas fa-user-plus"></i> Tambah Admin</a></li>
                <li class="btn-nav"><a href="../admin/tambah_film.php"><i class="fa-solid fa-cloud-arrow-up"></i> Tambah Film</a></li>
            <?php endif; ?>
            <?php if ($currentUser): ?>
                <div class="dropdown">
                    <?php
                    // Ambil jalur foto profil langsung dari database
                    $profilePicture = (!empty($currentUser['profile_picture']) && file_exists($currentUser['profile_picture']))
                    ? htmlspecialchars($currentUser['profile_picture']) . '?t=' . time()
                    : '../uploads/img/default.jpg';
                    ?>
                    <img src="<?= $profilePicture ?>" alt="Profile" class="profile-picture" id="profile-menu">
                    <div class="profile-dropdown" id="profile-dropdown">
                        <a href="../profil/edit_profile.php">
                            <?= htmlspecialchars($currentUser['username']); ?><br>
                            <?= htmlspecialchars($currentUser['email']); ?>
                        </a>
                        <span class="btn-logout"><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></span>
                    </div>
                </div>
            <?php else: ?>
                <li><a href="../login.php"><button class="btn-login">Login</button></a></li>
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

            // Mylist Dropdown
            const mylistMenu = document.getElementById('mylist-menu');
            const mylistDropdown = document.getElementById('mylist-dropdown');

            if (mylistMenu && mylistDropdown) {
                mylistMenu.addEventListener('click', (event) => {
                    event.preventDefault();
                    mylistDropdown.style.display =
                        mylistDropdown.style.display === 'grid' ? 'none' : 'grid';
                });

                document.addEventListener('click', (event) => {
                    if (!mylistMenu.contains(event.target) &&
                        !mylistDropdown.contains(event.target)) {
                        mylistDropdown.style.display = 'none';
                    }
                });
            }

            // Profile Dropdown
            const profileMenu = document.getElementById('profile-menu');
            const profileDropdown = document.getElementById('profile-dropdown');

            if (profileMenu && profileDropdown) {
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
            }
        });
    </script>
</body>

</html>
