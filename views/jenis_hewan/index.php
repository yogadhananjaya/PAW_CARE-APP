<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';

// Ambil data Jenis Hewan
$query_jenis = $pdo->query("SELECT * FROM Jenis_Hewan");
$data_jenis = $query_jenis->fetchAll();
?>

<!-- Header Halaman -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-paw-hitam">Manajemen Data Jenis Hewan</h2>
        <p class="text-gray-500 text-sm">Kelola spesifikasi jenis hewan peliharaan</p>
    </div>
    <div class="text-sm font-medium bg-paw-putih px-4 py-2 rounded-xl shadow-sm border border-gray-100">
        <?= date('l, d F Y'); ?>
    </div>
</div>

<!-- BAGIAN TABEL JENIS HEWAN -->
<div class="bg-paw-putih rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-lg text-paw-hitam">Daftar Jenis Hewan</h3>
        <a href="tambah.php"
            class="bg-paw-hitam text-paw-putih px-4 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-gray-800 transition shadow-lg">
            <i class="fa-solid fa-plus"></i> Tambah Jenis Baru
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-paw-krem-gelap/20 text-gray-700 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">No</th>
                    <th class="px-6 py-4 font-bold">Jenis Hewan</th>
                    <th class="px-6 py-4 font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php
                $no = 1;
                foreach ($data_jenis as $r) {
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-400"><?= $no++; ?></td>
                        <td class="px-6 py-4 font-bold text-paw-hitam"><?= $r['nama_jenis']; ?></td>
                        <td class="px-6 py-4">
                            <span class="bg-paw-krem-gelap/50 px-3 py-1 rounded-full text-xs font-bold text-gray-600">
                                <?= $r['nama_jenis']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 flex justify-center gap-3">
                            <a href="edit.php?id=<?= $r['id_jenis']; ?>"
                                class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-paw-hitam hover:text-paw-putih transition shadow-sm">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </a>
                            <a href="hapus.php?id=<?= $r['id_jenis']; ?>"
                                onclick="return confirm('Yakin ingin menghapus jenis hewan ini?')"
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