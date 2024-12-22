<?php
ob_start(); // Start output buffering
require '../navbar.php';
require_once '../classes/Database.php';
require_once '../classes/Movie.php';

use Classes\Database;
use Classes\Movie;

$db = new Database();
$movie = new Movie($db->pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
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
        $movie->addMovie($judul, $tanggal_rilis, $sutradara, $deskripsi, $gambar_poster);
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
            color: white;
        }

        main {
            padding: 20px 30px;
        }
    </style>
</head>

<body>
    <main>
        <h1>Add Movie</h1>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?= $errorMessage ?></p>
        <?php endif; ?>
        <form action="tambah_film.php" method="post" enctype="multipart/form-data">
            <label for="judul">Judul:</label>
            <input type="text" id="judul" name="judul" required>
            <br>
            <label for="tanggal_rilis">Tanggal Rilis:</label>
            <input type="date" id="tanggal_rilis" name="tanggal_rilis" required>
            <br>
            <label for="sutradara">Sutradara:</label>
            <input type="text" id="sutradara" name="sutradara" required>
            <br>
            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" required></textarea>
            <br>
            <label for="gambar_poster">Poster Image:</label>
            <input type="file" name="gambar_poster" id="gambar_poster" required>
            <br>
            <button type="submit">Add Movie</button>
        </form>
    </main>
</body>

</html>