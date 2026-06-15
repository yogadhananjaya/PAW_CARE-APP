<div class="flex-1 overflow-y-auto p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">
                Ubah Data Pengguna
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Perbarui data admin atau pegawai
            </p>
        </div>

        <a href="index.php?action=pengguna"
            class="text-paw-hitam bg-paw-krem-gelap px-5 py-2.5 rounded-xl text-sm font-bold border border-[#e0d6c8] hover:bg-gray-200 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-600 text-red-700 p-4 mb-6 rounded-r-lg max-w-2xl">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <div class="bg-paw-putih p-8 rounded-2xl shadow-sm border border-paw-krem-gelap max-w-2xl">

        <form action="index.php?action=pengguna_update" method="POST">
            <input type="hidden" name="id_pengguna" value="<?= htmlspecialchars($pengguna['id_pengguna']) ?>">

            <div class="space-y-6">

                <!-- Nama Lengkap -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required value="<?= htmlspecialchars($pengguna['nama_lengkap']) ?>"
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Jabatan -->
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Jabatan</label>
                        <select name="jabatan" required
                            class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                            <option value="SuperAdmin" <?= $pengguna['jabatan'] === 'SuperAdmin' ? 'selected' : '' ?>>SuperAdmin</option>
                            <option value="Perawat Hewan" <?= $pengguna['jabatan'] === 'Perawat Hewan' ? 'selected' : '' ?>>Perawat Hewan</option>
                            <option value="Koordinator" <?= $pengguna['jabatan'] === 'Koordinator' ? 'selected' : '' ?>>Koordinator</option>
                        </select>
                    </div>

                    <!-- Kontak -->
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Kontak (No HP)</label>
                        <input type="text" name="kontak" required value="<?= htmlspecialchars($pengguna['kontak']) ?>"
                            class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Username -->
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Username</label>
                        <input type="text" name="nama_pengguna" required value="<?= htmlspecialchars($pengguna['nama_pengguna']) ?>" autocomplete="off"
                            class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                    </div>

                    <!-- Password (Opsional) -->
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Password Baru (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="kata_sandi" placeholder="Password baru login"
                            class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                    </div>
                </div>

                <!-- Role -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Role Sistem</label>
                    <select name="role" required
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                        <option value="Pegawai" <?= $pengguna['role'] === 'Pegawai' ? 'selected' : '' ?>>Pegawai (Staff)</option>
                        <option value="SuperAdmin" <?= $pengguna['role'] === 'SuperAdmin' ? 'selected' : '' ?>>SuperAdmin</option>
                    </select>
                </div>

                <div class="flex justify-end pt-4 border-t border-paw-krem-gelap">
                    <button type="submit"
                        class="bg-paw-hitam text-paw-putih px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-md w-full">
                        <i class="fa-solid fa-check mr-2"></i>Update Pengguna
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>
