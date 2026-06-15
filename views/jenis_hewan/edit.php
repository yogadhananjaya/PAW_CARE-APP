<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die('ID jenis hewan tidak valid');
}

// Ambil data jenis hewan
$stmt = $pdo->prepare("SELECT * FROM Jenis_Hewan WHERE id_jenis = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if (!$row) {
    die('Jenis hewan tidak ditemukan');
}

if (isset($_POST['update'])) {
    $nama_jenis = isset($_POST['nama_jenis']) ? trim($_POST['nama_jenis']) : '';
    if (empty($nama_jenis)) {
        die('Nama jenis hewan tidak boleh kosong');
    }

    $stmt = $pdo->prepare("UPDATE Jenis_Hewan SET nama_jenis = ? WHERE id_jenis = ?");
    $stmt->execute([$nama_jenis, $id]);
    echo "<script>alert('Jenis hewan berhasil diupdate!'); window.location='index.php';</script>";
    exit;
}
?>

<div class="flex flex-col items-center justify-center h-full">
    <div class="w-full max-w-md bg-paw-putih rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="p-8 bg-paw-merah text-paw-putih text-center">
            <h2 class="text-2xl font-bold">Ubah Jenis Hewan</h2>
        </div>
        <form action="" method="POST" class="p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Nama Jenis</label>
                <input type="text" name="nama_jenis" value="<?= htmlspecialchars($row['nama_jenis'] ?? '') ?>"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition"
                    required>
            </div>
            <button type="submit" name="update"
                class="w-full bg-paw-merah text-paw-putih py-4 rounded-xl font-bold shadow-lg">UPDATE JENIS</button>
            <a href="index.php"
                class="block text-center text-sm text-gray-500 font-bold uppercase tracking-widest mt-4">Batal</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>