<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Data Pengadopsi</h2>
        <a href="index.php?page=pengadopsi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 700px;">
        <form action="index.php?page=pengadopsi_create" method="POST">
            <div class="form-group">
                <label>Pilih Akun Pengguna (Role: User)</label>
                <select name="id_pengguna" class="form-control" required>
                    <?php foreach($users as $u): ?>
                        <option value="<?= $u['id_pengguna'] ?>"><?= $u['username'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nama Lengkap Sesuai KTP</label>
                <input type="text" name="nama_lengkap" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nomor Induk Kependudukan (NIK)</label>
                <input type="text" name="nik" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Status Verifikasi</label>
                <select name="status_verifikasi" class="form-control">
                    <option value="Belum">Belum (Menunggu Pengecekan)</option>
                    <option value="Terverifikasi">Terverifikasi</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>