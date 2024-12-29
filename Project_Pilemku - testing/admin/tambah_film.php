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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $genre = isset($_POST['genre']) ? implode(', ', $_POST['genre']) : ''; // Handle multiple genres
    $tanggal_rilis = $_POST['tanggal_rilis'];
    $sutradara = $_POST['sutradara'];
    $deskripsi = $_POST['deskripsi'];

    // Handle file upload
    if (isset($_FILES['gambar_poster']) && $_FILES['gambar_poster']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambar_poster']['tmp_name'];
        $fileName = $_FILES['gambar_poster']['name'];
        $fileSize = $_FILES['gambar_poster']['size'];
        $fileType = $_FILES['gambar_poster']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Sanitize file name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // Check if the file has one of the allowed extensions
        $allowedfileExtensions = array('jpg', 'gif', 'png');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Directory in which the uploaded file will be moved
            $uploadFileDir = '../uploads/posters/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $gambar_poster = $dest_path;
            } else {
                $errorMessage = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            }
        } else {
            $errorMessage = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
    } else {
        $errorMessage = 'There is some error in the file upload. Please check the following error.<br>';
        $errorMessage .= 'Error:' . $_FILES['gambar_poster']['error'];
    }

    if (!isset($errorMessage)) {
        $movie->addMovie($judul, $genre, $tanggal_rilis, $sutradara, $deskripsi, $gambar_poster);
        header('Location: admin_dashboard.php');
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
            max-width: 800px;
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


        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }

        .grid-container input[type="checkbox"] {
            margin-right: 5px;
        }

        .grid-container label {
            display: flex;
            align-items: center;
            background-color: #262042;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .grid-container input[type="checkbox"]:checked + label {
            background-color: #8214B5;
            color: #fff;
            border-color: #8214B5;
        }

        .grid-container input[type="checkbox"]:hover + label {
            background-color: #8214B5;
            color: #fff;
            border-color: #8214B5;
        }

        .grid-container input[type="checkbox"] {
            display: none; /* Hide the checkbox */
        }

        button {
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
        }

        button:hover {
            background-color: #8214B5;
        }

        @media (max-width: 768px) {
            main {
                padding: 15px 20px;
            }
        
            button {
                width: auto;
            }
        }

    </style>
</head>

<body>
    <main>
        <h1>Add Movie</h1>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?= $errorMessage ?></p>
        <?php endif; ?>
        <form class="form-addMovies" action="tambah_film.php" method="post" enctype="multipart/form-data">
            <label class="label" for="judul">Judul:</label>
            <input class="input-ket" type="text" id="judul" name="judul" required>
            <br>

            <label class="label" for="genre">Genre:</label>
            <div class="grid-container">
                <?php
                $genres = ['Action', 'Comedy', 'Drama', 'Thriller', 'Sci-Fi', 'Fantasi', 'Romance', 'Horror', 'Adventure', 'Animation','Documentary', 'Zombie'];
                foreach ($genres as $genre) {
                    echo "
                    <input type='checkbox' name='genre[]' value='$genre' id='genre_$genre'>
                    <label for='genre_$genre'>$genre</label>";
                }
                ?>
            </div>
            
            <label class="label" for="tanggal_rilis">Tanggal Rilis:</label>
            <input class="input-ket" type="date" id="tanggal_rilis" name="tanggal_rilis" required>
            <br>
            <label class="label" for="sutradara">Sutradara:</label>
            <input class="input-ket" type="text" id="sutradara" name="sutradara" required>
            <br>
            <label class="label" for="deskripsi">Deskripsi:</label>
            <textarea class="input-ket" id="deskripsi" name="deskripsi" required></textarea>
            <br>
            <label class="label" for="gambar_poster">Poster Image:</label>
            <input class="input-ket" type="file" name="gambar_poster" id="gambar_poster" required>
            <br>
            <button type="submit">Add Movie</button>
        </form>
    </main>
</body>

</html>