<?php
ob_start(); // Start output buffering
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

$movieData = $movie->getMovieById($_GET['id']);
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    // Menangani data genre
    if (isset($_POST['genre'])) {
        $genre = implode(', ', $_POST['genre']); // Gabungkan genre yang dipilih
    } else {
        $genre = '';  // Jika tidak ada genre yang dipilih
    }
    $tanggal_rilis = $_POST['tanggal_rilis'];
    $sutradara = $_POST['sutradara'];
    $deskripsi = $_POST['deskripsi'];
    $gambar_poster = $movieData['gambar_poster']; // Default to existing poster image

    // Handle file upload
    if (isset($_FILES['gambar_poster']) && $_FILES['gambar_poster']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambar_poster']['tmp_name'];
        $fileName = $_FILES['gambar_poster']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
    
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    
        $allowedfileExtensions = ['jpg', 'gif', 'png'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = '../uploads/posters/';
            $dest_path = $uploadFileDir . $newFileName;
    
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $gambar_poster = $dest_path;
            } else {
                $errorMessage = 'Failed to move the uploaded file.';
            }
        } else {
            $errorMessage = 'Invalid file type. Allowed types: ' . implode(', ', $allowedfileExtensions);
        }
    }
    
    if (!isset($errorMessage)) {
        $movie->updateMovie($id, $judul, $genre, $tanggal_rilis, $sutradara, $deskripsi, $gambar_poster ?? null);
        header('Location: detail_film.php?id=' . $id);
        exit;
    }
    
}
ob_end_flush(); // Flush the output buffer
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
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        main {
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            max-width: 1300px;
            margin-top: 60px;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            color: #fff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-container img {
            max-width: 500px;
            height: 730px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            flex: 1 1 500px; /* Set width up to 500px */
        }

        .form-fields {
            flex: 1 1 500px; /* Ensure the form also takes up remaining space */
            min-width: 300px;
        }

        .input-ket {
            width: 96%;
            padding: 10px 12px;
            margin-bottom: 15px;
            font-size: 16px;
            border-radius: 6px;
            border: none;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            transition: border-color 0.3s ease;
        }

        textarea.input-ket {
            min-height: 100px;
            resize: vertical;
        }

        .input-ket[type="file"] {
            padding: 8px;
            font-size: 14px;
            border: none;
            background-color: transparent;
            cursor: pointer;
        }

        .input-ket[type="file"]:focus {
            outline: 2px solid #7267f0;
        }

        label {
            color: #ccc;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        /* Flexbox layout for genres */
        .genre-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .genre-container input[type="checkbox"] {
            display: none;
        }

        .genre-container label {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #262042;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            font-size: 14px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .genre-container input[type="checkbox"]:checked + label {
            background-color: #8214B5;
            color: #fff;
            border-color: #8214B5;
        }

        .genre-container input[type="checkbox"]:hover + label {
            background-color: #5f0b8a;
            border-color: #5f0b8a;
        }

        .btn-edit {
            background-color: #B14BDF;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 0 auto;
            width: 100%;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-kembali {
            background-color: #F11376;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 0 auto;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        a {
            text-decoration: none;
        }

        .btn-edit:hover {
            background-color: #8214B5;
        }

        .btn-kembali:hover {
            background-color: #9C0046;
        }

        @media (max-width: 768px) {
            main {
                padding: 15px 20px;
            }

            .form-container {
                flex-direction: column; /* Stack image and form vertically on smaller screens */
                align-items: center;
            }

            .form-container img {
                max-width: 100%;
            }

            button {
                width: auto;
            }
        }
    </style>
</head>

<body>
    <main>
        <h1>Edit Movie</h1>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?= $errorMessage ?></p>
        <?php endif; ?>
        <form class="form-addMovies" action="edit_film.php?id=<?= $movieData['id'] ?>" method="post" enctype="multipart/form-data">
            <div class="form-container">
                <!-- Gambar poster di sebelah kiri -->
                <img src="<?= htmlspecialchars($movieData['gambar_poster']) ?>" alt="Movie Poster">

                <!-- Form input di sebelah kanan -->
                <div class="form-fields">
                    <label for="judul">Judul:</label>
                    <input class="input-ket" type="text" id="judul" name="judul" value="<?= htmlspecialchars($movieData['judul']) ?>">

                    <label for="genre">Genre:</label>
                    <div class="genre-container">
                        <?php
                        // List of available genres
                        $genres = ['Action', 'Comedy', 'Drama', 'Thriller', 'Sci-Fi', 'Fantasi', 'Romance', 'Horror', 'Adventure', 'Animation','Documentary', 'Zombie'];
                        $selectedGenres = explode(', ', $movieData['genre']); // Convert existing genre string into array

                        foreach ($genres as $genre) {
                            $checked = in_array($genre, $selectedGenres) ? 'checked' : '';
                            echo "<input type='checkbox' name='genre[]' value='$genre' id='genre_$genre' $checked>
                                  <label for='genre_$genre'>$genre</label>";
                        }
                        ?>
                    </div>

                    <label for="tanggal_rilis">Tanggal Rilis:</label>
                    <input class="input-ket" type="date" id="tanggal_rilis" name="tanggal_rilis" value="<?= htmlspecialchars($movieData['tanggal_rilis']) ?>">

                    <label for="sutradara">Sutradara:</label>
                    <input class="input-ket" type="text" id="sutradara" name="sutradara" value="<?= htmlspecialchars($movieData['sutradara']) ?>">

                    <label for="deskripsi">Deskripsi:</label>
                    <textarea class="input-ket" id="deskripsi" name="deskripsi"><?= htmlspecialchars($movieData['deskripsi']) ?></textarea>

                    <label for="gambar_poster">Poster Image:</label>
                    <input class="input-ket" type="file" name="gambar_poster" id="gambar_poster">

                    <button class="btn-edit" type="submit">Edit Movie</button>
                    <div class="link">
                        <a href="detail_film.php?id=<?= $movieData['id'] ?>"><button class="btn-kembali">Kembali</button></a>
                    </div>
                </div>
            </div>
        </form>
    </main>
</body>

</html>
