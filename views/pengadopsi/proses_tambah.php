<?php
// AMBIL KONEKSI BISA PAKE INCLUDE
include "koneksi.php";

// TANGKAP DATA DARI FORM PAKE $_POST
// Nama-nama di dalam kurung siku ini harus sama kayak atribut 'name' di input form
$nama = $_POST['nama'];
$no_hp = $_POST['no_hp'];
$email = $_POST['email'];
$alamat = $_POST['alamat'];
$surat = $_POST['surat_keterangan'];

// BIKIN PERINTAH SQL UNTUK MASUKIN DATA (INSERT)
$query = "INSERT INTO Pengadopsi (nama, no_hp, email, alamat, surat_keterangan) 
          VALUES ('$nama', '$no_hp', '$email', '$alamat', '$surat')";

// JALANKAN PERINTAHNYA
$save = mysqli_query($koneksi, $query);

// CEK BERHASIL ATAU GAK
if ($save) {
    // Kalau berhasil, balik ke halaman utama (index.php)
    header("Location: index.php?pesan=berhasil_tambah");
} else {
    // Kalau gagal, kasih tau errornya apa
    echo "Duh gagal simpan nih: " . mysqli_error($koneksi);
}
?>
