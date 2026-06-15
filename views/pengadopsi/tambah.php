<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';
?>

<div class="mb-8">
    <a href="index.php" class="text-gray-500 hover:text-paw-hitam transition flex items-center gap-2 mb-2 font-medium">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
    <h2 class="text-3xl font-bold text-paw-hitam">Tambah Pengadopsi Baru</h2>
    <p class="text-gray-500 mt-1">Isi formulir di bawah dengan lengkap ya.</p>
</div>

<div class="max-w-2xl bg-paw-putih rounded-2xl shadow-sm border border-[#e0d6c8] p-8">
    <form action="proses_tambah.php" method="POST">
        <div class="space-y-6">
            <!-- Nama -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                <input type="text" name="nama" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- No HP -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nomor HP</label>
                    <input type="text" name="no_hp" required placeholder="Contoh: 08123xxx" class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Email</label>
                    <input type="email" name="email" required placeholder="Contoh: budi@gmail.com" class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Kata Sandi (Password)</label>
                <input type="password" name="kata_sandi" required placeholder="Sandi untuk login pengadopsi" class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Alamat Rumah</label>
                <textarea name="alamat" rows="3" required placeholder="Jl. Anggrek No. 123..." class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition"></textarea>
            </div>

            <!-- URL KTP -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">URL Foto KTP</label>
                <input type="text" name="url_ktp" placeholder="Link dokumen/foto KTP" class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
            </div>

            <!-- Status Verifikasi -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Status Verifikasi</label>
                <select name="status_verifikasi" class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
                    <option value="Belum">Belum</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Terverifikasi">Terverifikasi</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>

            <!-- Catatan Verifikasi -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Catatan Verifikasi</label>
                <textarea name="catatan_verifikasi" rows="2" placeholder="Catatan tambahan hasil verifikasi..." class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition"></textarea>
            </div>

            <!-- Tombol Simpan -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-paw-hitam text-paw-putih py-4 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Data Pengadopsi
                </button>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>
