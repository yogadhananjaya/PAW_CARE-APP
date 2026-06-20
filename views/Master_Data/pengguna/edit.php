<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Akun Pengguna</h2>
        <a href="index.php?page=pengguna" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=pengguna_edit&id=<?= $data['id_pengguna'] ?>" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="text" name="password" value="<?= htmlspecialchars($data['password']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="Pegawai" <?= $data['role'] == 'Pegawai' ? 'selected' : '' ?>>Pegawai</option>
                    <option value="SuperAdmin" <?= $data['role'] == 'SuperAdmin' ? 'selected' : '' ?>>SuperAdmin</option>
                    <option value="User" <?= $data['role'] == 'User' ? 'selected' : '' ?>>User (Adopter Publik)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>