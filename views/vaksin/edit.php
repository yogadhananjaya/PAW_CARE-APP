<div class="flex-1 overflow-y-auto p-8">

    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold">
            Edit Vaksin
        </h2>

        <a href="index.php?action=vaksin"
            class="bg-paw-krem-gelap px-5 py-2 rounded-xl">
            Kembali
        </a>
    </div>

    <div class="bg-paw-putih p-8 rounded-2xl shadow-sm border border-paw-krem-gelap max-w-3xl">

        <form action="index.php?action=vaksin_update" method="POST">

            <input type="hidden"
                name="id_vaksin"
                value="<?= $vaksin['id_vaksin'] ?>">

            <div class="space-y-5">

                <div>
                    <label class="block mb-2 font-bold">
                        Nama Vaksin
                    </label>

                    <input type="text"
                        name="nama_vaksin"
                        value="<?= htmlspecialchars($vaksin['nama_vaksin']) ?>"
                        required
                        class="w-full border rounded-xl p-3">
                </div>

                <div>
                    <label class="block mb-2 font-bold">
                        Jadwal
                    </label>

                    <input type="text"
                        name="jadwal"
                        value="<?= htmlspecialchars($vaksin['jadwal']) ?>"
                        class="w-full border rounded-xl p-3">
                </div>

                <div>
                    <label class="block mb-2 font-bold">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        rows="4"
                        class="w-full border rounded-xl p-3"><?= htmlspecialchars($vaksin['keterangan']) ?></textarea>
                </div>

            </div>

            <div class="flex justify-end mt-8">

                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">

                    Update Vaksin

                </button>

            </div>

        </form>

    </div>

</div>