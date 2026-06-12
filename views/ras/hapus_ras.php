<?php
include 'koneksi.php';
$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM Ras WHERE id_ras = '$id'");
echo "<script>alert('Ras sudah dihapus!'); window.location='index.php';</script>";
?>
