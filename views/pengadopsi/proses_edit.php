<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}

// Tangkap datanya sama kayak pas nambah, tapi tambahin ID
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$no_hp = isset($_POST['no_hp']) ? trim($_POST['no_hp']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
$surat = isset($_POST['surat_keterangan']) ? trim($_POST['surat_keterangan']) : '';

if ($id <= 0 || empty($nama) || empty($no_hp)) {
    die('Data tidak valid');
}

// BIKIN PERINTAH UPDATE
$stmt = $pdo->prepare("UPDATE Pengadopsi SET 
          nama = ?, 
          no_hp = ?, 
          email = ?, 
          alamat = ?, 
          surat_keterangan = ? 
          WHERE id_pengadopsi = ?");

$edit = $stmt->execute([$nama, $no_hp, $email, $alamat, $surat, $id]);

if ($edit) {
    header("Location: index.php?pesan=berhasil_ubah");
} else {
    die("Duh gagal update nih");
}
?>
