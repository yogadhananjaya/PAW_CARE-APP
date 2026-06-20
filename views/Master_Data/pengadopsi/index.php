<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>👥 Master Data: Pengadopsi (Adopter)</h2>
        <a href="index.php?page=pengadopsi_create" class="btn btn-primary">+ Tambah Pengadopsi</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>NIK KTP</th>
                    <th>No HP</th>
                    <th>Akun User</th>
                    <th>Status Verifikasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nik']) ?></td>
                    <td><?= htmlspecialchars($row['no_hp']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td>
                        <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; 
                            background-color: <?= $row['status_verifikasi'] == 'Terverifikasi' ? '#e2fbe8; color:#2ecc71;' : ($row['status_verifikasi'] == 'Ditolak' ? '#fce4e4; color:#e74c3c;' : '#fff3cd; color:#f1c40f;') ?>">
                            <?= $row['status_verifikasi'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=pengadopsi_edit&id=<?= $row['id_pengadopsi'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=pengadopsi_delete&id=<?= $row['id_pengadopsi'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>