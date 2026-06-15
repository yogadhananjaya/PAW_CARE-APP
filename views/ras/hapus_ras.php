<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID ras tidak valid');
}

$stmt = $pdo->prepare("DELETE FROM Ras WHERE id_ras = ?");
$stmt->execute([$id]);
echo "<script>alert('Ras sudah dihapus!'); window.location='index.php';</script>";
?>
