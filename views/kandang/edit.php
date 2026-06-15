<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID kandang tidak valid');
}

// Ambil data kandang
$stmt = $pdo->prepare("SELECT * FROM kandang WHERE id_kandang = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if (!$row) {
    die('Kandang tidak ditemukan');
}

if(isset($_POST['update'])) {
    $nama_kandang = isset($_POST['nama_kandang']) ? trim($_POST['nama_kandang']) : '';
    $kapasitas = isset($_POST['kapasitas']) ? (int)$_POST['kapasitas'] : 0;
    $lokasi = isset($_POST['lokasi']) ? trim($_POST['lokasi']) : '';
    
    if (empty($nama_kandang) || $kapasitas <= 0 || empty($lokasi)) {
        die('Data tidak boleh ada yang kosong');
    }
    
    $stmt = $pdo->prepare("UPDATE kandang SET nama_kandang = ?, kapasitas = ?, lokasi = ? WHERE id_kandang = ?");
    $stmt->execute([$nama_kandang, $kapasitas, $lokasi, $id]);
    echo "<script>alert('Kandang berhasil diupdate!'); window.location='index.php';</script>";
    exit;
}
?>
<div class="flex flex-col items-center justify-center h-full">
    <div class="w-full max-w-md bg-paw-putih rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="p-8 bg-paw-merah text-paw-putih text-center">
            <h2 class="text-2xl font-bold">Ubah Kandang</h2>
            <p class="text-red-100 text-sm">Sesuaikan detail kandang shelter</p>
        </div>
        <form action="" method="POST" class="p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Nama Kandang</label>
                <input type="text" name="nama_kandang" value="<?= htmlspecialchars($row['nama_kandang'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Kapasitas (Ekor)</label>
                <input type="number" name="kapasitas" value="<?= htmlspecialchars($row['kapasitas'] ?? '') ?>" min="1" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Lokasi</label>
                <input type="text" name="lokasi" value="<?= htmlspecialchars($row['lokasi'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
            </div>
            <button type="submit" name="update" class="w-full bg-paw-merah text-paw-putih py-4 rounded-xl font-bold shadow-lg">UPDATE KANDANG</button>
            <a href="index.php" class="block text-center text-sm text-gray-500 font-bold uppercase tracking-widest mt-4">Batal</a>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>