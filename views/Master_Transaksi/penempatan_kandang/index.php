<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>🏢 Transaksi: Penempatan Kandang</h2>
        <a href="index.php?page=penempatan_kandang_create" class="btn btn-primary">+ Alokasikan Kandang</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Hewan</th>
                    <th>Nama Kandang</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama_hewan']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_kandang']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                    <td><?= $row['tanggal_keluar'] ? htmlspecialchars($row['tanggal_keluar']) : '<i style="color:green;">Masih Menempati</i>' ?></td>
                    <td>
                        <a href="index.php?page=penempatan_kandang_edit&id=<?= $row['id_penempatan'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=penempatan_kandang_delete&id=<?= $row['id_penempatan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>