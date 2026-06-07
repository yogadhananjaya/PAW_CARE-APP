<?php
include 'koneksi.php';
$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM Kandang WHERE id_kandang = '$id'");
echo "<script>alert('Kandang hewan sudah dihapus!'); window.location='index.php';</script>";
?>