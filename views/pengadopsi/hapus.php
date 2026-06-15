<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID pengadopsi tidak valid');
}

// PERINTAH HAPUS (DELETE)
$stmt = $pdo->prepare("DELETE FROM pengadopsi WHERE id_pengadopsi = ?");
$hasil = $stmt->execute([$id]);

if ($hasil) {
    header("Location: index.php?pesan=berhasil_hapus");
} else {
    die("Gagal hapus data");
}
?>
