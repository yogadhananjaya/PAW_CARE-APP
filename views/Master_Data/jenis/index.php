<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>🐾 Master Data: Jenis Hewan</h2>
        <a href="index.php?page=jenis_create" class="btn btn-primary">+ Tambah Jenis</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th width="10%">ID</th>
                    <th>Nama Jenis Hewan</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_jenis']) ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_jenis']) ?></strong></td>
                    <td>
                        <a href="index.php?page=jenis_edit&id=<?= $row['id_jenis'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=jenis_delete&id=<?= $row['id_jenis'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>