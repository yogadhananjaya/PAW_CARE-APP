<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>🤝 Transaksi: Persetujuan Adopsi (E-Contract)</h2>
        <a href="index.php?page=transaksi_adopsi_create" class="btn btn-primary">+ Buat Transaksi Adopsi</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Pengadopsi</th>
                    <th>Hewan</th>
                    <th>Tanggal Adopsi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_hewan']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_adopsi']) ?></td>
                    <td>
                        <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; background-color: <?= $row['status_adopsi'] == 'Disetujui' ? '#e2fbe8; color:#2ecc71;' : ($row['status_adopsi'] == 'Ditolak' ? '#fce4e4; color:#e74c3c;' : '#e1f5fe; color:#3498db;') ?>">
                            <?= $row['status_adopsi'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=transaksi_adopsi_edit&id=<?= $row['id_transaksi'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=transaksi_adopsi_delete&id=<?= $row['id_transaksi'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus transaksi?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>