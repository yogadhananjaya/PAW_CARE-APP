<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>🐾 Manajemen Data Peliharaan Shelter</h2>
        <a href="index.php?page=hewan_create" class="btn btn-primary">+ Registrasi Hewan Baru</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Hewan</th>
                    <th>Jenis & Ras</th>
                    <th>Gender & Umur</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td>
                        <?php if(!empty($row['foto'])): ?>
                            <img src="assets/img/hewan/<?= htmlspecialchars($row['foto']) ?>" style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                        <?php else: ?>
                            <div style="width:70px; height:70px; background:#eee; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:11px; color:#aaa;">No Foto</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($row['nama_hewan']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_jenis']) ?> (<?= htmlspecialchars($row['nama_ras']) ?>)</td>
                    <td><?= htmlspecialchars($row['jenis_kelamin']) ?>, <?= htmlspecialchars($row['umur']) ?></td>
                    <td>
                        <span style="padding:4px 10px; border-radius:12px; font-size:12px; font-weight:bold; 
                            background-color: <?= $row['status'] == 'Tersedia' ? '#e2fbe8; color:#2ecc71;' : ($row['status'] == 'Karantina' ? '#fff3cd; color:#ffc107;' : '#fce4e4; color:#e74c3c;') ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=hewan_edit&id=<?= $row['id_hewan'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?page=hewan_delete&id=<?= $row['id_hewan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus hewan beserta fotonya?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>