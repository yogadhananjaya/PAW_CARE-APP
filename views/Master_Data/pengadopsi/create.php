<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Data Pengadopsi</h2>
        <a href="index.php?page=pengadopsi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 700px;">
        <?php if (!empty($error)): ?>
            <div style="background-color: #fce4e4; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                ⚠️ <strong>Gagal menyimpan:</strong> <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=pengadopsi_create" method="POST" enctype="multipart/form-data">
            <!-- Nama -->
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>" class="form-control" required placeholder="Contoh: Andi Wijaya">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>Email (digunakan untuk login)</label>
                <input type="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" class="form-control" required placeholder="Contoh: andi@email.com">
            </div>

            <!-- Kata Sandi -->
            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="kata_sandi" class="form-control" required>
            </div>

            <!-- No HP -->
            <div class="form-group">
                <label>Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" value="<?= isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : '' ?>" class="form-control" required placeholder="Contoh: 08123456789">
            </div>

            <!-- Alamat -->
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="3" required><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
            </div>

            <!-- Foto KTP -->
            <div class="form-group">
                <label>Upload Foto KTP</label>
                <input type="file" name="url_ktp" class="form-control" accept="image/*" required>
            </div>

            <!-- Status Verifikasi -->
            <div class="form-group">
                <label>Status Verifikasi KTP</label>
                <select name="status_verifikasi" class="form-control">
                    <option value="Belum">Belum (Default)</option>
                    <option value="Menunggu">Menunggu Verifikasi</option>
                    <option value="Terverifikasi">Terverifikasi</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>