<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}

// TANGKAP DATA DARI FORM PAKE $_POST
// Nama-nama di dalam kurung siku ini harus sama kayak atribut 'name' di input form
$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$no_hp = isset($_POST['no_hp']) ? trim($_POST['no_hp']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
$surat = isset($_POST['surat_keterangan']) ? trim($_POST['surat_keterangan']) : '';

if (empty($nama) || empty($no_hp)) {
    die('Nama dan No HP tidak boleh kosong');
}

// BIKIN PERINTAH SQL UNTUK MASUKIN DATA (INSERT)
$stmt = $pdo->prepare("INSERT INTO Pengadopsi (nama, no_hp, email, alamat, surat_keterangan) 
          VALUES (?, ?, ?, ?, ?)");

// JALANKAN PERINTAHNYA
$save = $stmt->execute([$nama, $no_hp, $email, $alamat, $surat]);

// CEK BERHASIL ATAU GAK
if ($save) {
    // Kalau berhasil, balik ke halaman utama (index.php)
    header("Location: index.php?pesan=berhasil_tambah");
} else {
    // Kalau gagal, kasih tau errornya apa
    die("Duh gagal simpan nih");
}
?>
