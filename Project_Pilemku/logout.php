<?php
session_start();

// Hancurkan sesi
session_unset();
session_destroy();

// Redirect kembali ke halaman utama (atau halaman lain setelah logout)
header('Location: profil/index.php');
exit;
?>
