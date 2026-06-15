<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID ras tidak valid');
}

// Ambil data ras
$stmt = $pdo->prepare("SELECT * FROM Ras WHERE id_ras = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die('Ras tidak ditemukan');
}

if(isset($_POST['update'])) {
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $id_jenis = isset($_POST['id_jenis']) ? (int)$_POST['id_jenis'] : 0;
    
    if (empty($nama) || $id_jenis <= 0) {
        die('Data tidak boleh kosong');
    }
    
    $stmt = $pdo->prepare("UPDATE Ras SET nama_ras = ?, id_jenis = ? WHERE id_ras = ?");
    $stmt->execute([$nama, $id_jenis, $id]);
    echo "<script>alert('Ras Berhasil diperbarui!'); window.location='index.php';</script>";
    exit;
}

// Ambil list jenis hewan
$jenis_list = $pdo->query("SELECT * FROM Jenis_Hewan")->fetchAll();
?>

<div class="flex flex-col items-center justify-center h-full">
    <div class="w-full max-w-md bg-paw-putih rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="p-8 bg-paw-merah text-paw-putih text-center">
            <h2 class="text-2xl font-bold">Ubah Ras Hewan</h2>
        </div>
        <form action="" method="POST" class="p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Nama Ras</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama_ras'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Jenis Hewan</label>
                <select name="id_jenis" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
                    <?php
                    foreach($jenis_list as $j) {
                        $pilih = ($j['id_jenis'] == $data['id_jenis']) ? "selected" : "";
                        echo "<option value='".$j['id_jenis']."' $pilih>".htmlspecialchars($j['nama_jenis'])."</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="update" class="w-full bg-paw-merah text-paw-putih py-4 rounded-xl font-bold shadow-lg">UPDATE RAS</button>
            <a href="index.php" class="block text-center text-sm text-gray-500 font-bold uppercase tracking-widest mt-4">Batal</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>
