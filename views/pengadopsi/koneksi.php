<?php
// FILE INI UNTUK MENGHUBUNGKAN PHP KE DATABASE
// JANGAN LUPA NYALAKAN XAMPP (MYSQL)

$host = "127.0.0.1"; // ini alamat servernya
$user = "root";      // ini username database
$pass = "";          // ini password database (kosong kalau bawaan xampp)
$db   = "pawcare_db"; // ini nama database kita

// Ini perintah untuk menyambungkan
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek apakah berhasil atau tidak
if (!$koneksi) {
    die("Yah, koneksi gagal: " . mysqli_connect_error());
}
?>
