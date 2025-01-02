<?php
ob_start();
require '../navbar.php';
require_once '../classes/Database.php';
require_once '../classes/Movie.php';

use Classes\Database;
use Classes\Movie;

$db = new Database();
$movie = new Movie($db->pdo);

$homeLink = '../profil/index.php';
if ($isLoggedIn) {
    $homeLink = ($currentUser['role'] === 'admin') ? '../admin/admin_dashboard.php' : '../user/user_dashboard.php';
}

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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #262042;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            padding: 20px 100px 20px 100px;
            margin-top: 120px;
        }

        main {
            display: flex;
            justify-content: center;
            justify-items: center;
            width: 100%;
            max-width: 2000px;
        }

        .movie-image {
            flex: 1;
            max-width: 40%;
        }

        .movie-image img {
            width: 450px;
            height: 580px;
            border-radius: 10px;
        }

        .movie-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 15px;
            text-align: center;
        }

        label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 14px;
            resize: none;
        }

        input:read-only,
        textarea:read-only {
            background-color: rgba(255, 255, 255, 0.1);
            cursor: not-allowed;
        }

        textarea {
            height: 100px;
        }

        .links {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-edit, 
        .btn-delete, 
        .btn-kembali {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-edit {
            background-color: #F2974C;
        }

        .btn-delete {
            background-color: #B14BDF;
        }

        .btn-kembali {
            background-color: #F11376;
        }

        .btn-edit:hover {
            background-color: #B85E14;
        }

        .btn-delete:hover {
            background-color: #8214B5;
        }

        .btn-kembali:hover {
            background-color: #9C0046;
        }

    </style>
</head>

<body>
    <main>
        <div class="movie-image">
            <img src="<?= htmlspecialchars($movieData['gambar_poster']) ?>" alt="Poster">
        </div>
        <div class="movie-details">
            <h1><?= htmlspecialchars($movieData['judul']) ?></h1>
            <label for="judul">Judul:</label>
            <input type="text" value="<?= htmlspecialchars($movieData['judul']) ?>" readonly>

            <label for="genre">Genre:</label>
            <input type="text" value="<?= htmlspecialchars($movieData['genre']) ?>" readonly>

            <label for="tanggal_rilis">Tanggal Rilis:</label>
            <input type="date" value="<?= htmlspecialchars($movieData['tanggal_rilis']) ?>" readonly>

            <label for="sutradara">Sutradara:</label>
            <input type="text" value="<?= htmlspecialchars($movieData['sutradara']) ?>" readonly>

            <label for="deskripsi">Deskripsi:</label>
            <textarea readonly><?= htmlspecialchars($movieData['deskripsi']) ?></textarea>
            <div class="links">
                <a class="btn-edit" href="edit_film.php?id=<?= htmlspecialchars($movieData['id']) ?>">Edit</a>
                <a class="btn-delete" href="detail_film.php?delete_id=<?= htmlspecialchars($movieData['id']) ?>" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>
                <a class="btn-kembali" href="<?= $homeLink ?>">Kembali</a>
            </div>
        </form>
    </main>
</body>

</html>