<?php
include "koneksi.php";

// Ambil ID dari URL
$id = $_GET['id'];

// PERINTAH HAPUS (DELETE)
$query = "DELETE FROM Pengadopsi WHERE id_pengadopsi = '$id'";

$hapus = mysqli_query($koneksi, $query);

if ($hapus) {
    header("Location: index.php?pesan=berhasil_hapus");
} else {
    echo "Gagal hapus data: " . mysqli_error($koneksi);
}
?>
