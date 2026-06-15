<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';

if(isset($_POST['simpan'])) {
    $kode_kandang = isset($_POST['kode_kandang']) ? trim($_POST['kode_kandang']) : '';
    $kapasitas = isset($_POST['kapasitas']) ? (int)$_POST['kapasitas'] : 0;
    
    if (empty($kode_kandang) || $kapasitas <= 0) {
        die('Data tidak boleh kosong');
    }
    
    $stmt = $pdo->prepare("INSERT INTO Kandang (kode_kandang, kapasitas) VALUES (?, ?)");
    $stmt->execute([$kode_kandang, $kapasitas]);
    echo "<script>alert('Kandang hewan berhasil ditambahkan!'); window.location='index.php';</script>";
    exit;
}
?>

<div class="flex flex-col items-center justify-center h-full">
    <div class="w-full max-w-md bg-paw-putih rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="p-8 bg-paw-merah text-paw-putih text-center">
            <h2 class="text-2xl font-bold">Tambah Kandang Hewan Baru</h2>
        </div>
        <form action="" method="POST" class="p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Kode Kandang</label>
                <input type="text" name="kode_kandang" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Kapasitas</label>
                <input type="number" name="kapasitas" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
            </div>
            <button type="submit" name="simpan" class="w-full bg-paw-merah text-paw-putih py-4 rounded-xl font-bold shadow-lg">SIMPAN KANDANG</button>
            <a href="index.php" class="block text-center text-sm text-gray-500 font-bold uppercase tracking-widest mt-4">Batal</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>