 <?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <div>
            <h2>Data Pengguna</h2>
            <p>Master data akun internal sistem PawCare (Koordinator & Perawat).</p>
        </div>
        <a href="index.php?page=pengguna_create" class="btn btn-primary">+ Tambah Pengguna</a>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Kode</th>
                    <th>Nama Lengkap</th>
                    <th>Nama Pengguna</th>
                    <th>Jabatan</th>
                    <th>Kontak</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($data as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['kode_pengguna'] ?? '') ?></td>
                    <td><strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                    <td><?= htmlspecialchars($row['jabatan']) ?></td>
                    <td><?= htmlspecialchars($row['kontak']) ?></td>
                    <td>
                        <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; 
                            background-color: <?= $row['role'] == 'SuperAdmin' ? '#e1f5fe; color:#3498db;' : '#e0f2f1; color:#00796b;' ?>">
                            <?= $row['role'] == 'Pengguna' ? 'Pengguna' : htmlspecialchars($row['role']) ?>
                        </span>
                    </td>
                    <td>
                        <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; 
                            background-color: <?= $row['status'] == 'Aktif' ? '#e2fbe8; color:#2ecc71;' : ($row['status'] == 'Suspended' ? '#fff3cd; color:#f1c40f;' : '#fce4e4; color:#e74c3c;') ?>">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?page=pengguna_edit&id=<?= $row['id_pengguna'] ?>" class="btn btn-sm btn-warning" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </a>
                        <?php if ($row['nama_pengguna'] !== 'pawcare'): ?>
                        <a href="index.php?page=pengguna_delete&id=<?= $row['id_pengguna'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus akun ini?');" title="Hapus">
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