<?php
// Hubungkan database
include "../../config/koneksi.php";

// Ambil ID dari URL
$id = $_GET['id'];

$pdo->query("DELETE FROM donasi WHERE id_donasi = $id");

// Kembalikan ke halaman utama donasi dengan pesan sukses
header("Location: index.php?success=Data donasi berhasil didelete!");
exit;
?>
