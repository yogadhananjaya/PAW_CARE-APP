<div class="flex-1 overflow-y-auto p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">
                Tambah Hewan Baru
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Masukkan data hewan shelter
            </p>
        </div>

        <a href="index.php?action=hewan"
            class="text-paw-hitam bg-paw-krem-gelap px-5 py-2.5 rounded-xl text-sm font-bold border border-[#e0d6c8] hover:bg-gray-200 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="bg-paw-putih p-8 rounded-2xl shadow-sm border border-paw-krem-gelap max-w-3xl">

        <form action="index.php?action=hewan_simpan" method="POST">

            <div class="grid grid-cols-2 gap-5">

                <div>
                    <label class="block mb-2 text-sm font-bold">
                        Nama Hewan
                    </label>

                    <input type="text"
                        name="nama_hewan"
                        required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold">
                        Jenis/Ras Hewan
                    </label>

                    <select name="id_ras" required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">

                        <option value="">-- Pilih Ras --</option>

                        <?php foreach ($ras as $r): ?>

                            <option value="<?= $r['id_ras'] ?>">

                                <?= $r['nama_jenis'] ?>
                                -
                                <?= $r['nama_ras'] ?>

                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold">
                        Tanggal Lahir
                    </label>

                    <input type="date"
                        id="tanggal_lahir"
                        name="tanggal_lahir"
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold">
                        Estimasi Umur
                    </label>

                    <input type="text"
                        id="estimasi_umur"
                        readonly
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">

                    <input type="hidden"
                        id="estimasi_umur_db"
                        name="estimasi_umur">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold">
                        Jenis Kelamin
                    </label>

                    <select name="jenis_kelamin"
                        required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">

                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>

                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold">
                        Status Adopsi
                    </label>

                    <select name="status_adopsi"
                        required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">

                        <option value="Tersedia">Tersedia</option>
                        <option value="Diproses">Diproses</option>
                        <option value="Diadopsi">Diadopsi</option>

                    </select>
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
            const tanggalLahir = document.getElementById('tanggal_lahir');
            const umurTampil = document.getElementById('estimasi_umur');
            const umurDb = document.getElementById('estimasi_umur_db');

            tanggalLahir.addEventListener('change', function() {

                if (!this.value) {
                    umurTampil.value = '';
                    umurDb.value = '';
                    return;
                }

                const lahir = new Date(this.value);
                const sekarang = new Date();

                let totalBulan =
                    (sekarang.getFullYear() - lahir.getFullYear()) * 12 +
                    (sekarang.getMonth() - lahir.getMonth());

                if (sekarang.getDate() < lahir.getDate()) {
                    totalBulan--;
                }

                if (totalBulan < 0) totalBulan = 0;

                umurDb.value = totalBulan;

                if (totalBulan < 12) {
                    umurTampil.value = totalBulan + ' Bulan';
                } else {
                    umurTampil.value = Math.floor(totalBulan / 12) + ' Tahun';
                }
            });
        </script>

    </div>

</div>