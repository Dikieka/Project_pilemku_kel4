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

$message = ''; // Variabel untuk menyimpan pesan notifikasi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadsDir = '../profil/uploads/fotoProfile/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        // Jalur file sementara
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = uniqid() . '_' . $_FILES['profile_picture']['name']; // Gunakan nama unik
        $destination = $uploadsDir . $fileName;

        // Hapus foto lama jika ada
        if (!empty($currentUser['profile_picture']) && file_exists($currentUser['profile_picture'])) {
            if ($currentUser['profile_picture'] !== '../uploads/fotoProfile/default.jpg') { // Hindari menghapus default
                unlink($currentUser['profile_picture']);
            }
        }

        // Pindahkan file baru
        if (move_uploaded_file($fileTmpPath, $destination)) {
            // Perbarui database dengan jalur baru
            $user->updateProfilePicture($_SESSION['user_id'], $destination);
            // Perbarui data pengguna setelah upload
            $currentUser = $user->getCurrentUser($_SESSION['user_id']);
            $message = "Foto profil berhasil diperbarui!"; // Set pesan notifikasi
        } else {
            $message = "Gagal memindahkan file yang diupload.";
        }
    } else {
        $message = "Tidak ada file yang diupload atau terjadi kesalahan.";
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
            display: flex;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 600px;
            height: 85vh;
            gap: 20px;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 10px;
        }

        h1 {
            text-align: center;
        }

        form {
            text-align: center;   
        }

        .btn-inputimg {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
            padding: 10px 20px;
            margin-top: 10px;
        }

        button {
            width: 100%;
            background-color: #F11376;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #a90f55;
        }

        .keterangan {
            width: 35%;
            background-color: #131127;
            border-radius: 5px;
            padding: 10px 20px;
            text-align: center;
            font-weight: bold;
        }

        p {
            margin-top: -10px;
            margin-bottom: -10px;
        }
    </style>
</head>

<body>
    <main>
        <h1>Pengaturan Profile</h1>
        <!-- Menampilkan foto profil -->
        <?php if (!empty($currentUser['profile_picture']) && file_exists($currentUser['profile_picture'])): ?>
            <img src="<?= htmlspecialchars($currentUser['profile_picture']) ?>" alt="Profile" class="profile-picture" style="width: 250px; height: auto; border-radius: 50%; object-fit: cover;">
        <?php else: ?>
            <img src="../uploads/fotoProfile/default.jpg" alt="Default Profile" class="profile-picture" style="width: 250px; height: auto; border-radius: 50%; object-fit: cover;">
        <?php endif; ?>

        <div class="keterangan">
        <?= htmlspecialchars($currentUser['username']); ?><br>
        <?= htmlspecialchars($currentUser['email']); ?>
        </div>
        
        <?php if (!empty($message)): ?>
            <p style="color: #8D9AFC; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <!-- Form untuk upload -->
        <form method="POST" enctype="multipart/form-data">
            <label for="profile_picture">Upload Foto Profil baru</label><br>
            <input class="btn-inputimg" type="file" name="profile_picture" id="profile_picture" required><br>
            <br>
            <button type="submit">Simpan</button>
        </form>
    </main>

</body>

</html>