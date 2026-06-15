<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}
require_once __DIR__ . '/../../views/layouts/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID pengadopsi tidak valid');
}

// CARI DATA YANG ID-NYA COCOK
$stmt = $pdo->prepare("SELECT * FROM pengadopsi WHERE id_pengadopsi = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die('Pengadopsi tidak ditemukan');
}
?>

<div class="mb-8">
    <a href="index.php" class="text-gray-500 hover:text-paw-hitam transition flex items-center gap-2 mb-2 font-medium">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
    <h2 class="text-3xl font-bold text-paw-hitam">Ubah Data Pengadopsi</h2>
    <p class="text-gray-500 mt-1">Ubah bagian yang salah aja ya.</p>
</div>

<div class="max-w-2xl bg-paw-putih rounded-2xl shadow-sm border border-[#e0d6c8] p-8">
    <form action="proses_edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $data['id_pengadopsi']; ?>">

        <div class="space-y-6">
            <!-- Nama -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama'] ?? '') ?>" required class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- No HP -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nomor HP</label>
                    <input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp'] ?? '') ?>" required class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
                </div>
            </div>

            <!-- Password (Opsional) -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Kata Sandi Baru (Kosongkan jika tidak ingin diubah)</label>
                <input type="password" name="kata_sandi" placeholder="Sandi baru pengadopsi" class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Alamat Rumah</label>
                <textarea name="alamat" rows="3" required class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition"><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
            </div>

            <!-- URL KTP -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">URL Foto KTP</label>
                <input type="text" name="url_ktp" value="<?= htmlspecialchars($data['url_ktp'] ?? '') ?>" placeholder="Link dokumen/foto KTP" class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
            </div>

            <!-- Status Verifikasi -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Status Verifikasi</label>
                <select name="status_verifikasi" class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition">
                    <option value="Belum" <?= ($data['status_verifikasi'] === 'Belum') ? 'selected' : '' ?>>Belum</option>
                    <option value="Menunggu" <?= ($data['status_verifikasi'] === 'Menunggu') ? 'selected' : '' ?>>Menunggu</option>
                    <option value="Terverifikasi" <?= ($data['status_verifikasi'] === 'Terverifikasi') ? 'selected' : '' ?>>Terverifikasi</option>
                    <option value="Ditolak" <?= ($data['status_verifikasi'] === 'Ditolak') ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>

            <!-- Catatan Verifikasi -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Catatan Verifikasi</label>
                <textarea name="catatan_verifikasi" rows="2" placeholder="Catatan tambahan hasil verifikasi..." class="w-full px-4 py-3 rounded-xl border border-[#e0d6c8] focus:ring-2 focus:ring-paw-hitam focus:border-paw-hitam outline-none transition"><?= htmlspecialchars($data['catatan_verifikasi'] ?? '') ?></textarea>
            </div>

            <!-- Tombol Simpan -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-paw-hitam text-paw-putih py-4 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg">
                    <i class="fa-solid fa-check mr-2"></i> Update Data Pengadopsi
                </button>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>
