<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>💰 Master Data: Rekap Donasi</h2>
        <a href="index.php?page=donasi_create" class="btn btn-primary">+ Catat Donasi Manual</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Nama Donatur</th>
                    <th>Jumlah (Rp)</th>
                    <th>Tanggal Masuk</th>
                    <th>Status Pencairan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama_donatur']) ?></strong></td>
                    <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td>
                        <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; 
                            background-color: <?= $row['status'] == 'Dikonfirmasi' ? '#e2fbe8; color:#2ecc71;' : ($row['status'] == 'Ditolak' ? '#fce4e4; color:#e74c3c;' : '#e1f5fe; color:#3498db;') ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=donasi_edit&id=<?= $row['id_donasi'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=donasi_delete&id=<?= $row['id_donasi'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus donasi?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>