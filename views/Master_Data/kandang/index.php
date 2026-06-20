<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>🏢 Master Data: Kandang</h2>
        <a href="index.php?page=kandang_create" class="btn btn-primary">+ Tambah Kandang</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Nama Kandang / Blok</th>
                    <th>Kapasitas (Ekor)</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama_kandang']) ?></strong></td>
                    <td><?= htmlspecialchars($row['kapasitas']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <a href="index.php?page=kandang_edit&id=<?= $row['id_kandang'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=kandang_delete&id=<?= $row['id_kandang'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>