<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <div>
            <h2>Ras Hewan</h2>
            <p>Master data daftar ras hewan peliharaan di shelter.</p>
        </div>
        <a href="index.php?page=ras_create" class="btn btn-primary">+ Tambah Ras</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th>Jenis Hewan</th>
                    <th>Nama Ras</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_ras']) ?></td>
                    <td><?= htmlspecialchars($row['nama_jenis']) ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_ras']) ?></strong></td>
                    <td>
                        <a href="index.php?page=ras_edit&id=<?= $row['id_ras'] ?>" class="btn btn-sm btn-warning" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </a>
                        <a href="index.php?page=ras_delete&id=<?= $row['id_ras'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?');" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>