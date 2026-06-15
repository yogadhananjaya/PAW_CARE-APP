<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';

// Ambil data Kandang Hewan
$query_kandang = $pdo->query("SELECT * FROM Kandang");
$data_kandang = $query_kandang->fetchAll();
?>

<!-- Header Halaman -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-paw-hitam">Manajemen Data Kandang Hewan</h2>
        <p class="text-gray-500 text-sm">Kelola kandang hewan peliharaan</p>
    </div>
    <div class="text-sm font-medium bg-paw-putih px-4 py-2 rounded-xl shadow-sm border border-gray-100">
        <?= date('l, d F Y'); ?>
    </div>
</div>

<!-- BAGIAN TABEL KANDANG HEWAN -->
<div class="bg-paw-putih rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-lg text-paw-hitam">Daftar Kandang Hewan</h3>
        <a href="tambah.php"
            class="bg-paw-hitam text-paw-putih px-4 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-gray-800 transition shadow-lg">
            <i class="fa-solid fa-plus"></i> Tambah Kandang Baru
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-paw-krem-gelap/20 text-gray-700 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">No</th>
                    <th class="px-6 py-4 font-bold">Kode Kandang</th>
                    <th class="px-6 py-4 font-bold text-center">Kapasitas</th>
                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php
                $no = 1;
                foreach ($data_kandang as $r) {
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-400"><?= $no++; ?></td>
                        <td class="px-6 py-4 font-bold text-paw-hitam"><?= $r['kode_kandang']; ?></td>
                        <td class="px-6 py-4">
                            <span class="bg-paw-krem-gelap/50 px-3 py-1 rounded-full text-xs font-bold text-gray-600">
                                <?= $r['kapasitas']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 flex justify-center gap-3">
                            <a href="edit.php?id=<?= $r['id_kandang']; ?>"
                                class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-paw-hitam hover:text-paw-putih transition shadow-sm">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </a>
                            <a href="hapus.php?id=<?= $r['id_kandang']; ?>"
                                onclick="return confirm('Yakin ingin menghapus kandang ini?')"
                                class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:bg-paw-merah hover:text-paw-putih transition shadow-sm">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>