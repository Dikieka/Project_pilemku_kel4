<?php
require_once '../navbar.php'; // Use require_once
require_once '../classes/Database.php'; // Use require_once
require_once '../classes/User.php'; // Use require_once

use Classes\Database;
use Classes\User;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$db = new Database();
$user = new User($db->pdo);

// Ambil data pengguna yang sedang login
$currentUser = $user->getCurrentUser($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadsDir = 'uploads/fotoProfile/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $destination = $uploadsDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            $user->updateProfilePicture($_SESSION['user_id'], $destination);
            // Perbarui data pengguna setelah upload
            $currentUser = $user->getCurrentUser($_SESSION['user_id']);
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
        <h1>Edit Profile</h1>
        <!-- Menampilkan foto profil -->
        <?php if (!empty($currentUser['profile_picture']) && file_exists($currentUser['profile_picture'])): ?>
            <img src="<?= htmlspecialchars($currentUser['profile_picture']) ?>" alt="Profile" class="profile-picture" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
        <?php else: ?>
            <img src="../uploads/fotoProfile/default.jpg" alt="Default Profile" class="profile-picture" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
        <?php endif; ?>

        <!-- Form untuk upload -->
        <form method="POST" enctype="multipart/form-data">
            <label for="profile_picture">Upload Foto Profil</label><br>
            <input type="file" name="profile_picture" id="profile_picture" required><br>
            <br>
            <button type="submit">Simpan</button>
        </form>
    </main>

</body>

</html>