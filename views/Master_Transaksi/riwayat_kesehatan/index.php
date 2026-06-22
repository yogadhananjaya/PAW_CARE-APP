<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <div>
            <h2>Rekam Medis Hewan</h2>
            <p>Riwayat perawatan dan vaksinasi peliharaan di shelter PawCare.</p>
        </div>
        <a href="index.php?page=riwayat_kesehatan_create" class="btn btn-primary">+ Tambah Rekam Medis</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th>Tanggal</th>
                    <th>Hewan</th>
                    <th>Tipe</th>
                    <th>Vaksin (Opsional)</th>
                    <th>Deskripsi</th>
                    <th>Perawat</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_riwayat']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_hewan']) ?></strong></td>
                    <td>
                        <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; 
                            background-color: <?= $row['tipe'] == 'Vaksinasi' ? '#e1f5fe; color:#3498db;' : '#e0f2f1; color:#00796b;' ?>">
                            <?= htmlspecialchars($row['tipe']) ?>
                        </span>
                    </td>
                    <td><?= $row['nama_vaksin'] ? htmlspecialchars($row['nama_vaksin']) : '-' ?></td>
                    <td><?= htmlspecialchars(substr($row['deskripsi'], 0, 50)) ?>...</td>
                    <td><?= htmlspecialchars($row['perawat']) ?></td>
                    <td>
                        <a href="index.php?page=riwayat_kesehatan_edit&id=<?= $row['id_riwayat'] ?>" class="btn btn-sm btn-warning" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </a>
                        <a href="index.php?page=riwayat_kesehatan_delete&id=<?= $row['id_riwayat'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus rekam medis ini?');" title="Hapus">
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