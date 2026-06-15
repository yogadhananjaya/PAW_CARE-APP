<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';

// Ambil data Jenis Hewan
$query_jenis = $pdo->query("SELECT * FROM jenis_hewan ORDER BY id_jenis DESC");
$data_jenis = $query_jenis->fetchAll();
?>

<div class="flex-1 overflow-y-auto p-8">

    <!-- Header Halaman -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Manajemen Data Jenis Hewan</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola spesifikasi jenis hewan peliharaan</p>
        </div>
        <a href="tambah.php"
            class="bg-paw-hitam text-paw-putih px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Jenis Baru
        </a>
    </div>

    <!-- BAGIAN TABEL JENIS HEWAN -->
    <div class="bg-paw-putih rounded-2xl shadow-sm border border-paw-krem-gelap overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-paw-krem-gelap text-xs uppercase text-paw-hitam font-bold">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Jenis Hewan</th>
                        <th class="px-6 py-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-paw-krem-gelap">
                    <?php
                    $no = 1;
                    foreach ($data_jenis as $r) {
                        ?>
                        <tr class="hover:bg-paw-krem-utama/50 transition">
                            <td class="px-6 py-4 text-sm text-gray-400 font-medium"><?= $no++; ?></td>
                            <td class="px-6 py-4 font-bold text-paw-hitam">
                                <span class="bg-paw-krem-gelap/50 px-3 py-1 rounded-full text-xs font-bold text-gray-600">
                                    <?= htmlspecialchars($r['nama_jenis']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center w-32">
                                <div class="flex justify-center gap-2">
                                    <a href="edit.php?id=<?= $r['id_jenis']; ?>"
                                        class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-paw-hitam hover:text-paw-putih transition shadow-sm">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <a href="hapus.php?id=<?= $r['id_jenis']; ?>"
                                        onclick="return confirm('Yakin ingin menghapus jenis hewan ini?')"
                                        class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:bg-paw-merah hover:text-paw-putih transition shadow-sm">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($data_jenis)) { ?>
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">
                                Belum ada data jenis hewan...
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>