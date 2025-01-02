<?php
ob_start(); // Start output buffering
require '../navbar.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Movie.php';

use Classes\Database;
use Classes\Movie;
use Classes\User;

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../profil/index.php');
    exit;
}

$db = new Database();

$user = new User($db->pdo);
$currentUser = $user->getCurrentUser($_SESSION['user_id']);

$movie = new Movie($db->pdo);
$movies = $movie->getAllMovies();

// Pastikan hanya admin yang dapat mengakses halaman ini
if ($currentUser['role'] !== 'admin') {
    header('Location: user/user_dashboard.php');
    exit;
}

// Mengelompokkan film berdasarkan genre
$genres = ['Action', 'Comedy', 'Drama', 'Thriller', 'Sci-Fi', 'Fantasi', 'Romance', 'Horror', 'Adventure', 'Animation','Documentary', 'Zombie'];
$moviesByGenre = [];

foreach ($movies as $movie) {
    // Membagi genre berdasarkan koma
    $movieGenres = explode(', ', $movie['genre']);
    foreach ($movieGenres as $genre) {
        if (!isset($moviesByGenre[$genre])) {
            $moviesByGenre[$genre] = [];
        }
        $moviesByGenre[$genre][] = $movie;
    }
}

ob_end_flush(); // Flush the output buffer
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilemku</title>
    <style>
         /* Global styles */
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }
        
        
        body {
            background-color: #262042;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        main {
            padding: 30px;
        }
        
        /* Hero Section */
        .hero-section {
            padding-left: 10px;
            padding-right: 10px;
            display: flex;
            gap: 30px;
        }
        
        .hero-slider {
            width: 70%;
            overflow: hidden;
            position: relative;
        }
        
        .slider-container {
            display: flex;
            animation: slide 20s infinite;
        }
        
        .slider-item {
            width: 100%;
            flex-shrink: 0;
            position: relative;
        }
        
        .slider-item img {
            width: 100%;
            height: 550px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .slider-item p {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 24px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            color: white;
        }
        
        /* Side films */
        .side-films {
            width: 40%; /* Lebar 40% dari area hero-section */
            display: flex;
            flex-wrap: wrap; /* Membungkus item ke baris baru */
            gap: 20px;
            max-height: 580px; /* Batasi tinggi untuk 2 baris */
            overflow-y: auto; /* Agar bisa scroll jika ada lebih dari 2 baris */
        }

        /* Film item (thumbnail) */
        .film-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 30%; /* Membatasi lebar film item menjadi 30% */
            min-width: 120px; /* Mengatur lebar minimum */
            margin-bottom: 20px; /* Memberikan jarak antara baris */
        }

        .film-item img {
            width: 160px;
            height: 230px;
            object-fit: cover;
            border-radius: 8px;
        }

        .film-item p {
            text-align: center;
            font-weight: bold;
            margin-top: 5px;
            color: white;
        }

        
        /* Genre Section */
        .container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 30px;
        }

        .container-card {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            background-color: rgba(225, 225, 225, 0.05);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border: none;
            border-radius: 10px;
            padding: 30px 10px 30px 10px;
        }
        
        .container .card {
            width: 190px;
            height: 350px;
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .container .card img {
            height: 250px;
            width: 190px;
            margin-bottom: 5px;
        }
        
        .container h1 {
            font-weight: bolder;
            font-size: 30pt;
            margin-left: 67px;
        }

        .i {
            color: #B14BDF;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            gap: 10px;
        }

        .card p {
            margin-bottom: 20px;
        }

        .btn-detail {
            padding: 5px 10px 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            background-color: #F11376;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-edit {
            padding: 5px 10px 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            background-color: #F2974C;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-detail:hover {
            background-color: #9C0046;
        }

        .btn-edit:hover {
            background-color: #B85E14;
        }

        /* Footer */
        footer {
            background-color: #1A132B;
            padding: 20px 0;
            text-align: center;
            color: white;
            
        }

        .footer-container {
           width: 100%;
           margin: auto;
           padding-left: 30px;
           padding-right: 30px;
           display: flex;
           flex-wrap: wrap;
           justify-content: space-between;
           align-items: center;
           gap: 20px;
        }

        .footer-logo {
           flex: 1;
           text-align: left;
        }

        .footer-logo img {
            width: 30%;
        }

        .footer-logo h2 {
           margin: 0;
           font-size: 24px;
           color: #B14BDF;
        }

        .footer-logo p {
           font-size: 14px;
           margin: 5px 0 0;
           color: #B3B3B3;
        }

        .footer-links {
           flex: 2;
           display: flex;
           justify-content: center;
           gap: 30px;
        }

        .footer-links a {
           text-decoration: none;
           color: #FFFFFF;
           font-size: 16px;
        }

        .footer-social {
           flex: 1;
           text-align: right;
        }

        .footer-social a {
           margin: 0 10px;
           text-decoration: none;
           color: #B14BDF;
        }

        .footer-social img {
           width: 30px;
           height: 30px;
        }

        .footer-bottom {
           margin-top: 20px;
           border-top: 1px solid #333333;
           padding-top: 10px;
           font-size: 14px;
           color: #B3B3B3;
        }
        
        /* Slider Animation */
        @keyframes slide {
            0% { transform: translateX(0); }
            33.33% { transform: translateX(-100%); }
            66.66% { transform: translateX(-200%); }
            100% { transform: translateX(0); }
        }

    </style>
</head>

<body>
    <main>
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-slider">
                <div class="slider-container">
                    <?php foreach ($movies as $movie): ?>
                        <div class="slider-item">
                            <img src="<?= htmlspecialchars($movie['gambar_poster']) ?>" alt="<?= htmlspecialchars($movie['judul']) ?>">
                            <p><?= htmlspecialchars($movie['judul']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="side-films">
                <?php foreach ($movies as $movie): ?>
                    <div class="film-item">
                        <img src="<?= htmlspecialchars($movie['gambar_poster']) ?>" alt="<?= htmlspecialchars($movie['judul']) ?>">
                        <p><?= htmlspecialchars($movie['judul']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Genres Section -->
        <div class="container">
            <?php foreach ($genres as $genre): ?>
                <h1><span class="i">I</span> <?= htmlspecialchars($genre) ?></h1>
                <div id="<?= $genre ?>" class="container-card">
                    <?php if (isset($moviesByGenre[$genre])): ?>
                        <?php foreach ($moviesByGenre[$genre] as $movie): ?>
                            <div class="card">
                                <img src="<?= htmlspecialchars($movie['gambar_poster']) ?>" alt="Poster">
                                <div class="card-body">
                                    <p><?= htmlspecialchars($movie['judul']) ?></p>
                                </div>
                                <div class="btn-card">
                                    <a class="btn-edit" href="edit_film.php?id=<?= $movie['id'] ?>">Edit</a>
                                    <a class="btn-detail" href="detail_film.php?id=<?= $movie['id'] ?>">Detail</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No movies found in this genre.</p>
                    <?php endif; ?>
                </div>
                <br>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <!-- Logo -->
            <div class="footer-logo">
                <img src="../uploads/img/Logo_Pilemku1.png" alt="">
                <p>Tempat terbaik untuk mencari referensi film terpopuler</p>
            </div>

            <!-- Links -->
            <div class="footer-links">
                <a href="<?= $homeLink ?>">Home</a>
                <a href="#Action">Genres</a>
                <a href="#">Contact</a>
                <a href="#">About</a>
            </div>

            <!-- Social Media -->
            <div class="footer-social">
                <a href="https://facebook.com" target="#">
                <img src="../uploads/img/facebook.png" alt="">
                </a>
                <a href="https://twitter.com" target="#">
                <img src="../uploads/img/twitter.png" alt="">
                </a>
                <a href="https://instagram.com" target="#">
                <img src="../uploads/img/instagram.png" alt="">
                </a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?= date('Y') ?> Pilemku. All rights reserved.
        </div>
    </footer>

    <script src="script.js">
        // Script to handle the slide effect for the hero slider
            let index = 0;
            const slides = document.querySelectorAll('.slider-item');

            function autoSlide() {
                    index++;
                    if (index >= slides.length) {
                    index = 0;
                }
                document.querySelector('.slider-container').style.transform = `translateX(-${index * 100}%)`;
            }

            setInterval(autoSlide, 5000); // Change slide every 5 seconds
    </script>
</body>

</html>
