<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID kandang tidak valid');
}

$stmt = $pdo->prepare("DELETE FROM Kandang WHERE id_kandang = ?");
$stmt->execute([$id]);
echo "<script>alert('Kandang hewan sudah dihapus!'); window.location='index.php';</script>";
?>