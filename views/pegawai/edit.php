<div class="flex-1 overflow-y-auto p-8">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Edit Data Pegawai</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi staf atau dokter hewan</p>
        </div>
        <a href="index.php?action=pegawai" class="text-paw-hitam bg-paw-krem-gelap px-5 py-2.5 rounded-xl text-sm font-bold border border-[#e0d6c8] hover:bg-gray-200 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>Batal
        </a>
    </div>

    <div class="bg-paw-putih p-8 rounded-2xl shadow-sm border border-paw-krem-gelap max-w-2xl">
        
        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-paw-merah text-paw-merah p-4 mb-6 rounded-r-lg font-medium text-sm">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i><?= htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=pegawai_update" method="POST">
            <input type="hidden" name="id_pegawai" value="<?= $pegawai['id_pegawai']; ?>">
            
            <div class="mb-5">
                <label for="nama" class="block mb-2 text-sm font-bold text-paw-hitam">Nama Lengkap Pegawai</label>
                <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($pegawai['nama']); ?>" required autocomplete="off"
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam focus:border-paw-hitam block p-3 transition outline-none">
            </div>

            <div class="mb-5">
                <label for="jabatan" class="block mb-2 text-sm font-bold text-paw-hitam">Jabatan / Posisi</label>
                <select id="jabatan" name="jabatan" required
    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam focus:border-paw-hitam block p-3 transition outline-none cursor-pointer">
    
    <option value="Dokter Hewan" <?= ($pegawai['jabatan'] == 'Dokter Hewan') ? 'selected' : ''; ?>>Dokter Hewan</option>
    <option value="Perawat Hewan" <?= ($pegawai['jabatan'] == 'Perawat Hewan') ? 'selected' : ''; ?>>Perawat Hewan</option>
    <option value="Admin Shelter" <?= ($pegawai['jabatan'] == 'Admin Shelter') ? 'selected' : ''; ?>>Admin Shelter</option>
    <option value="Staff Kebersihan" <?= ($pegawai['jabatan'] == 'Staff Kebersihan') ? 'selected' : ''; ?>>Staff Kebersihan</option>

</select>
            </div>

            <div class="mb-8">
                <label for="kontak" class="block mb-2 text-sm font-bold text-paw-hitam">Nomor Kontak</label>
                <input type="text" id="kontak" name="kontak" value="<?= htmlspecialchars($pegawai['kontak']); ?>" required autocomplete="off"
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam focus:border-paw-hitam block p-3 transition outline-none">
            </div>

            <div class="flex justify-end border-t border-paw-krem-gelap pt-5 mt-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-md flex items-center">
                    <i class="fa-solid fa-pen-to-square mr-2"></i>Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>