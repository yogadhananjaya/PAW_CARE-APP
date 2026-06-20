<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>🔒 Master Data: Pengguna / Staff</h2>
        <a href="index.php?page=pengguna_create" class="btn btn-primary">+ Tambah Pengguna</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role Akses</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['username']) ?></strong></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                        <a href="index.php?page=pengguna_edit&id=<?= $row['id_pengguna'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=pengguna_delete&id=<?= $row['id_pengguna'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus akun ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>