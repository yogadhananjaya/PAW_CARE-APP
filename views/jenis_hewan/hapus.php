<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID jenis hewan tidak valid');
}

$stmt = $pdo->prepare("DELETE FROM Jenis_Hewan WHERE id_jenis = ?");
$stmt->execute([$id]);
echo "<script>alert('Jenis hewan sudah dihapus!'); window.location='index.php';</script>";
?>