<?php
ob_start();
require '../navbar.php';
require_once '../classes/Database.php';
require_once '../classes/Movie.php';

use Classes\Database;
use Classes\Movie;

$db = new Database();
$movie = new Movie($db->pdo);

$movieData = $movie->getMovieById($_GET['id']);

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie</title>
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
            <label for="judul">judul:</label><br>
            <input type="text" value="<?= htmlspecialchars($movieData['judul']) ?>" readonly><br>
            <label for="tanggal_rilis">tanggal_rilis:</label><br>
            <input type="date" value="<?= htmlspecialchars($movieData['tanggal_rilis']) ?>" readonly><br>
            <label for="sutradara">sutradara:</label><br>
            <input type="text" value="<?= htmlspecialchars($movieData['sutradara']) ?>" readonly><br>
            <label for="deskripsi">deskripsi:</label><br>
            <textarea readonly><?= htmlspecialchars($movieData['deskripsi']) ?></textarea><br>
            <div class="links">
                <a href="index.php">Kembali</a>
            </div>
        </form>
    </main>
</body>

</html>