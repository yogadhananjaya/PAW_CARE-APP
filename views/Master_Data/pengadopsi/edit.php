<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Data Pengadopsi</h2>
        <a href="index.php?page=pengadopsi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 700px;">
        <?php if (!empty($error)): ?>
            <div style="background-color: #fce4e4; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                ⚠️ <strong>Gagal menyimpan:</strong> <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=pengadopsi_edit&id=<?= $data['id_pengadopsi'] ?>" method="POST" enctype="multipart/form-data">
            <!-- Nama Lengkap -->
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : htmlspecialchars($data['nama_lengkap']) ?>" class="form-control" required>
            </div>

            <!-- Username -->
            <div class="form-group">
                <label>Nama Pengguna (Username)</label>
                <input type="text" name="nama_pengguna" value="<?= isset($_POST['nama_pengguna']) ? htmlspecialchars($_POST['nama_pengguna']) : htmlspecialchars($data['nama_pengguna']) ?>" class="form-control" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($data['email']) ?>" class="form-control" required>
            </div>

            <!-- Password (kosongkan jika tidak diubah) -->
            <div class="form-group">
                <label>Kata Sandi Baru <small style="color:#999;">(Kosongkan jika tidak diubah)</small></label>
                <input type="password" name="kata_sandi" class="form-control" autocomplete="new-password">
            </div>

            <!-- No HP -->
            <div class="form-group">
                <label>Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" value="<?= isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : htmlspecialchars($data['no_hp']) ?>" class="form-control" required>
            </div>

            <!-- Alamat -->
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="3" required><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : htmlspecialchars($data['alamat']) ?></textarea>
            </div>

            <!-- Foto KTP -->
            <div class="form-group">
                <label>Foto KTP Saat Ini</label>
                <?php if (!empty($data['url_ktp']) && file_exists(__DIR__ . '/../../../assets/img/ktp/' . $data['url_ktp'])): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="assets/img/ktp/<?= htmlspecialchars($data['url_ktp']) ?>" style="max-width: 200px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                <?php else: ?>
                    <p style="color:#64748b; font-style:italic; font-size:13px; margin-bottom:10px;">Belum ada foto KTP</p>
                <?php endif; ?>
                <label>Upload Foto KTP Baru <small style="color:#999;">(Kosongkan jika tidak ingin diubah)</small></label>
                <input type="file" name="url_ktp" class="form-control" accept="image/*">
            </div>

            <!-- Status Verifikasi -->
            <div class="form-group">
                <label>Status Verifikasi KTP</label>
                <select name="status_verifikasi" class="form-control">
                    <option value="Belum" <?= $data['status_verifikasi'] == 'Belum' ? 'selected' : '' ?>>Belum</option>
                    <option value="Menunggu" <?= $data['status_verifikasi'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                    <option value="Terverifikasi" <?= $data['status_verifikasi'] == 'Terverifikasi' ? 'selected' : '' ?>>Terverifikasi</option>
                    <option value="Ditolak" <?= $data['status_verifikasi'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>

            <!-- Tanggal Verifikasi -->
            <div class="form-group">
                <label>Tanggal Verifikasi <small style="color:#999;">(opsional)</small></label>
                <input type="date" name="tanggal_verifikasi" value="<?= htmlspecialchars($data['tanggal_verifikasi'] ?? '') ?>" class="form-control">
            </div>

            <!-- Catatan Verifikasi -->
            <div class="form-group">
                <label>Catatan Verifikasi <small style="color:#999;">(opsional)</small></label>
                <textarea name="catatan_verifikasi" class="form-control" rows="2"><?= htmlspecialchars($data['catatan_verifikasi'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>