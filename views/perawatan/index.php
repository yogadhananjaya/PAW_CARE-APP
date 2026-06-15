<div class="flex-1 overflow-y-auto p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Riwayat Perawatan</h2>
            <p class="text-sm text-gray-500 mt-1">Catatan medis dan rutinitas harian hewan shelter</p>
        </div>
        <a href="index.php?action=perawatan_tambah" class="bg-paw-hitam text-paw-putih px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center">
            <i class="fa-solid fa-notes-medical mr-2"></i>Catat Perawatan Baru
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-600 text-green-700 p-4 mb-6 rounded-r-lg font-medium text-sm flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i><?= htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-paw-putih rounded-2xl shadow-sm border border-paw-krem-gelap overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-paw-hitam">
                <thead class="text-xs bg-paw-krem-gelap uppercase font-bold text-paw-hitam">
                    <tr>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Pasien (Hewan)</th>
                        <th class="px-5 py-4 w-1/2">Catatan Medis & Tindakan</th>
                        <th class="px-5 py-4">Ditangani Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-paw-krem-gelap">
                    <?php if (empty($perawatan)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fa-solid fa-stethoscope text-3xl mb-3 text-gray-300 block"></i>
                                Belum ada riwayat perawatan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($perawatan as $p): ?>
                            <tr class="hover:bg-paw-krem-utama/50 transition">
                                <td class="px-5 py-4 font-bold text-gray-600 whitespace-nowrap">
                                    <?= date('d M Y', strtotime($p['tanggal'])); ?>
                                </td>
                                <td class="px-5 py-4 font-bold text-paw-merah">
                                    <i class="fa-solid fa-paw mr-1"></i> <?= htmlspecialchars($p['nama_hewan'] ?? 'Hewan Dihapus'); ?>
                                </td>
                                <td class="px-5 py-4 text-gray-600 leading-relaxed whitespace-pre-line"><?= htmlspecialchars($p['deskripsi'] ?: '-'); ?></td>
                                <td class="px-5 py-4 font-medium whitespace-nowrap">
                                    <i class="fa-solid fa-user-doctor text-gray-400 mr-1"></i> <?= htmlspecialchars($p['nama_pegawai'] ?? 'Anonim'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>