<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';

// Perintah SQL untuk mengambil semua data pengadopsi
$stmt = $pdo->query("SELECT * FROM pengadopsi ORDER BY id_pengadopsi DESC");
$hasil = $stmt->fetchAll();
?>

<div class="flex-1 overflow-y-auto p-8">

    <!-- Header Halaman -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Data Pengadopsi</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar orang yang ingin atau sudah mengadopsi hewan.</p>
        </div>
        <a href="tambah.php"
            class="bg-paw-hitam text-paw-putih px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Pengadopsi
        </a>
    </div>

    <!-- TABEL DATA -->
    <div class="bg-paw-putih rounded-2xl shadow-sm border border-paw-krem-gelap overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-paw-krem-gelap text-xs uppercase text-paw-hitam font-bold">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">No. HP</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4 text-center">Verifikasi</th>
                        <th class="px-6 py-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-paw-krem-gelap">
                    <?php 
                    $no = 1;
                    foreach ($hasil as $row) { 
                    ?>
                    <tr class="hover:bg-paw-krem-utama/50 transition">
                        <td class="px-6 py-4 text-sm text-gray-400 font-medium"><?= $no++; ?></td>
                        <td class="px-6 py-4 font-bold text-paw-hitam"><?= htmlspecialchars($row['nama']); ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['no_hp']); ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['email']); ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['alamat']); ?></td>
                        <td class="px-6 py-4 text-center">
                            <?php if($row['status_verifikasi'] == 'Terverifikasi'): ?>
                                <span class="bg-green-100 text-green-700 border border-green-200 px-3 py-1 rounded-full text-xs font-bold">Terverifikasi</span>
                            <?php elseif($row['status_verifikasi'] == 'Menunggu'): ?>
                                <span class="bg-yellow-100 text-yellow-700 border border-yellow-200 px-3 py-1 rounded-full text-xs font-bold">Menunggu</span>
                            <?php elseif($row['status_verifikasi'] == 'Ditolak'): ?>
                                <span class="bg-red-100 text-paw-merah border border-red-200 px-3 py-1 rounded-full text-xs font-bold">Ditolak</span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-700 border border-gray-200 px-3 py-1 rounded-full text-xs font-bold">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center w-32">
                            <div class="flex justify-center gap-2">
                                <a href="edit.php?id=<?= $row['id_pengadopsi']; ?>"
                                    class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-paw-hitam hover:text-paw-putih transition shadow-sm">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <a href="hapus.php?id=<?= $row['id_pengadopsi']; ?>"
                                    onclick="return confirm('Beneran mau hapus data ini?')"
                                    class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:bg-paw-merah hover:text-paw-putih transition shadow-sm">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if (count($hasil) == 0) { ?>
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                            Belum ada data pengadopsi nih...
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>
