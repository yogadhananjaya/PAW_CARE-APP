<?php
include "koneksi.php";

// Tangkap datanya sama kayak pas nambah, tapi tambahin ID
$id     = $_POST['id'];
$nama   = $_POST['nama'];
$no_hp  = $_POST['no_hp'];
$email  = $_POST['email'];
$alamat = $_POST['alamat'];
$surat  = $_POST['surat_keterangan'];

// BIKIN PERINTAH UPDATE
$query = "UPDATE Pengadopsi SET 
          nama = '$nama', 
          no_hp = '$no_hp', 
          email = '$email', 
          alamat = '$alamat', 
          surat_keterangan = '$surat' 
          WHERE id_pengadopsi = '$id'";

$edit = mysqli_query($koneksi, $query);

if ($edit) {
    header("Location: index.php?pesan=berhasil_ubah");
} else {
    echo "Duh gagal update nih: " . mysqli_error($koneksi);
}
?>
