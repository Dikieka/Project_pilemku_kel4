<?php
ob_start();
require '../navbar.php';
require_once '../classes/Database.php';
require_once '../classes/Movie.php';
require_once '../classes/Watchlist.php';

use Classes\Database;
use Classes\Movie;
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
$movie = new Movie($db->pdo);
$watchlist = new Watchlist($db->pdo);

$movieData = $movie->getMovieById($_GET['id']);

// Handle watchlist type update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['watchlist_id'], $_POST['new_type'])) {
    $watchlist->updateWatchlistType($_SESSION['user_id'], $_POST['watchlist_id'], $_POST['new_type']);
    header('Location: detail_film.php?id=' . $_GET['id']);
    ob_end_flush();
    exit;
}

// Get current watchlist entry
$currentWatchlistEntry = $watchlist->isMovieInWatchlist($_SESSION['user_id'], $_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Watchlist</title>
    <style>
        body{
            background-color: #262042;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        main {
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 75px;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #F2974C;
            text-align: center;
        }

        p {
            font-size: 18px;
            text-align: center;
        }

        .img-editWatchlist {
            width: 200px;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        .form-editWatchlist {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        .label-menu {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .tipe-list {
            width: 80%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #131127;
            color: white;
            font-size: 14px;
        }

        .btn-update {
            width: 80%;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #F2974C;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-kembali {
            width: 480px;
            padding: 10px 20px;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #F11376;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-update:hover {
            background-color: #B85E14;
        }

        .btn-kembali:hover {
            background-color: #9C0046;
        }


    </style>
</head>

<body>
    <div class="container">
    <main>
        <form class="form-editWatchlist" method="POST">
            <input type="hidden" name="watchlist_id" value="<?= htmlspecialchars($currentWatchlistEntry['id']) ?>">
            <p><strong><?= htmlspecialchars(string: $movieData['judul']) ?></strong></p>
            <img class="img-editWatchlist" src="<?= htmlspecialchars($movieData['gambar_poster']) ?>"><br>
            <label class="label-menu" for="new_type">Update Watchlist Type:</label><br>
            <select class="tipe-list" name="new_type" id="new_type" required>
                <option value="watching" <?= $currentWatchlistEntry['tipe_watchlist'] == 'watching' ? 'selected' : '' ?>>Watching</option>
                <option value="completed" <?= $currentWatchlistEntry['tipe_watchlist'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="on-hold" <?= $currentWatchlistEntry['tipe_watchlist'] == 'on-hold' ? 'selected' : '' ?>>On-Hold</option>
                <option value="dropped" <?= $currentWatchlistEntry['tipe_watchlist'] == 'dropped' ? 'selected' : '' ?>>Dropped</option>
                <option value="plan-to-watch" <?= $currentWatchlistEntry['tipe_watchlist'] == 'plan-to-watch' ? 'selected' : '' ?>>Plan to Watch</option>
            </select><br>
            <button class="btn-update" type="submit">Update Watchlist</button>
            <div class="links">
                <a href="detail_film.php?id=<?= $movieData['id'] ?>"><button class="btn-kembali">Kembali</button></a>
                </div>
        </form>
    </main>
    </div>
</body>

</html>