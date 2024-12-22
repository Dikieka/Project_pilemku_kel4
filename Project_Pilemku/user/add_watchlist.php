<?php
ob_start();
require_once '../navbar.php';
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipe_watchlist'])) {
    $watchlist->addToWatchlist($_SESSION['user_id'], $_GET['id'], $_POST['tipe_watchlist']);
    header('Location: detail_film.php?id=' . $_GET['id']);
    ob_end_flush();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Watchlist</title>
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
        <h1>Add Movie to Watchlist</h1>
        <form method="POST">
            <p><strong><?= htmlspecialchars($movieData['judul']) ?></strong></p>
            <img src="<?= htmlspecialchars($movieData['gambar_poster']) ?>"><br>
            <label for="tipe_watchlist">Watchlist Type:</label><br>
            <select name="tipe_watchlist" id="tipe_watchlist" required>
                <option value="watching">Watching</option>
                <option value="completed">Completed</option>
                <option value="on-hold">On-Hold</option>
                <option value="dropped">Dropped</option>
                <option value="plan-to-watch">Plan to Watch</option>
            </select><br>
            <button type="submit">Add to Watchlist</button>
        </form>
    </main>
</body>

</html>