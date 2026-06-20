<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>🐾 Master Data: Ras Hewan</h2>
        <a href="index.php?page=ras_create" class="btn btn-primary">+ Tambah Ras</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Jenis Hewan</th>
                    <th>Nama Ras</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_jenis']) ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_ras']) ?></strong></td>
                    <td>
                        <a href="index.php?page=ras_edit&id=<?= $row['id_ras'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=ras_delete&id=<?= $row['id_ras'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>