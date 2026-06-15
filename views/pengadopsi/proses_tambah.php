<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}

$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$no_hp = isset($_POST['no_hp']) ? trim($_POST['no_hp']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
$kata_sandi = isset($_POST['kata_sandi']) ? trim($_POST['kata_sandi']) : '';
$url_ktp = isset($_POST['url_ktp']) && $_POST['url_ktp'] !== '' ? trim($_POST['url_ktp']) : null;
$status_verifikasi = isset($_POST['status_verifikasi']) ? $_POST['status_verifikasi'] : 'Belum';
$catatan_verifikasi = isset($_POST['catatan_verifikasi']) && $_POST['catatan_verifikasi'] !== '' ? trim($_POST['catatan_verifikasi']) : null;

if (empty($nama) || empty($no_hp) || empty($email) || empty($alamat) || empty($kata_sandi)) {
    die('Nama, No HP, Email, Alamat, dan Kata Sandi tidak boleh kosong');
}

$hashedPassword = password_hash($kata_sandi, PASSWORD_DEFAULT);
$tanggal_verifikasi = ($status_verifikasi === 'Terverifikasi') ? date('Y-m-d') : null;

$stmt = $pdo->prepare("INSERT INTO pengadopsi (nama, no_hp, email, alamat, kata_sandi, url_ktp, status_verifikasi, tanggal_verifikasi, catatan_verifikasi) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

$save = $stmt->execute([$nama, $no_hp, $email, $alamat, $hashedPassword, $url_ktp, $status_verifikasi, $tanggal_verifikasi, $catatan_verifikasi]);

if ($save) {
    header("Location: index.php?pesan=berhasil_tambah");
} else {
    die("Duh gagal simpan nih");
}
?>
