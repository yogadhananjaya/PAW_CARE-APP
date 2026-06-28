<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Akun Pengguna</h2>
        <a href="index.php?page=pengguna" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 700px;">
        <?php if (!empty($error_duplikat)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_duplikat) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=pengguna_create" method="POST">
            <!-- Nama Lengkap -->
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>" required placeholder="Contoh: Budi Santoso">
            </div>

            <!-- Nama Pengguna (username) -->
            <div class="form-group">
                <label>Nama Pengguna (Username)</label>
                <input type="text" name="nama_pengguna" class="form-control" value="<?= htmlspecialchars($_POST['nama_pengguna'] ?? '') ?>" required autocomplete="off" placeholder="Contoh: budi.santoso">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="kata_sandi" class="form-control" required>
            </div>

            <div style="display:flex; gap:20px;">
                <!-- Jabatan -->
                <div class="form-group" style="flex:1;">
                    <label>Jabatan</label>
                    <select name="jabatan" class="form-control" required>
                        <?php $post_jabatan = $_POST['jabatan'] ?? ''; ?>
                        <option value="Koordinator" <?= $post_jabatan == 'Koordinator' ? 'selected' : '' ?>>Koordinator</option>
                        <option value="Perawat Hewan" <?= $post_jabatan == 'Perawat Hewan' ? 'selected' : '' ?>>Perawat Hewan</option>
                        <option value="SuperAdmin" <?= $post_jabatan == 'SuperAdmin' ? 'selected' : '' ?>>SuperAdmin</option>
                    </select>
                </div>

                <!-- Role -->
                <div class="form-group" style="flex:1;">
                    <label>Role Akses</label>
                    <select name="role" class="form-control">
                        <?php $post_role = $_POST['role'] ?? ''; ?>
                        <option value="Pegawai" <?= $post_role == 'Pegawai' ? 'selected' : '' ?>>Pengguna</option>
                        <option value="SuperAdmin" <?= $post_role == 'SuperAdmin' ? 'selected' : '' ?>>SuperAdmin</option>
                    </select>
                </div>
            </div>

            <!-- Kontak -->
            <div class="form-group">
                <label>Nomor Kontak / HP</label>
                <input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($_POST['kontak'] ?? '') ?>" required placeholder="Contoh: 08123456789">
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status Akun</label>
                <select name="status" class="form-control">
                    <?php $post_status = $_POST['status'] ?? ''; ?>
                    <option value="Aktif" <?= $post_status == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Suspended" <?= $post_status == 'Suspended' ? 'selected' : '' ?>>Suspended</option>
                    <option value="Resign" <?= $post_status == 'Resign' ? 'selected' : '' ?>>Resign</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>