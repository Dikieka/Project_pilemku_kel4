<?php
require '../navbar.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';

session_start();
use Classes\Database;
use Classes\User;

$db = new Database();
$user = new User($db->pdo);

// Mengecek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']);
$currentUser = $isLoggedIn ? $user->getCurrentUser($_SESSION['user_id']) : null;

$searchResults = [];
$searchQuery = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
    $searchResults = $user->searchMovies($searchQuery);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        /* Reset dasar */
        body, h1, h2, p, a {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
           
        }

        /* Body dan container utama */
        body {
            background-color: #262042;
            padding: 20px;
            line-height: 1.6;
        }

        main {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 30px;
        }

        /* Heading */
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: white;
            text-align: center;
        }

        /* Kontainer hasil pencarian */
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Kartu film */
        .card {
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            height: 350px;
            padding: 80px;
            text-align: center;
        }

        .card img {
            height: 250px;
            width: 190px;
            margin-bottom: 5px;
        }

        .btn-detail a {
            width: 85%;
            text-decoration: none;
            background-color: #F11376;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .btn-detail a:hover {
            background-color: #9C0046;
        }

        /* Pesan ketika hasil pencarian kosong */
        p {
            text-align: center;
            font-size: 16px;
            margin-bottom: 35px;
            color: white;
        }

    </style>
</head>

<body>

    <main>
        <h1>Search Results for "<?= htmlspecialchars($searchQuery) ?>"</h1>
        <?php if (!empty($searchResults)): ?>
            <div class="container">
                <?php foreach ($searchResults as $movie): ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($movie['gambar_poster']) ?>" alt="<?= htmlspecialchars($movie['judul']) ?>">
                        <p><?= htmlspecialchars($movie['judul']) ?></p>
                        <div class="btn-detail">
                            <a href="detail_film.php?id=<?= $movie['id'] ?>">Detail</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results found for "<?= htmlspecialchars($searchQuery) ?>".</p>
        <?php endif; ?>
    </main>
</body>

</html>
