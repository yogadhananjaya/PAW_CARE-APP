<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>🩺 Transaksi: Rekam Medis Hewan</h2>
        <a href="index.php?page=riwayat_kesehatan_create" class="btn btn-primary">+ Tambah Rekam Medis</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Tanggal Periksa</th>
                    <th>Hewan</th>
                    <th>Vaksin (Opsional)</th>
                    <th>Diagnosa</th>
                    <th>Perawat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['tanggal_periksa']) ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_hewan']) ?></strong></td>
                    <td><?= $row['nama_vaksin'] ? htmlspecialchars($row['nama_vaksin']) : '-' ?></td>
                    <td><?= htmlspecialchars($row['diagnosa']) ?></td>
                    <td><?= htmlspecialchars($row['perawat']) ?></td>
                    <td>
                        <a href="index.php?page=riwayat_kesehatan_edit&id=<?= $row['id_riwayat'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=riwayat_kesehatan_delete&id=<?= $row['id_riwayat'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus rekam medis?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>