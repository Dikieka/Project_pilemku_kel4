<?php
ob_start(); // Start output buffering
require '../navbar.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Watchlist.php';

use Classes\Database;
use Classes\User;
use Classes\Watchlist;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../profil/index.php');
    exit;
}

$db = new Database();
$user = new User($db->pdo);

$currentUser = $user->getCurrentUser($_SESSION['user_id']);

$watchlist = new Watchlist($db->pdo);

// Get watchlist type from query parameter
$tipe = isset($_GET['tipe']) ? $_GET['tipe'] : 'watching';

// Get watchlist data for the logged-in user
$userWatchlist = $watchlist->getUserWatchlist($_SESSION['user_id']);

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Watchlist - <?= ucfirst($tipe) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #262042;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-left: 30px;
        }

        main {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        h1 {
            font-weight: bold;
            font-size: 24pt;
            color: white;
            margin-left: 100px;
        }

        /* Container for cards */
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            width: 190px;
            height: 350px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .card img {
            width: 190px;
            height: 250px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            gap: 10px;
            text-align: center;
        }

        .card p {
            margin-top: 5px;
            font-size: 14pt;
        }

        .btn-edit {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            background-color: #F11376;
            color: white;
        }

        .btn-edit:hover {
            background-color: #B10F5D;
        }

        @media (max-width: 768px) {
            .container {
                justify-content: center;
            }

            .card {
                width: 160px;
                height: 300px;
            }

            .card img {
                width: 160px;
                height: 200px;
            }
        }
    </style>
</head>

<body>
    <main>
        <h1><span style="color: #B14BDF">I</span> My Watchlist - <?= ucfirst($tipe) ?></h1>
        <div class="container">
                <?php foreach ($userWatchlist[$tipe] as $movie): ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($movie['gambar_poster']) ?>" alt="<?= htmlspecialchars($movie['judul']) ?>">
                        <p><?= htmlspecialchars($movie['judul']) ?></p>
                        <div class="card-body">
                            <a class="btn-edit" href="detail_film.php?id=<?= $movie['id'] ?>">Detail</a>
                        </div>
                    </div>
                <?php endforeach; ?>
        </div>
    </main>

    <script>
        function confirmDelete(movieId) {
            if (confirm('Are you sure you want to remove this movie from your watchlist?')) {
                document.getElementById('delete-form-' + movieId).submit();
            }
        }
    </script>

</body>

</html>
