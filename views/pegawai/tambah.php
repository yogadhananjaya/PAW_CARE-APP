<div class="flex-1 overflow-y-auto p-8">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Tambah Pegawai Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Masukkan data staf atau dokter hewan ke dalam sistem</p>
        </div>
        <a href="index.php?action=pegawai" class="text-paw-hitam bg-paw-krem-gelap px-5 py-2.5 rounded-xl text-sm font-bold border border-[#e0d6c8] hover:bg-gray-200 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="bg-paw-putih p-8 rounded-2xl shadow-sm border border-paw-krem-gelap max-w-2xl">
        
        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-paw-merah text-paw-merah p-4 mb-6 rounded-r-lg font-medium text-sm">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i><?= htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=pegawai_simpan" method="POST">
            
            <div class="mb-5">
                <label for="nama" class="block mb-2 text-sm font-bold text-paw-hitam">Nama Lengkap Pegawai</label>
                <input type="text" id="nama" name="nama" required autocomplete="off" placeholder="Contoh: drh. Andi Budi"
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam focus:border-paw-hitam block p-3 transition outline-none">
            </div>

            <div class="mb-5">
                <label for="jabatan" class="block mb-2 text-sm font-bold text-paw-hitam">Jabatan / Posisi</label>
                <select id="jabatan" name="jabatan" required
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam focus:border-paw-hitam block p-3 transition outline-none cursor-pointer">
                    <option value="" disabled selected>-- Pilih Jabatan --</option>
                    <option value="Dokter Hewan">Dokter Hewan</option>
                    <option value="Perawat Hewan">Perawat Hewan</option>
                    <option value="Admin Shelter">Admin Shelter</option>
                    <option value="Staff Kebersihan">Staff Kebersihan</option>
                </select>
            </div>

            <div class="mb-8">
                <label for="kontak" class="block mb-2 text-sm font-bold text-paw-hitam">Nomor Kontak (WhatsApp / HP)</label>
                <input type="text" id="kontak" name="kontak" required autocomplete="off" placeholder="Contoh: 081234567890"
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam focus:border-paw-hitam block p-3 transition outline-none">
            </div>

            <div class="flex justify-end border-t border-paw-krem-gelap pt-5 mt-2">
                <button type="submit" class="bg-paw-hitam text-paw-putih px-6 py-3 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Pegawai
                </button>
            </div>

        </form>
    </div>
</div>