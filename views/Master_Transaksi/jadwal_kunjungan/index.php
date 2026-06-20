<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>📅 Transaksi: Jadwal Kunjungan</h2>
        <a href="index.php?page=jadwal_kunjungan_create" class="btn btn-primary">+ Tambah Jadwal</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Pengadopsi</th>
                    <th>Hewan Tujuan</th>
                    <th>Jadwal Kunjungan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_hewan']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_kunjungan']) ?></td>
                    <td>
                        <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; background-color: <?= $row['status'] == 'Selesai' ? '#e2fbe8; color:#2ecc71;' : ($row['status'] == 'Dikonfirmasi' ? '#e1f5fe; color:#3498db;' : '#fff3cd; color:#f1c40f;') ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=jadwal_kunjungan_edit&id=<?= $row['id_jadwal'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=jadwal_kunjungan_delete&id=<?= $row['id_jadwal'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus jadwal?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>