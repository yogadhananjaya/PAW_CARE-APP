<?php
include 'koneksi.php';
$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM Jenis_Hewan WHERE id_jenis = '$id'");
echo "<script>alert('Jenis hewan sudah dihapus!'); window.location='index.php';</script>";
?>