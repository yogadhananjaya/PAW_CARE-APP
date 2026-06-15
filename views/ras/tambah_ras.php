<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';

if(isset($_POST['simpan'])) {
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $id_jenis = isset($_POST['id_jenis']) ? (int)$_POST['id_jenis'] : 0;
    
    if (empty($nama) || $id_jenis <= 0) {
        die('Data tidak boleh kosong');
    }
    
    $stmt = $pdo->prepare("INSERT INTO Ras (id_jenis, nama_ras) VALUES (?, ?)");
    $stmt->execute([$id_jenis, $nama]);
    echo "<script>alert('Ras Berhasil ditambahkan!'); window.location='index.php';</script>";
    exit;
}

// Ambil list jenis hewan
$jenis_list = $pdo->query("SELECT * FROM Jenis_Hewan")->fetchAll();
?>

<div class="flex flex-col items-center justify-center h-full">
    <div class="w-full max-w-md bg-paw-putih rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="p-8 bg-paw-merah text-paw-putih text-center">
            <h2 class="text-2xl font-bold">Tambah Ras Baru</h2>
            <p class="text-red-100 text-sm">Contoh: Persia, Golden Retriever, dll</p>
        </div>
        <form action="" method="POST" class="p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Nama Ras</label>
                <input type="text" name="nama" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Jenis Hewan</label>
                <select name="id_jenis" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
                    <option value="">-- Pilih Jenis --</option>
                    <?php
                    foreach($jenis_list as $j) {
                        echo "<option value='".$j['id_jenis']."'>".htmlspecialchars($j['nama_jenis'])."</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="simpan" class="w-full bg-paw-merah text-paw-putih py-4 rounded-xl font-bold shadow-lg">SIMPAN RAS</button>
            <a href="index.php" class="block text-center text-sm text-gray-500 font-bold uppercase tracking-widest mt-4">Batal</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>
