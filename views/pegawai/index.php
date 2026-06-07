<div class="flex-1 overflow-y-auto p-8">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Manajemen Pegawai</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data staf dan dokter hewan shelter</p>
        </div>
        <a href="index.php?action=pegawai_tambah" class="bg-paw-hitam text-paw-putih px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Pegawai
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-600 text-green-700 p-4 mb-6 rounded-r-lg font-medium text-sm flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i>
            <?= htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-paw-putih rounded-2xl shadow-sm border border-paw-krem-gelap overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-paw-hitam">
                <thead class="text-xs bg-paw-krem-gelap uppercase font-bold text-paw-hitam">
                    <tr>
                        <th scope="col" class="px-6 py-4">ID</th>
                        <th scope="col" class="px-6 py-4">Nama Pegawai</th>
                        <th scope="col" class="px-6 py-4">Jabatan</th>
                        <th scope="col" class="px-6 py-4">Kontak</th>
                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-paw-krem-gelap">
                    <?php if (empty($pegawai)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fa-solid fa-folder-open text-3xl mb-3 text-gray-300 block"></i>
                                Belum ada data pegawai terdaftar.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pegawai as $p): ?>
                            <tr class="hover:bg-paw-krem-utama/50 transition">
                                <td class="px-6 py-4 font-bold text-gray-600">
                                    PGW-<?= str_pad($p['id_pegawai'], 3, '0', STR_PAD_LEFT); ?>
                                </td>
                                <td class="px-6 py-4 font-bold text-paw-hitam"><?= htmlspecialchars($p['nama']); ?></td>
                                <td class="px-6 py-4">
                                    <span class="bg-paw-krem-gelap text-paw-hitam px-3 py-1 rounded-md text-xs font-bold border border-[#e0d6c8]">
                                        <?= htmlspecialchars($p['jabatan']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($p['kontak']); ?></td>
                                <td class="px-6 py-4 text-center text-lg">
                                    <a href="index.php?action=pegawai_edit&id=<?= $p['id_pegawai']; ?>" class="text-paw-hitam hover:text-blue-600 transition mx-2" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button onclick="bukaModalHapus(<?= $p['id_pegawai']; ?>)" class="text-paw-merah hover:text-red-700 transition mx-2" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalHapus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-paw-hitam/40 backdrop-blur-sm transition-opacity">
        <div class="bg-paw-putih p-8 rounded-3xl shadow-xl w-96 text-center transform scale-95 transition-transform border border-paw-krem-gelap">
            
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5 text-paw-merah text-4xl shadow-inner">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            
            <h3 class="text-2xl font-black text-paw-hitam mb-2">Hapus Pegawai?</h3>
            <p class="text-gray-500 text-sm mb-8">Data yang dihapus tidak dapat dikembalikan lagi. Apakah Anda yakin?</p>
            
            <div class="flex gap-3 justify-center">
                <button onclick="tutupModalHapus()" class="flex-1 bg-paw-krem-gelap text-paw-hitam py-3 rounded-xl font-bold hover:bg-gray-200 transition border border-[#e0d6c8]">
                    Tidak, Batal
                </button>
                <a id="btnKonfirmasiHapus" href="#" class="flex-1 bg-paw-merah text-white py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-md">
                    Ya, Hapus
                </a>
            </div>

        </div>
    </div>

   <div id="modalHapus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-paw-hitam/40 backdrop-blur-sm transition-opacity">
        <div class="bg-paw-putih p-8 rounded-3xl shadow-xl w-96 text-center transform scale-95 transition-transform border border-paw-krem-gelap">
            
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5 text-paw-merah text-4xl shadow-inner">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            
            <h3 class="text-2xl font-black text-paw-hitam mb-2">Hapus Pegawai?</h3>
            <p class="text-gray-500 text-sm mb-8">Data yang dihapus tidak dapat dikembalikan lagi. Apakah Anda yakin?</p>
            
            <div class="flex gap-3 justify-center">
                <button onclick="tutupModalHapus()" class="flex-1 bg-paw-krem-gelap text-paw-hitam py-3 rounded-xl font-bold hover:bg-gray-200 transition border border-[#e0d6c8]">
                    Tidak, Batal
                </button>
                <a id="btnKonfirmasiHapus" href="#" class="flex-1 bg-paw-merah text-white py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-md">
                    Ya, Hapus
                </a>
            </div>

        </div>
    </div>

    <script>
        const modal = document.getElementById('modalHapus');
        const btnKonfirmasi = document.getElementById('btnKonfirmasiHapus');

        function bukaModalHapus(idPegawai) {
            // Tampilkan modal
            modal.classList.remove('hidden');
            // Set tujuan link penghapusan sesuai ID yang diklik
            btnKonfirmasi.href = 'index.php?action=pegawai_hapus&id=' + idPegawai;
        }

        function tutupModalHapus() {
            // Sembunyikan modal
            modal.classList.add('hidden');
        }

        // Tutup modal jika user klik area gelap di luar kotak putih
        window.onclick = function(event) {
            if (event.target == modal) {
                tutupModalHapus();
            }
        }
    </script>