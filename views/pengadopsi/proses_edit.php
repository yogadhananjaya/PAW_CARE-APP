<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$no_hp = isset($_POST['no_hp']) ? trim($_POST['no_hp']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
$kata_sandi = isset($_POST['kata_sandi']) ? trim($_POST['kata_sandi']) : '';
$url_ktp = isset($_POST['url_ktp']) && $_POST['url_ktp'] !== '' ? trim($_POST['url_ktp']) : null;
$status_verifikasi = isset($_POST['status_verifikasi']) ? $_POST['status_verifikasi'] : 'Belum';
$catatan_verifikasi = isset($_POST['catatan_verifikasi']) && $_POST['catatan_verifikasi'] !== '' ? trim($_POST['catatan_verifikasi']) : null;

if ($id <= 0 || empty($nama) || empty($no_hp) || empty($email) || empty($alamat)) {
    die('Data tidak valid');
}

$tanggal_verifikasi = ($status_verifikasi === 'Terverifikasi') ? date('Y-m-d') : null;

if (!empty($kata_sandi)) {
    $hashedPassword = password_hash($kata_sandi, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE pengadopsi SET 
              nama = ?, 
              no_hp = ?, 
              email = ?, 
              alamat = ?, 
              kata_sandi = ?,
              url_ktp = ?,
              status_verifikasi = ?,
              tanggal_verifikasi = ?,
              catatan_verifikasi = ?
              WHERE id_pengadopsi = ?");
    $edit = $stmt->execute([$nama, $no_hp, $email, $alamat, $hashedPassword, $url_ktp, $status_verifikasi, $tanggal_verifikasi, $catatan_verifikasi, $id]);
} else {
    $stmt = $pdo->prepare("UPDATE pengadopsi SET 
              nama = ?, 
              no_hp = ?, 
              email = ?, 
              alamat = ?, 
              url_ktp = ?,
              status_verifikasi = ?,
              tanggal_verifikasi = ?,
              catatan_verifikasi = ?
              WHERE id_pengadopsi = ?");
    $edit = $stmt->execute([$nama, $no_hp, $email, $alamat, $url_ktp, $status_verifikasi, $tanggal_verifikasi, $catatan_verifikasi, $id]);
}

if ($edit) {
    header("Location: index.php?pesan=berhasil_ubah");
} else {
    die("Duh gagal update nih");
}
?>
