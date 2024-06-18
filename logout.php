<?php
session_start();
session_unset(); // Menghapus semua variabel sesi
session_destroy(); // Menghancurkan sesi
header("Location: Home.php"); // Mengarahkan pengguna kembali ke halaman login
exit();
?>
