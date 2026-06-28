<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<?php
require_once __DIR__ . '/../../../app/models/RiwayatKesehatanModel.php';
$model = new RiwayatKesehatanModel();
$current_user_id = $_SESSION['user_id'] ?? 0;
$current_role    = $_SESSION['role'] ?? '';

$total_rekam = count($data);
$total_perawatan = 0;
$total_vaksinasi = 0;
foreach ($data as $row) {
    if (($row['tipe'] ?? '') == 'Perawatan') {
        $total_perawatan++;
    } elseif (($row['tipe'] ?? '') == 'Vaksinasi') {
        $total_vaksinasi++;
    }
}
?>

<div class="main-wrapper">
    <header class="admin-header">
        <div>
            <h2>Rekam Medis Hewan</h2>
            <p>Riwayat perawatan dan vaksinasi peliharaan di shelter PawCare.</p>
        </div>
        <?php if ($current_role !== 'Koordinator'): ?>
        <a href="index.php?page=riwayat_kesehatan_create" class="btn btn-primary">+ Tambah Rekam Medis</a>
        <?php endif; ?>
    </header>

    <?php if (isset($_GET['alert']) && $_GET['alert'] === 'locked'): ?>
    <div style="background:#fff3cd; border:1px solid #ffc107; color:#856404; padding:12px 18px; border-radius:10px; margin-bottom:20px; font-size:14px; font-weight:600;">
        🔒 Catatan tidak dapat diubah karena sudah melewati batas 24 jam atau bukan milik Anda.
    </div>
    <?php endif; ?>

    <!-- Stats Widget Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">
        <div style="background: #ffffff; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="background: #eff6ff; color: #3b82f6; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold;">📊</div>
            <div>
                <span style="font-size: 11px; color: #94a3b8; font-weight: 700; display: block; text-transform: uppercase; letter-spacing: 0.5px;">Total Rekam Medis</span>
                <span style="font-size: 22px; color: #0f172a; font-weight: 800; line-height: 1.2;"><?= $total_rekam ?></span>
            </div>
        </div>
        <div style="background: #ffffff; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="background: #e6fffa; color: #00a389; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold;">🩺</div>
            <div>
                <span style="font-size: 11px; color: #94a3b8; font-weight: 700; display: block; text-transform: uppercase; letter-spacing: 0.5px;">Total Perawatan</span>
                <span style="font-size: 22px; color: #0f172a; font-weight: 800; line-height: 1.2;"><?= $total_perawatan ?></span>
            </div>
        </div>
        <div style="background: #ffffff; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="background: #fffbeb; color: #d97706; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold;">💉</div>
            <div>
                <span style="font-size: 11px; color: #94a3b8; font-weight: 700; display: block; text-transform: uppercase; letter-spacing: 0.5px;">Total Vaksinasi</span>
                <span style="font-size: 22px; color: #0f172a; font-weight: 800; line-height: 1.2;"><?= $total_vaksinasi ?></span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div style="background:#ffffff; border-radius:12px; padding:15px; border:1px solid #e2e8f0; margin-bottom:20px; display:flex; gap:15px; flex-wrap:wrap; align-items:center; box-shadow: 0 2px 4px rgba(0,0,0,0.01);">
        <div style="flex:1; min-width:250px; position:relative;">
            <input type="text" id="searchInput" placeholder="Cari nama hewan atau kode rekam medis..." style="width:100%; padding:10px 15px; border-radius:8px; border:1px solid #cbd5e1; outline:none; font-size:14px;" onkeyup="filterTable()">
        </div>
        <div style="width:200px;">
            <select id="typeFilter" style="width:100%; padding:10px 15px; border-radius:8px; border:1px solid #cbd5e1; outline:none; font-size:14px; background:#fff; cursor:pointer;" onchange="filterTable()">
                <option value="ALL">🔍 Semua Tipe</option>
                <option value="Perawatan">🩺 Perawatan</option>
                <option value="Vaksinasi">💉 Vaksinasi</option>
            </select>
        </div>
    </div>

    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Hewan</th>
                    <th>Tipe</th>
                    <th>Vaksin (Opsional)</th>
                    <th>Deskripsi</th>
                    <th>Perawat</th>
                    <?php if ($current_role !== 'Koordinator'): ?>
                    <th width="12%">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($data as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td class="col-kode" style="font-weight: 600; color: #475569;"><?= htmlspecialchars($row['kode_riwayat_kesehatan'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td class="col-hewan"><strong><?= htmlspecialchars($row['nama_hewan']) ?></strong></td>
                    <td class="col-tipe">
                        <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; 
                            background-color: <?= $row['tipe'] == 'Vaksinasi' ? '#e1f5fe; color:#3498db;' : '#e0f2f1; color:#00796b;' ?>">
                            <?= htmlspecialchars($row['tipe']) ?>
                        </span>
                    </td>
                    <td><?= $row['nama_vaksin'] ? htmlspecialchars($row['nama_vaksin']) : '-' ?></td>
                    <td><?= htmlspecialchars(substr($row['deskripsi'], 0, 50)) ?>...</td>
                    <td><?= htmlspecialchars($row['perawat']) ?></td>
                    <?php if ($current_role !== 'Koordinator'): ?>
                    <td>
                        <?php $can_modify = $model->canModify($row, $current_user_id, $current_role); ?>
                        <?php if ($can_modify): ?>
                        <a href="index.php?page=riwayat_kesehatan_edit&id=<?= $row['id_riwayat'] ?>" class="btn btn-sm btn-warning" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </a>
                        <a href="index.php?page=riwayat_kesehatan_delete&id=<?= $row['id_riwayat'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Batalkan rekam medis ini? Data tidak akan dihapus permanen.');" title="Batalkan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </a>
                        <?php else: ?>
                        <span title="Catatan terkunci setelah 24 jam" style="color:#94a3b8; font-size:13px; cursor:default;">🔒 Terkunci</span>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($deleted_data)): ?>
    <div style="margin-top: 32px;">
        <h3 style="font-size:15px; font-weight:700; color:#94a3b8; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            Riwayat yang Dibatalkan
        </h3>
        <div class="card" style="opacity: 0.8;">
            <table class="crud-table">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Hewan</th>
                        <th>Tipe</th>
                        <th>Deskripsi</th>
                        <th>Perawat</th>
                        <th>Dibatalkan Pada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($deleted_data as $row): ?>
                    <tr style="color:#94a3b8; text-decoration: line-through;">
                        <td><?= $no++ ?></td>
                        <td style="font-weight:600;"><?= htmlspecialchars($row['kode_riwayat_kesehatan'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td><strong><?= htmlspecialchars($row['nama_hewan']) ?></strong></td>
                        <td><?= htmlspecialchars($row['tipe']) ?></td>
                        <td><?= htmlspecialchars(substr($row['deskripsi'], 0, 50)) ?>...</td>
                        <td><?= htmlspecialchars($row['perawat']) ?></td>
                        <td style="font-size:12px; color:#ef4444; text-decoration:none; font-weight:600;">
                            <?= date('d M Y, H:i', strtotime($row['deleted_at'])) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
function filterTable() {
    var search = document.getElementById('searchInput').value.toLowerCase();
    var type = document.getElementById('typeFilter').value;
    var rows = document.querySelectorAll('.crud-table tbody tr');

    rows.forEach(function(row) {
        var colHewan = row.querySelector('.col-hewan');
        var colKode = row.querySelector('.col-kode');
        var colTipe = row.querySelector('.col-tipe');
        
        if (colHewan && colKode && colTipe) {
            var textHewan = colHewan.textContent.toLowerCase();
            var textKode = colKode.textContent.toLowerCase();
            var textTipe = colTipe.textContent.trim();
            
            var matchesSearch = textHewan.includes(search) || textKode.includes(search);
            var matchesType = (type === 'ALL' || textTipe === type);
            
            if (matchesSearch && matchesType) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>