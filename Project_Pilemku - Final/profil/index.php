<?php
require '../navbar.php';
require_once '../classes/Movie.php';

use Classes\Movie;

// Pastikan koneksi database sudah ada
$movie = new Movie($db->pdo);
$movies = $movie->getAllMovies();

// Mengelompokkan film berdasarkan genre
$genres = ['Action', 'Comedy', 'Drama', 'Thriller', 'Sci-Fi', 'Fantasi', 'Romance', 'Horror', 'Adventure', 'Animation'];
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

// Cek apakah user sudah login (gunakan session untuk validasi)
$isLoggedIn = isset($_SESSION['user']);
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

        body {
            background-color: #262042;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 50px;
        }

        /* Modal Popup */
        #loginModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        #loginModal div {
            background: #262042;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
        }

        #loginModal .btn-mdlLogin {
            background: #F11376;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        #loginModal .btn-mdlTutup {
            background: #B14BDF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        #loginModal .btn-mdlLogin:hover {
            background-color: #9C0046;
        }

        #loginModal .btn-mdlTutup:hover {
            background-color: #8214B5;
        }

        /* Hero Section */
        .hero-section {
            display: flex;
            margin-top: 60px;
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

        .btn-add {
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

        .btn-add:hover {
            background-color: #B85E14;
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
                    <a class="btn-filmlist" href="detail_film.php?id=<?= $movie['id'] ?>">
                        <img src="<?= htmlspecialchars($movie['gambar_poster']) ?>" alt="<?= htmlspecialchars($movie['judul']) ?>">
                        <p><?= htmlspecialchars($movie['judul']) ?></p>
                    </a>
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
                                    <a class="btn-add" href="add_watchlist.php?id=<?= $movie['id'] ?>">Add to List</a>
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

    <!-- Modal Popup -->
    <div id="loginModal">
        <div>
            <p>Untuk pengalaman lebih lanjut, silahkan login terlebih dahulu</p><br>
            <a href="../login.php"><button class="btn-mdlLogin">Login</button></a>
            <button class="btn-mdlTutup" id="closeModal">Tutup</button>
        </div>
    </div>

    <script>
        // Variabel untuk mengecek apakah pengguna sudah login
        let isLoggedIn = <?= json_encode($isLoggedIn); ?>;

        // Tambahkan event listener untuk tombol
        document.querySelectorAll('.btn-add, .btn-detail, .btn-filmlist').forEach(button => {
            button.addEventListener('click', (event) => {
                if (!isLoggedIn) {
                    event.preventDefault(); // Mencegah navigasi ke halaman lain
                    document.getElementById('loginModal').style.display = 'flex';
                }
            });
        });

        // Tutup modal
        document.getElementById('closeModal').addEventListener('click', () => {
            document.getElementById('loginModal').style.display = 'none';
        });
    </script>
</body>

</html>
