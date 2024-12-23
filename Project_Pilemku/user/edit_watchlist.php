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
        body {
            background-color: #262042;
            color: white;
        }

        main {
            padding: 20px 30px;
        }

        a {
            color: white;
        }
    </style>
</head>

<body>
    <main>
        <h1>Edit Watchlist</h1>
        <form method="POST">
            <input type="hidden" name="watchlist_id" value="<?= htmlspecialchars($currentWatchlistEntry['id']) ?>">
            <p><strong><?= htmlspecialchars($movieData['judul']) ?></strong></p>
            <img src="<?= htmlspecialchars($movieData['gambar_poster']) ?>"><br>
            <label for="new_type">Update Watchlist Type:</label><br>
            <select name="new_type" id="new_type" required>
                <option value="watching" <?= $currentWatchlistEntry['tipe_watchlist'] == 'watching' ? 'selected' : '' ?>>Watching</option>
                <option value="completed" <?= $currentWatchlistEntry['tipe_watchlist'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="on-hold" <?= $currentWatchlistEntry['tipe_watchlist'] == 'on-hold' ? 'selected' : '' ?>>On-Hold</option>
                <option value="dropped" <?= $currentWatchlistEntry['tipe_watchlist'] == 'dropped' ? 'selected' : '' ?>>Dropped</option>
                <option value="plan-to-watch" <?= $currentWatchlistEntry['tipe_watchlist'] == 'plan-to-watch' ? 'selected' : '' ?>>Plan to Watch</option>
            </select><br>
            <button type="submit">Update Watchlist</button>
            <div class="links">
                <a href="detail_film.php?id=<?= $movieData['id'] ?>">Kembali</a>
            </div>
        </form>
    </main>
</body>

</html>