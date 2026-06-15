<div class="flex-1 overflow-y-auto p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">
                Tambah Pengguna Baru
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Masukkan data admin atau pegawai baru
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

        <form action="index.php?action=pengguna_simpan" method="POST">

            <div class="space-y-6">

                <!-- Nama Lengkap -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required placeholder="Contoh: Ahmad Subardjo"
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Jabatan -->
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Jabatan</label>
                        <select name="jabatan" required
                            class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                            <option value="SuperAdmin">SuperAdmin</option>
                            <option value="Perawat Hewan">Perawat Hewan</option>
                            <option value="Koordinator">Koordinator</option>
                        </select>
                    </div>

                    <!-- Kontak -->
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Kontak (No HP)</label>
                        <input type="text" name="kontak" required placeholder="Contoh: 081234xxx"
                            class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Username -->
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Username</label>
                        <input type="text" name="nama_pengguna" required placeholder="Username unik" autocomplete="off"
                            class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Password</label>
                        <input type="password" name="kata_sandi" required placeholder="Password login"
                            class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                    </div>
                </div>

                <!-- Role -->
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Role Sistem</label>
                    <select name="role" required
                        class="w-full bg-paw-krem-utama border border-[#e0d6c8] rounded-xl p-3 outline-none focus:border-paw-hitam transition">
                        <option value="Pegawai">Pegawai (Staff)</option>
                        <option value="SuperAdmin">SuperAdmin</option>
                    </select>
                </div>

                <div class="flex justify-end pt-4 border-t border-paw-krem-gelap">
                    <button type="submit"
                        class="bg-paw-hitam text-paw-putih px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-md w-full">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Pengguna
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>
