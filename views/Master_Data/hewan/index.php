<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <div>
            <h2>Data Peliharaan</h2>
            <p>Manajemen dan registrasi hewan peliharaan di shelter.</p>
        </div>
        <?php if (in_array($_SESSION['role'] ?? '', ['SuperAdmin', 'Koordinator'])): ?>
            <a href="index.php?page=hewan_create" class="btn btn-primary">+ Registrasi Hewan Baru</a>
        <?php endif; ?>
    </header>
    <div class="card">
        <?php if (isset($_GET['error']) && $_GET['error'] == 'belum_vaksinasi'): ?>
            <div style="background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; border-radius:10px; padding:12px 18px; margin-bottom:15px; font-weight:600; font-size:14px;">
                ⚠️ Hewan harus memiliki minimal 1 rekam Vaksinasi sebelum dapat direkomendasikan.
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
            <div style="background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; border-radius:10px; padding:12px 18px; margin-bottom:15px; font-weight:600; font-size:14px;">
                ⚠️ Gagal menghapus: Hewan ini sedang memiliki transaksi adopsi atau jadwal kunjungan yang aktif!
            </div>
        <?php endif; ?>
        <table class="crud-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Kode</th>
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
                <?php $no = 1; foreach($data as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['kode_hewan'] ?? '') ?></td>
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
                                $row['status_adopsi'] == 'Tersedia' ? '#e2fbe8; color:#2ecc71;' : '#fff3cd; color:#b45309;' ?>">
                            <?= $row['status_adopsi'] ?>
                        </span>
                        <?php if ($row['status_adopsi'] == 'Karantina' && ($row['rekomendasi_adopsi'] ?? 0) == 1): ?>
                            <br><small style="color:#2563eb; font-weight:700; font-size:11px; display:inline-block; margin-top:4px;">👍 Rekomendasi Perawat</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status_adopsi'] == 'Karantina'): ?>
                            <?php if (in_array($_SESSION['role'] ?? '', ['Perawat', 'Perawat Hewan']) && ($row['rekomendasi_adopsi'] ?? 0) == 0): ?>
                                <a href="index.php?page=hewan_recommend&id=<?= $row['id_hewan'] ?>" class="btn btn-sm btn-primary" style="background:#3b82f6; border:none; color:white; width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; margin-right:4px; vertical-align:middle;" title="Rekomendasikan Siap Adopsi">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (in_array($_SESSION['role'] ?? '', ['SuperAdmin', 'Koordinator']) && ($row['rekomendasi_adopsi'] ?? 0) == 1): ?>
                                <a href="index.php?page=hewan_confirm&id=<?= $row['id_hewan'] ?>" class="btn btn-sm btn-success" style="background:#10b981; border:none; color:white; width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; margin-right:4px; vertical-align:middle;" title="Setujui & Rilis ke Katalog">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (in_array($_SESSION['role'] ?? '', ['SuperAdmin', 'Koordinator'])): ?>
                            <a href="index.php?page=hewan_edit&id=<?= $row['id_hewan'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                            </a>
                            <a href="index.php?page=hewan_delete&id=<?= $row['id_hewan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus hewan beserta fotonya?');" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>