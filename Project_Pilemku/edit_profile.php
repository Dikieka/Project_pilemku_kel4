<?php
require 'classes/Database.php';
require 'classes/User.php';

use Classes\Database;
use Classes\User;

session_start();
$db = new Database();
$user = new User($db->pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadsDir = 'uploads/';
        
        // Cek apakah folder 'uploads/' ada, jika tidak, buat folder tersebut
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true); // Membuat folder dengan izin penuh
        }
        
        // Dapatkan informasi file
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $destination = $uploadsDir . $fileName;

        // Pindahkan file yang diupload ke folder 'uploads/'
        if (move_uploaded_file($fileTmpPath, $destination)) {
            // Simpan path file ke database
            $userId = $_SESSION['user_id'];
            $user->updateProfilePicture($userId, $destination);
            echo "Foto profil berhasil diperbarui!";
        } else {
            echo "Gagal memindahkan file yang diupload.";
        }
    } else {
        echo "Tidak ada file yang diupload atau terjadi kesalahan.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>
    <h1>Edit Profile</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="profile_picture">Upload Foto Profil</label>
        <input type="file" name="profile_picture" id="profile_picture" required>
        <br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
