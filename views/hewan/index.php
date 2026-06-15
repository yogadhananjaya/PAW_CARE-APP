<div class="flex-1 overflow-y-auto p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">
                Manajemen Hewan
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Kelola data hewan shelter
            </p>
        </div>

        <a href="index.php?action=hewan_tambah"
            class="bg-paw-hitam text-paw-putih px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>
            Tambah Hewan
        </a>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-600 text-green-700 p-4 mb-6 rounded-r-lg">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-600 text-red-700 p-4 mb-6 rounded-r-lg">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <div class="bg-paw-putih rounded-2xl shadow-sm border border-paw-krem-gelap overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-paw-krem-gelap text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nama Hewan</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Ras</th>
                        <th class="px-6 py-4">Umur</th>
                        <th class="px-6 py-4">Kelamin</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-paw-krem-gelap">

                    <?php if(empty($hewan)): ?>

                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                Belum ada data hewan.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach($hewan as $h): ?>

                            <tr class="hover:bg-paw-krem-utama/50 transition">

                                <td class="px-6 py-4 font-bold text-gray-600">
                                    HWN-<?= str_pad($h['id_hewan'], 3, '0', STR_PAD_LEFT) ?>
                                </td>

                                <td class="px-6 py-4 font-bold">
                                    <?= htmlspecialchars($h['nama_hewan']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($h['nama_jenis']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($h['nama_ras']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($h['umur']) ?> Tahun
                                </td>

                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($h['jenis_kelamin']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?php if($h['status_adopsi'] == 'Tersedia'): ?>
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                            Tersedia
                                        </span>
                                    <?php elseif($h['status_adopsi'] == 'Karantina'): ?>
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">
                                            Karantina
                                        </span>
                                    <?php elseif($h['status_adopsi'] == 'Dalam Proses'): ?>
                                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                            Dalam Proses
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                                            Diadopsi
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 text-center">

                                    <a href="index.php?action=hewan_edit&id=<?= $h['id_hewan'] ?>"
                                        class="text-blue-600 hover:text-blue-800 mx-2">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <a href="index.php?action=hewan_hapus&id=<?= $h['id_hewan'] ?>"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                                        class="text-red-600 hover:text-red-800 mx-2">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>
        </div>

    </div>

</div>