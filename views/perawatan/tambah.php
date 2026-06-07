<div class="flex-1 overflow-y-auto p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Catat Perawatan Harian</h2>
            <p class="text-sm text-gray-500 mt-1">Input kondisi medis dan tindakan untuk hewan</p>
        </div>
        <a href="index.php?action=perawatan" class="text-paw-hitam bg-paw-krem-gelap px-5 py-2.5 rounded-xl text-sm font-bold border border-[#e0d6c8] hover:bg-gray-200 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>Batal
        </a>
    </div>

    <div class="bg-paw-putih p-8 rounded-2xl shadow-sm border border-paw-krem-gelap max-w-3xl">
        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-paw-merah text-paw-merah p-4 mb-6 rounded-r-lg font-medium text-sm">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i><?= htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=perawatan_simpan" method="POST">
            
            <div class="grid grid-cols-2 gap-6 mb-5">
                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Tanggal Perawatan</label>
                    <input type="date" name="tanggal_perawatan" value="<?= date('Y-m-d'); ?>" required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Ditangani Oleh (Pegawai)</label>
                    <select name="id_pegawai" required class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none cursor-pointer">
                        <option value="" disabled selected>-- Pilih Petugas/Dokter --</option>
                        <?php foreach($pegawai as $pgw): ?>
                            <option value="<?= $pgw['id_pegawai']; ?>"><?= htmlspecialchars($pgw['nama']); ?> (<?= htmlspecialchars($pgw['jabatan']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-bold text-paw-hitam">Pasien (Hewan)</label>
                <select name="id_hewan" required class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none cursor-pointer">
                    <option value="" disabled selected>-- Pilih Hewan yang Dirawat --</option>
                    <?php if(empty($hewan)): ?>
                        <option value="" disabled>Belum ada data hewan di sistem!</option>
                    <?php else: ?>
                        <?php foreach($hewan as $hwn): ?>
                            <option value="<?= $hwn['id_hewan']; ?>"><?= htmlspecialchars($hwn['nama_hewan']); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-bold text-paw-hitam">Hasil Pemeriksaan / Gejala</label>
                <textarea name="pemeriksaan" rows="2" placeholder="Contoh: Suhu tubuh normal, telinga sedikit kotor..."
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none resize-none"></textarea>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-bold text-paw-hitam">Tindakan / Perawatan yang Dilakukan</label>
                <textarea name="perawatan" rows="2" placeholder="Contoh: Grooming, potong kuku, pembersihan telinga..."
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none resize-none"></textarea>
            </div>

            <div class="mb-8">
                <label class="block mb-2 text-sm font-bold text-paw-hitam">Pemberian Obat (Jika Ada)</label>
                <input type="text" name="pemberian_obat" placeholder="Contoh: Vitamin Bulu, Obat Cacing Drontal..."
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none">
            </div>

            <div class="flex justify-end border-t border-paw-krem-gelap pt-5">
                <button type="submit" class="bg-paw-hitam text-paw-putih px-6 py-3 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Perawatan
                </button>
            </div>
        </form>
    </div>
</div>