<div class="flex-1 overflow-y-auto p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">
                Manajemen Pengguna (Admin & Pegawai)
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Kelola data pengguna sistem PawCare
            </p>
        </div>

        <a href="index.php?action=pengguna_tambah"
            class="bg-paw-hitam text-paw-putih px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>
            Tambah Pengguna
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

                <thead class="bg-paw-krem-gelap text-xs uppercase text-paw-hitam font-bold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-paw-krem-gelap">

                    <?php if(empty($pengguna)): ?>

                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 italic">
                                Belum ada data pengguna.
                            </td>
                        </tr>

                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach($pengguna as $p): ?>

                            <tr class="hover:bg-paw-krem-utama/50 transition">

                                <td class="px-6 py-4 text-gray-400 font-medium">
                                    <?= $no++ ?>
                                </td>

                                <td class="px-6 py-4 font-bold text-paw-hitam">
                                    <?= htmlspecialchars($p['nama_lengkap']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($p['jabatan']) ?>
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    <?= htmlspecialchars($p['kontak']) ?>
                                </td>

                                <td class="px-6 py-4 font-mono text-xs">
                                    <?= htmlspecialchars($p['nama_pengguna']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?php if($p['role'] == 'SuperAdmin'): ?>
                                        <span class="bg-red-100 text-paw-merah px-3 py-1 rounded-full text-xs font-bold">
                                            SuperAdmin
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                            Pegawai
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="index.php?action=pengguna_edit&id=<?= $p['id_pengguna'] ?>"
                                            class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-paw-hitam hover:text-paw-putih transition shadow-sm">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>

                                        <a href="index.php?action=pengguna_hapus&id=<?= $p['id_pengguna'] ?>"
                                            onclick="return confirm('Yakin ingin menghapus pengguna ini?')"
                                            class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:bg-paw-merah hover:text-paw-putih transition shadow-sm">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>
        </div>

    </div>

</div>
