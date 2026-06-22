<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Akun Pengguna</h2>
        <a href="index.php?page=pengguna" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 700px;">
        <form action="index.php?page=pengguna_edit&id=<?= $data['id_pengguna'] ?>" method="POST">
            <!-- Nama Lengkap -->
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" class="form-control" required>
            </div>

            <!-- Nama Pengguna -->
            <div class="form-group">
                <label>Nama Pengguna (Username)</label>
                <input type="text" name="nama_pengguna" value="<?= htmlspecialchars($data['nama_pengguna']) ?>" class="form-control" required>
            </div>

            <!-- Password (kosongkan jika tidak diubah) -->
            <div class="form-group">
                <label>Kata Sandi Baru <small style="color:#999;">(Kosongkan jika tidak diubah)</small></label>
                <input type="password" name="kata_sandi" class="form-control" autocomplete="new-password">
            </div>

            <div style="display:flex; gap:20px;">
                <!-- Jabatan -->
                <div class="form-group" style="flex:1;">
                    <label>Jabatan</label>
                    <select name="jabatan" class="form-control" required>
                        <option value="Koordinator" <?= $data['jabatan'] == 'Koordinator' ? 'selected' : '' ?>>Koordinator</option>
                        <option value="Perawat Hewan" <?= $data['jabatan'] == 'Perawat Hewan' ? 'selected' : '' ?>>Perawat Hewan</option>
                        <option value="SuperAdmin" <?= $data['jabatan'] == 'SuperAdmin' ? 'selected' : '' ?>>SuperAdmin</option>
                    </select>
                </div>

                <!-- Role -->
                <div class="form-group" style="flex:1;">
                    <label>Role Akses</label>
                    <select name="role" class="form-control">
                        <option value="Pegawai" <?= $data['role'] == 'Pegawai' ? 'selected' : '' ?>>Pegawai</option>
                        <option value="SuperAdmin" <?= $data['role'] == 'SuperAdmin' ? 'selected' : '' ?>>SuperAdmin</option>
                    </select>
                </div>
            </div>

            <!-- Kontak -->
            <div class="form-group">
                <label>Nomor Kontak / HP</label>
                <input type="text" name="kontak" value="<?= htmlspecialchars($data['kontak']) ?>" class="form-control" required>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status Akun</label>
                <select name="status" class="form-control">
                    <option value="Aktif" <?= $data['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Suspended" <?= $data['status'] == 'Suspended' ? 'selected' : '' ?>>Suspended</option>
                    <option value="Resign" <?= $data['status'] == 'Resign' ? 'selected' : '' ?>>Resign</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>