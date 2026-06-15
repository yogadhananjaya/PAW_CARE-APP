<?php
// Variabel dipass dari HewanController::edit()
// @var array $hewan Data hewan yang akan diedit
// @var array $ras Daftar ras hewan
if (!isset($hewan) || !isset($ras)) {
    die('Error: Data hewan atau ras tidak ditemukan.');
}
?>

<div class="flex-1 overflow-y-auto p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">
                Edit Data Hewan
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Perbarui data hewan shelter
            </p>
        </div>

        <a href="index.php?action=hewan"
            class="bg-paw-krem-gelap text-paw-hitam px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-200 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="bg-paw-putih p-8 rounded-2xl shadow-sm border border-paw-krem-gelap max-w-3xl">

        <form action="index.php?action=hewan_update" method="POST">

            <input type="hidden"
                   name="id_hewan"
                   value="<?= $hewan['id_hewan'] ?>">

            <!-- Nama Hewan -->
            <div class="mb-5">
                <label class="block mb-2 text-sm font-bold">
                    Nama Hewan
                </label>

                <input type="text"
                       name="nama_hewan"
                       value="<?= htmlspecialchars($hewan['nama_hewan']) ?>"
                       required
                       class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">
            </div>

            <!-- Ras -->
            <div class="mb-5">
                <label class="block mb-2 text-sm font-bold">
                    Ras
                </label>

                <select name="id_ras"
                        required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">

                    <?php foreach($ras as $r): ?>

                        <option value="<?= $r['id_ras'] ?>"
                            <?= ($hewan['id_ras'] == $r['id_ras']) ? 'selected' : '' ?>>

                            <?= htmlspecialchars($r['nama_ras']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>
            </div>

            <!-- Tanggal Lahir -->
            <div class="mb-5">
                <label class="block mb-2 text-sm font-bold">
                    Tanggal Lahir
                </label>

                <input type="date"
                       name="tanggal_lahir"
                       value="<?= $hewan['tanggal_lahir'] ?>"
                       class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">
            </div>

            <!-- Estimasi Umur -->
            <div class="mb-5">
                <label class="block mb-2 text-sm font-bold">
                    Estimasi Umur (Tahun)
                </label>

                <input type="number"
                       name="estimasi_umur"
                       min="0"
                       value="<?= $hewan['estimasi_umur'] ?>"
                       class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-5">
                <label class="block mb-2 text-sm font-bold">
                    Jenis Kelamin
                </label>

                <select name="jenis_kelamin"
                        required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">

                    <option value="Jantan"
                        <?= ($hewan['jenis_kelamin'] == 'Jantan') ? 'selected' : '' ?>>
                        Jantan
                    </option>

                    <option value="Betina"
                        <?= ($hewan['jenis_kelamin'] == 'Betina') ? 'selected' : '' ?>>
                        Betina
                    </option>

                </select>
            </div>

            <!-- Status Adopsi -->
            <div class="mb-8">
                <label class="block mb-2 text-sm font-bold">
                    Status Adopsi
                </label>

                <select name="status_adopsi"
                        required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap rounded-xl p-3">

                    <option value="Tersedia"
                        <?= ($hewan['status_adopsi'] == 'Tersedia') ? 'selected' : '' ?>>
                        Tersedia
                    </option>

                    <option value="Diproses"
                        <?= ($hewan['status_adopsi'] == 'Diproses') ? 'selected' : '' ?>>
                        Diproses
                    </option>

                    <option value="Diadopsi"
                        <?= ($hewan['status_adopsi'] == 'Diadopsi') ? 'selected' : '' ?>>
                        Diadopsi
                    </option>

                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Update Data
                </button>
            </div>

        </form>

    </div>

</div>