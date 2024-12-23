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
$isInWatchlist = $watchlist->isMovieInWatchlist($_SESSION['user_id'], $_GET['id']);

// Handle delete from watchlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_watchlist_id'])) {
    $watchlist->deleteFromWatchlist($_SESSION['user_id'], $_POST['delete_watchlist_id']);
    header('Location: detail_film.php?id=' . $_GET['id']);
    ob_end_flush();
    exit;
}

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

        a,
        button {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            background-color: #444;
            border-radius: 5px;
        }

        .delete-link {
            color: red;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <main>
        <h1>Detail Movie</h1>
        <form id="delete-form" method="POST" class="hidden">
            <input type="hidden" name="delete_watchlist_id" value="<?= htmlspecialchars($isInWatchlist['id']) ?>">
        </form>
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
                <a href="#" class="delete-link" onclick="confirmDelete()">Remove from Watchlist</a>
            <?php else: ?>
                <a href="add_watchlist.php?id=<?= htmlspecialchars($movieData['id']) ?>">Add to Watchlist</a>
            <?php endif; ?>
            <a href="user_dashboard.php">Kembali</a>
        </div>
    </main>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to remove this movie from your watchlist?')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
</body>

</html>