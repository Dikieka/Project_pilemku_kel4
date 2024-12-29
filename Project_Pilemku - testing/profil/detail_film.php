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
    <title>Detail Movie</title>
    <style>
        /* Global styles */
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
            height: 100vh;
            padding: 20px;
        }

        main {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 14px;
            resize: none;
        }

        input:read-only, textarea:read-only {
            background-color: rgba(255, 255, 255, 0.1);
            cursor: not-allowed;
        }

        .links {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .links a {
            text-decoration: none;
            color: white;
            background-color: #F11376;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .links a:hover {
            background-color: #B10C5B;
        }
    </style>
</head>

<body>
    <main>
        <h1>Detail Movie</h1>
        <form>
            <img src="<?= htmlspecialchars($movieData['gambar_poster']) ?>" alt="Poster Film"><br>
            <label for="judul">Judul:</label>
            <input type="text" value="<?= htmlspecialchars($movieData['judul']) ?>" readonly>
            
            <label for="tanggal_rilis">Tanggal Rilis:</label>
            <input type="date" value="<?= htmlspecialchars($movieData['tanggal_rilis']) ?>" readonly>
            
            <label for="sutradara">Sutradara:</label>
            <input type="text" value="<?= htmlspecialchars($movieData['sutradara']) ?>" readonly>
            
            <label for="deskripsi">Deskripsi:</label>
            <textarea rows="5" readonly><?= htmlspecialchars($movieData['deskripsi']) ?></textarea>
            
            <div class="links">
                <a href="index.php">Kembali</a>
            </div>
        </form>
    </main>
</body>

</html>
