<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>💉 Master Data: Vaksin</h2>
        <a href="index.php?page=vaksin_create" class="btn btn-primary">+ Tambah Vaksin</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Nama Vaksin</th>
                    <th>Deskripsi Peruntukan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama_vaksin']) ?></strong></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td>
                        <a href="index.php?page=vaksin_edit&id=<?= $row['id_vaksin'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=vaksin_delete&id=<?= $row['id_vaksin'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>