<div class="flex-1 overflow-y-auto p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">
                Manajemen Vaksin
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Kelola data vaksin shelter
            </p>
        </div>

        <a href="index.php?action=vaksin_tambah"
            class="bg-paw-hitam text-paw-putih px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md">
            <i class="fa-solid fa-plus mr-2"></i>
            Tambah Vaksin
        </a>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-600 text-green-700 p-4 mb-6 rounded-r-lg">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <div class="bg-paw-putih rounded-2xl shadow-sm border border-paw-krem-gelap overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-paw-krem-gelap text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nama Vaksin</th>
                        <th class="px-6 py-4">Jadwal</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-paw-krem-gelap">

                    <?php foreach($vaksin as $v): ?>

                        <tr class="hover:bg-paw-krem-utama/50">

                            <td class="px-6 py-4 font-bold">
                                VKS-<?= str_pad($v['id_vaksin'],3,'0',STR_PAD_LEFT) ?>
                            </td>

                            <td class="px-6 py-4 font-bold">
                                <?= htmlspecialchars($v['nama_vaksin']) ?>
                            </td>

                            <td class="px-6 py-4">
                                <?= htmlspecialchars($v['jadwal']) ?>
                            </td>

                            <td class="px-6 py-4">
                                <?= htmlspecialchars($v['keterangan']) ?>
                            </td>

                            <td class="px-6 py-4 text-center">

                                <a href="index.php?action=vaksin_edit&id=<?= $v['id_vaksin'] ?>"
                                    class="text-blue-600 mx-2">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <a href="index.php?action=vaksin_hapus&id=<?= $v['id_vaksin'] ?>"
                                    onclick="return confirm('Yakin hapus data?')"
                                    class="text-red-600 mx-2">
                                    <i class="fa-solid fa-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>