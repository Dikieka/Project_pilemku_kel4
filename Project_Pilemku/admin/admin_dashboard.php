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
    header('Location: ../login.php');
    exit;
}

$db = new Database();

$user = new User($db->pdo);
$currentUser = $user->getCurrentUser($_SESSION['user_id']);

$movie = new Movie($db->pdo);
$movies = $movie->getAllMovies();

// Pastikan hanya admin yang dapat mengakses halaman ini
if ($currentUser['role'] !== 'admin') {
    header('Location: user_dashboard.php');
    exit;
}

// Handle delete request 
if (isset($_GET['delete_id'])) {
    $movie->deleteMovie($_GET['delete_id']);
    header('Location: admin_dashboard.php');
    exit;
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
        body {
            background-color: #262042;
        }

        main {
            padding: 20px 30px;
            display: flex;
            flex-direction: column;
        }

        main h1 {
            color: white;
        }

        main a {
            padding: 5px 10px;
            width: fit-content;
            background-color: #F11376;
            text-align: center;
            font-weight: bold;
            border: 2px solid white;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            transition: 0.4s;
        }

        main a:hover {
            background-color: #131127;
            border: 2px solid #F11376;
            color: white;
        }

        .container {
            padding: 30px 0;
            display: flex;
            flex-direction: row;
            gap: 20px 30px;
        }

        .container .card {
            padding: 5px 10px;
            width: 200px;
            height: 350px;
            border: 2px solid white;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container .card h1{
            text-align: center;
            font-size: x-large;
        }

        .container .card img {
            height: 200px;
            width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <main>
        <a href="tambah_film.php">Tambah Film</a>
        <div class="container">
            <?php foreach ($movies as $movie): ?>
                <div class="card">
                    <h1><?= htmlspecialchars($movie['judul']) ?></h1>
                    <img src="<?= htmlspecialchars($movie['gambar_poster']) ?>">
                    <div class="card-body">
                        <a href="detail_film.php?id=<?= $movie['id'] ?>">Detail</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>

</html>