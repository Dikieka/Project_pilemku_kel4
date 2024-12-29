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

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../profil/index.php');
    exit;
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        main {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 90px;
            text-align: center;
        }

        .add-watchlist {
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            max-width: 600px;
            margin: 0 auto;
        }

        .add-watchlist p {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #fff;
        }

        .add-watchlist img {
            max-width: 200px;
            height: auto;
            margin: 0 auto;
            display: block;
            border-radius: 10px;
        }

        label {
            color: #ccc;
            font-size: 16px;
            display: block;
        }

        select {
            width: 80%;
            padding: 12px;
            margin: 0 auto;
            background-color: #131127;
            color: white;
            font-size: 14px;
            border-radius: 5px;
            border: none;
        }

        .links {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .btn-add {
            width: 80%;
            padding: 10px;
            margin: 0 auto;
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
            width: 77%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #F11376;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-add:hover {
            background-color: #B85E14;
        }

        .btn-kembali:hover {
            background-color: #9C0046;
        }

        .links {
            text-align: center;
            margin-top: 15px;
        }

        a {
            text-decoration: none;
        }

        @media (max-width: 768px) {
            main {
                padding: 15px;
            }
        
            .add-watchlist {
                padding: 15px;
            }
        
            .add-watchlist img {
                max-width: 100%;
                margin: 0 auto 20px;
            }
        
            button {
                width: 100%;
                margin-top: 10px;
            }
        }

    </style>
</head>

<body>
    <main>
        <form class="add-watchlist" method="POST">
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
            <button class="btn-add" type="submit">Add to Watchlist</button>
            <div class="links">
                <a class="btn-kembali" href="detail_film.php?id=<?= $movieData['id'] ?>">Kembali</a>
            </div>
        </form>
    </main>
</body>

</html>