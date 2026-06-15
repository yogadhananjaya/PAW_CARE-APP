<div class="flex-1 overflow-y-auto p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">
                Tambah Hewan Baru
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Masukkan data hewan shelter secara lengkap
            </p>
        </div>

        <a href="index.php?action=hewan"
            class="text-paw-hitam bg-paw-krem-gelap px-5 py-2.5 rounded-xl text-sm font-bold border border-[#e0d6c8] hover:bg-gray-200 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="bg-paw-putih p-8 rounded-2xl shadow-sm border border-paw-krem-gelap max-w-4xl">

        <form action="index.php?action=hewan_simpan" method="POST" enctype="multipart/form-data">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nama Hewan -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">
                        Nama Hewan
                    </label>
                    <input type="text"
                        name="nama_hewan"
                        required
                        placeholder="Contoh: Milo, Kitty"
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                </div>

                <!-- Ras Hewan -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">
                        Jenis / Ras Hewan
                    </label>
                    <select name="id_ras" required
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                        <option value="">-- Pilih Ras --</option>
                        <?php foreach ($ras as $r): ?>
                            <option value="<?= $r['id_ras'] ?>">
                                <?= htmlspecialchars($r['nama_jenis']) ?> - <?= htmlspecialchars($r['nama_ras']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Umur -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">
                        Umur (Tahun)
                    </label>
                    <input type="number"
                        name="umur"
                        required
                        min="0"
                        placeholder="Contoh: 2"
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">
                        Jenis Kelamin
                    </label>
                    <select name="jenis_kelamin" required
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>

                <!-- Status Adopsi -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">
                        Status Adopsi
                    </label>
                    <select name="status_adopsi" required
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                        <option value="Tersedia">Tersedia</option>
                        <option value="Karantina">Karantina</option>
                        <option value="Dalam Proses">Dalam Proses</option>
                        <option value="Diadopsi">Diadopsi</option>
                    </select>
                </div>

                <!-- Sumber Intake -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">
                        Sumber Intake
                    </label>
                    <select name="sumber_intake" id="sumber_intake" required
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                        <option value="Legacy">Legacy</option>
                        <option value="Breeding">Breeding</option>
                        <option value="Donasi">Donasi</option>
                    </select>
                </div>

                <!-- Donatur Section (Hanya muncul jika donasi) -->
                <div class="hidden col-span-2 grid grid-cols-2 gap-6" id="section_donatur">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">
                            Nama Donatur
                        </label>
                        <input type="text"
                            name="nama_donatur"
                            placeholder="Nama Donatur"
                            class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">
                            Kontak Donatur
                        </label>
                        <input type="text"
                            name="kontak_donatur"
                            placeholder="Nomor HP/WA Donatur"
                            class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                    </div>
                </div>

                <!-- Tanggal Intake -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">
                        Tanggal Intake
                    </label>
                    <input type="date"
                        name="tanggal_intake"
                        required
                        value="<?= date('Y-m-d') ?>"
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                </div>

                <!-- Upload Foto Hewan -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">
                        Foto Hewan
                    </label>
                    <input type="file"
                        name="foto_hewan"
                        accept="image/*"
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-2 outline-none focus:border-paw-hitam transition">
                </div>

                <!-- Keterangan Intake -->
                <div class="col-span-2">
                    <label class="block mb-2 text-sm font-bold text-gray-700">
                        Keterangan Intake
                    </label>
                    <textarea name="keterangan_intake" rows="2"
                        placeholder="Catatan mengenai riwayat intake hewan..."
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition"></textarea>
                </div>

                <!-- Deskripsi Hewan -->
                <div class="col-span-2">
                    <label class="block mb-2 text-sm font-bold text-gray-700">
                        Deskripsi Hewan
                    </label>
                    <textarea name="deskripsi" rows="3"
                        placeholder="Jelaskan karakteristik, kebiasaan, atau kondisi umum hewan..."
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition"></textarea>
                </div>

            </div>

            <div class="flex justify-end border-t border-paw-krem-gelap pt-5 mt-8">
                <button type="submit"
                    class="bg-paw-hitam text-paw-putih px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-md">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Simpan Hewan
                </button>
            </div>

        </form>

        <script>
            const sumberIntake = document.getElementById('sumber_intake');
            const sectionDonatur = document.getElementById('section_donatur');

            sumberIntake.addEventListener('change', function() {
                if (this.value === 'Donasi') {
                    sectionDonatur.classList.remove('hidden');
                } else {
                    sectionDonatur.classList.add('hidden');
                }
            });
        </script>

    </div>

</div>