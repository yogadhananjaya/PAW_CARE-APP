<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <div>
            <h2>Data Peliharaan</h2>
            <p>Manajemen dan registrasi hewan peliharaan di shelter.</p>
        </div>
        <a href="index.php?page=hewan_create" class="btn btn-primary">+ Registrasi Hewan Baru</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th>Foto</th>
                    <th>Nama Hewan</th>
                    <th>Jenis & Ras</th>
                    <th>Gender & Umur</th>
                    <th>Sumber Intake</th>
                    <th>Status Adopsi</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_hewan']) ?></td>
                    <td>
                        <?php if(!empty($row['url_foto_hewan'])): ?>
                            <img src="assets/img/hewan/<?= htmlspecialchars($row['url_foto_hewan']) ?>" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                        <?php else: ?>
                            <div style="width:60px; height:60px; background:#f0ece3; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:11px; color:#aaa; font-weight:600;">No Foto</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($row['nama_hewan']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_jenis']) ?> (<?= htmlspecialchars($row['nama_ras']) ?>)</td>
                    <td><?= htmlspecialchars($row['jenis_kelamin']) ?>, <?= htmlspecialchars($row['estimasi_umur']) ?> bln</td>
                    <td><?= htmlspecialchars($row['sumber_intake']) ?></td>
                    <td>
                        <span style="padding:4px 10px; border-radius:12px; font-size:12px; font-weight:bold; 
                            background-color: <?= 
                                $row['status_adopsi'] == 'Tersedia' ? '#e2fbe8; color:#2ecc71;' : 
                                ($row['status_adopsi'] == 'Karantina' ? '#fff3cd; color:#ffc107;' : 
                                ($row['status_adopsi'] == 'Dalam Proses' ? '#e1f5fe; color:#3498db;' :
                                ($row['status_adopsi'] == 'Diadopsi' ? '#e8eaf6; color:#5c6bc0;' : '#fce4e4; color:#e74c3c;'))) ?>">
                            <?= $row['status_adopsi'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=hewan_edit&id=<?= $row['id_hewan'] ?>" class="btn btn-sm btn-warning" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </a>
                        <a href="index.php?page=hewan_delete&id=<?= $row['id_hewan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus hewan beserta fotonya?');" title="Hapus">
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