<?php
ob_start();
require '../navbar.php';
require_once '../classes/Database.php';
require_once '../classes/Movie.php';

use Classes\Database;
use Classes\Movie;

$db = new Database();
$movie = new Movie($db->pdo);

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../profil/index.php');
    exit;
}

// Handle delete request 
if (isset($_GET['delete_id'])) {
    try {
        $movie->deleteMovieAndWatchlist($_GET['delete_id']);
        header('Location: admin_dashboard.php');
        exit;
    } catch (\Exception $e) {
        echo "Error deleting movie: " . $e->getMessage();
    }
}

$movieData = $movie->getMovieById($_GET['id']);

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
                <a href="edit_film.php?id=<?= htmlspecialchars($movieData['id']) ?>">Edit</a>
                <a href="detail_film.php?delete_id=<?= htmlspecialchars($movieData['id']) ?>" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>
                <a href="admin_dashboard.php">Kembali</a>
            </div>
        </form>
    </main>
</body>

</html>