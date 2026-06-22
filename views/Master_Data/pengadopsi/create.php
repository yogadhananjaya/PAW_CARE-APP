<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Data Pengadopsi</h2>
        <a href="index.php?page=pengadopsi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 700px;">
        <form action="index.php?page=pengadopsi_create" method="POST">
            <!-- Nama -->
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required placeholder="Contoh: Andi Wijaya">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>Email (digunakan untuk login)</label>
                <input type="email" name="email" class="form-control" required placeholder="Contoh: andi@email.com">
            </div>

            <!-- Kata Sandi -->
            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="kata_sandi" class="form-control" required>
            </div>

            <!-- No HP -->
            <div class="form-group">
                <label>Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" class="form-control" required placeholder="Contoh: 08123456789">
            </div>

            <!-- Alamat -->
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="3" required></textarea>
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