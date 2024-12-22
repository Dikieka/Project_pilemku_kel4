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

$db = new Database();
$movie = new Movie($db->pdo);
$watchlist = new Watchlist($db->pdo);

$movieData = $movie->getMovieById($_GET['id']);
$isInWatchlist = $watchlist->isMovieInWatchlist($_SESSION['user_id'], $_GET['id']);

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Movie</title>
    <style>
        body {
            background-color: #262042;
            color: white;
        }

        main {
            padding: 20px 30px;
        }
    </style>
</head>

<body>
    <main>
        <h1>Detail Movie</h1>
        <form>
            <img src="<?= htmlspecialchars($movieData['gambar_poster']) ?>"><br>
            <label for="judul">Judul:</label><br>
            <input type="text" value="<?= htmlspecialchars($movieData['judul']) ?>" readonly><br>
            <label for="tanggal_rilis">Tanggal Rilis:</label><br>
            <input type="date" value="<?= htmlspecialchars($movieData['tanggal_rilis']) ?>" readonly><br>
            <label for="sutradara">Sutradara:</label><br>
            <input type="text" value="<?= htmlspecialchars($movieData['sutradara']) ?>" readonly><br>
            <label for="deskripsi">Deskripsi:</label><br>
            <textarea readonly><?= htmlspecialchars($movieData['deskripsi']) ?></textarea><br>
            <div class="links">
                <?php if ($isInWatchlist): ?>
                    <a href="edit_watchlist.php?id=<?= htmlspecialchars($movieData['id']) ?>">Edit Watchlist</a>
                <?php else: ?>
                    <a href="add_watchlist.php?id=<?= htmlspecialchars($movieData['id']) ?>">Add to Watchlist</a>
                <?php endif; ?>
                <a href="user_dashboard.php">Kembali</a>
            </div>
        </form>
    </main>
</body>

</html>