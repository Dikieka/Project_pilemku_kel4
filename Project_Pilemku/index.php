<?php
require 'navbar.php';

require_once 'classes/Movie.php';

use Classes\Movie;

$movie = new Movie($db->pdo);
$movies = $movie->getAllMovies();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilemku</title>
    <style>
        body {
            background-color: #262042;
        }

        main {
            padding: 20px 30px;
            display: flex;
            flex-direction: column;
        }

        main h1 {
            color: white;
        }

        main a {
            padding: 5px 10px;
            width: fit-content;
            background-color: #F11376;
            text-align: center;
            font-weight: bold;
            border: 2px solid white;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            transition: 0.4s;
        }

        main a:hover {
            background-color: #131127;
            border: 2px solid #F11376;
            color: white;
        }

        .container {
            padding: 30px 0;
        }

        .container .card {
            padding: 5px 10px;
            width: 200px;
            height: 350px;
            border: 2px solid white;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container .card img {
            height: 200px;
            width: auto;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <main>
        <main>
            <div class="container">
                <?php foreach ($movies as $movie): ?>
                    <div class="card">
                        <h1><?= htmlspecialchars($movie['judul']) ?></h1>
                        <img src="<?= htmlspecialchars($movie['gambar_poster']) ?>">
                        <div class="card-body">
                            <a href="admin_dashboard.php?delete_id=<?= $movie['id'] ?>" onclick="return confirm('Are you sure you want to delete this movie?')">Delete</a>
                            <a href="edit_film.php?id=<?= $movie['id'] ?>">Edit</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </main>
</body>

</html>