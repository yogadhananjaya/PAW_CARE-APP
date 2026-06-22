<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <div>
            <h2>Transaksi Adopsi</h2>
            <p>Data kontrak persetujuan adopsi hewan peliharaan di shelter PawCare.</p>
        </div>
        <a href="index.php?page=transaksi_adopsi_create" class="btn btn-primary">+ Buat Transaksi Adopsi</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th>Pengadopsi</th>
                    <th>Hewan</th>
                    <th>Tanggal Adopsi</th>
                    <th>Status Kontrak</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_adopsi']) ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_pengadopsi']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_hewan']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_adopsi']) ?></td>
                    <td>
                        <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; 
                            background-color: <?= $row['status_kontrak'] == 'Aktif' ? '#e2fbe8; color:#2ecc71;' : ($row['status_kontrak'] == 'Ditandatangani' ? '#e1f5fe; color:#3498db;' : '#fff3cd; color:#f1c40f;') ?>">
                            <?= htmlspecialchars($row['status_kontrak']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=transaksi_adopsi_edit&id=<?= $row['id_adopsi'] ?>" class="btn btn-sm btn-warning" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </a>
                        <a href="index.php?page=transaksi_adopsi_delete&id=<?= $row['id_adopsi'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus transaksi adopsi ini?');" title="Hapus">
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