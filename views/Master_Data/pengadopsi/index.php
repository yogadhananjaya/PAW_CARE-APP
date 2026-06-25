<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<?php
// Ambil database pdo dari file connect.php yang terhubung di index.php
global $pdo;

// Deteksi jika ada adopter yang sedang diperiksa/ditinjau
$active_adopter = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM pengadopsi WHERE id_pengadopsi = ?");
    $stmt->execute([intval($_GET['id'])]);
    $active_adopter = $stmt->fetch();
}
?>

<div class="main-wrapper">
    <header class="admin-header">
        <div>
            <h2>Data Pengadopsi</h2>
            <p>Daftar calon pengadopsi yang mendaftar mandiri di sistem PawCare.</p>
        </div>
        <a href="index.php?page=pengadopsi_create" class="btn btn-primary">+ Tambah Pengadopsi</a>
    </header>

    <!-- Bagian 2: Panel Meninjau Adopter (Tampil Hanya Jika ID Dipilih) -->
    <?php if ($active_adopter): ?>
    <div class="card" style="background:#ffffff; border-radius:16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03); padding: 30px; margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                🔎 Meninjau Adopter: <span style="color:#4f46e5;"><?= htmlspecialchars($active_adopter['nama']) ?></span>
            </h3>
            <a href="index.php?page=pengadopsi" class="btn btn-secondary" style="background:#f1f5f9; color:#475569; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">Kembali ke Daftar</a>
        </div>

        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 40px; align-items: start;">
            <!-- Kolom Kiri: Form & Informasi -->
            <div>
                <!-- Info Detail -->
                <div style="display:grid; gap: 15px; margin-bottom:25px;">
                    <div>
                        <span style="font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase;">Nama Lengkap:</span>
                        <div style="font-size: 14px; font-weight: 500; color: #334155; margin-top: 2px;"><?= htmlspecialchars($active_adopter['nama']) ?></div>
                    </div>
                    <div>
                        <span style="font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase;">Email:</span>
                        <div style="font-size: 14px; font-weight: 500; color: #334155; margin-top: 2px;"><?= htmlspecialchars($active_adopter['email']) ?></div>
                    </div>
                    <div>
                        <span style="font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase;">Nomor HP/WA:</span>
                        <div style="font-size: 14px; font-weight: 500; color: #334155; margin-top: 2px;"><?= htmlspecialchars($active_adopter['no_hp']) ?></div>
                    </div>
                    <div>
                        <span style="font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase;">Alamat Rumah:</span>
                        <div style="font-size: 14px; font-weight: 500; color: #334155; margin-top: 2px; line-height:1.5;"><?= htmlspecialchars($active_adopter['alamat']) ?></div>
                    </div>
                </div>

                <hr style="border:none; border-top:1px solid #f1f5f9; margin: 20px 0;">

                <!-- Form Keputusan Verifikasi -->
                <form action="index.php?page=pengadopsi_edit&id=<?= $active_adopter['id_pengadopsi'] ?>" method="POST">
                    <!-- Kita passing data lama agar model update tidak null -->
                    <input type="hidden" name="nama" value="<?= htmlspecialchars($active_adopter['nama']) ?>">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($active_adopter['email']) ?>">
                    <input type="hidden" name="no_hp" value="<?= htmlspecialchars($active_adopter['no_hp']) ?>">
                    <input type="hidden" name="alamat" value="<?= htmlspecialchars($active_adopter['alamat']) ?>">

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">Keputusan Verifikasi</label>
                        <select name="status_verifikasi" class="form-control" style="width:100%; padding:10px 15px; border-radius:8px; border:1px solid #cbd5e1; outline:none; font-size:14px;" onchange="toggleCatatan(this.value)">
                            <option value="Terverifikasi" <?= $active_adopter['status_verifikasi'] === 'Terverifikasi' ? 'selected' : '' ?>>✅ Setujui (Terverifikasi)</option>
                            <option value="Ditolak" <?= $active_adopter['status_verifikasi'] === 'Ditolak' ? 'selected' : '' ?>>❌ Tolak (Ditolak)</option>
                            <option value="Menunggu" <?= $active_adopter['status_verifikasi'] === 'Menunggu' ? 'selected' : '' ?>>⏳ Menunggu (Menunggu)</option>
                        </select>
                    </div>

                    <!-- Input Catatan Penolakan (Tampil jika Ditolak) -->
                    <div id="catatanGroup" class="form-group" style="margin-top: 15px; display: <?= $active_adopter['status_verifikasi'] === 'Ditolak' ? 'block' : 'none' ?>;">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">Alasan Penolakan</label>
                        <textarea name="catatan_verifikasi" class="form-control" rows="2" placeholder="Contoh: Foto KTP buram, silakan upload ulang." style="width:100%; padding:10px 15px; border-radius:8px; border:1px solid #cbd5e1; font-size:13px; resize:none; outline:none;"><?= htmlspecialchars($active_adopter['catatan_verifikasi'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">Tanggal Verifikasi</label>
                        <input type="date" name="tanggal_verifikasi" class="form-control" value="<?= htmlspecialchars($active_adopter['tanggal_verifikasi'] ?? date('Y-m-d')) ?>" style="width:100%; padding:10px 15px; border-radius:8px; border:1px solid #cbd5e1; font-size:14px; outline:none;">
                    </div>

                    <button type="submit" class="btn" style="width:100%; background:#4f46e5; color:#ffffff; font-weight:600; padding:12px; border:none; border-radius:8px; margin-top:20px; font-size:14px; cursor:pointer; transition:background 0.2s;">Simpan Keputusan</button>
                </form>
            </div>

            <!-- Kolom Kanan: Berkas KTP -->
            <div>
                <span style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 12px; display: block; text-align:center;">Berkas Foto KTP:</span>
                
                <?php 
                $file_exists = false;
                if (!empty($active_adopter['url_ktp'])) {
                    $filepath = __DIR__ . '/../../../assets/img/ktp/' . $active_adopter['url_ktp'];
                    if (file_exists($filepath)) {
                        $file_exists = true;
                    }
                }
                ?>

                <?php if ($file_exists): ?>
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:10px; border-radius:12px; text-align:center;">
                        <a href="assets/img/ktp/<?= htmlspecialchars($active_adopter['url_ktp']) ?>" target="_blank">
                            <img src="assets/img/ktp/<?= htmlspecialchars($active_adopter['url_ktp']) ?>" style="max-width:100%; max-height:280px; border-radius:8px; border:1px solid #e2e8f0; box-shadow:0 4px 12px rgba(0,0,0,0.05);" alt="Foto KTP">
                        </a>
                        <p style="color:#64748b; font-size:12px; margin-top:8px;">Klik gambar untuk memperbesar di tab baru</p>
                    </div>
                <?php else: ?>
                    <div style="background:#ffebee; border: 1px dashed #ffcdd2; color:#c62828; padding:35px 20px; border-radius:12px; text-align:center; font-size:14px; font-weight:600; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px;">
                        <span style="font-size:24px;">⚠</span> File KTP tidak ditemukan di server.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <table class="crud-table">
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th>Status Verifikasi</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_pengadopsi']) ?></td>
                    <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['no_hp']) ?></td>
                    <td><?= htmlspecialchars(substr($row['alamat'], 0, 40)) ?>...</td>
                    <td>
                        <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; 
                            background-color: <?= $row['status_verifikasi'] == 'Terverifikasi' ? '#e2fbe8; color:#2ecc71;' : ($row['status_verifikasi'] == 'Ditolak' ? '#fce4e4; color:#e74c3c;' : ($row['status_verifikasi'] == 'Menunggu' ? '#e1f5fe; color:#3498db;' : '#fff3cd; color:#f1c40f;')) ?>">
                            <?= $row['status_verifikasi'] ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row['status_verifikasi'] !== 'Terverifikasi'): ?>
                        <a href="index.php?page=pengadopsi&id=<?= $row['id_pengadopsi'] ?>" class="btn btn-sm" style="background:#4f46e5; color:#ffffff; width:auto; padding:0 12px; font-size:11px; font-weight:600; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; height:36px; margin-right:4px;" title="Periksa KTP">
                            Periksa KTP
                        </a>
                        <?php endif; ?>
                        <a href="index.php?page=pengadopsi_edit&id=<?= $row['id_pengadopsi'] ?>" class="btn btn-sm btn-warning" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </a>
                        <a href="index.php?page=pengadopsi_delete&id=<?= $row['id_pengadopsi'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data pengadopsi ini?');" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleCatatan(value) {
    var group = document.getElementById('catatanGroup');
    if (value === 'Ditolak') {
        group.style.display = 'block';
    } else {
        group.style.display = 'none';
    }
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>