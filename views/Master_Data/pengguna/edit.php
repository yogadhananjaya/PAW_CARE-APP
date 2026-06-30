<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Akun Pengguna</h2>
        <a href="index.php?page=pengguna" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 700px;">
        <?php if (!empty($error_duplikat)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_duplikat) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=pengguna_edit&id=<?= $data['id_pengguna'] ?>" method="POST">
            <!-- Nama Lengkap -->
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? $data['nama_lengkap']) ?>" class="form-control" required>
            </div>

            <!-- Nama Pengguna -->
            <div class="form-group">
                <label>Nama Pengguna (Username)</label>
                <input type="text" name="nama_pengguna" value="<?= htmlspecialchars($_POST['nama_pengguna'] ?? $data['nama_pengguna']) ?>" class="form-control" required>
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
                    <?php if ($data['nama_pengguna'] === 'pawcare'): ?>
                        <select name="jabatan" class="form-control" readonly style="pointer-events: none; background: #e9ecef;">
                            <option value="SuperAdmin" selected>SuperAdmin</option>
                        </select>
                    <?php else: ?>
                        <select name="jabatan" class="form-control" required>
                            <?php $curr_jabatan = $_POST['jabatan'] ?? $data['jabatan']; ?>
                            <option value="Koordinator" <?= $curr_jabatan == 'Koordinator' ? 'selected' : '' ?>>Koordinator</option>
                            <option value="Perawat Hewan" <?= $curr_jabatan == 'Perawat Hewan' ? 'selected' : '' ?>>Perawat Hewan</option>
                        </select>
                    <?php endif; ?>
                </div>

                <!-- Role -->
                <div class="form-group" style="flex:1;">
                    <label>Role Akses</label>
                    <?php if ($data['nama_pengguna'] === 'pawcare'): ?>
                        <select name="role" class="form-control" readonly style="pointer-events: none; background: #e9ecef;">
                            <option value="SuperAdmin" selected>SuperAdmin</option>
                        </select>
                    <?php else: ?>
                        <select name="role" class="form-control" required>
                            <?php $curr_role = $_POST['role'] ?? $data['role']; ?>
                            <option value="Pengguna" <?= $curr_role == 'Pengguna' ? 'selected' : '' ?>>Pengguna</option>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kontak -->
            <div class="form-group">
                <label>Nomor Kontak / HP</label>
                <input type="text" name="kontak" value="<?= htmlspecialchars($_POST['kontak'] ?? $data['kontak']) ?>" class="form-control" required>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status Akun</label>
                <select name="status" class="form-control">
                    <?php $curr_status = $_POST['status'] ?? $data['status']; ?>
                    <option value="Aktif" <?= $curr_status == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Suspended" <?= $curr_status == 'Suspended' ? 'selected' : '' ?>>Suspended</option>
                    <option value="Resign" <?= $curr_status == 'Resign' ? 'selected' : '' ?>>Resign</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>