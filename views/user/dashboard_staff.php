<?php
global $pdo;
$uid = $_SESSION['user_id'] ?? 0;

// ── 1. WIDGET STATISTIK ──────────────────────────────────────────────────────
$total_karantina = $pdo->query("SELECT COUNT(*) FROM hewan WHERE status_adopsi = 'Karantina'")->fetchColumn();
$total_siap_rilis = $pdo->query("SELECT COUNT(*) FROM hewan WHERE status_adopsi = 'Karantina' AND rekomendasi_adopsi = 1")->fetchColumn();
$total_tersedia   = $pdo->query("SELECT COUNT(*) FROM hewan WHERE status_adopsi = 'Tersedia'")->fetchColumn();

// Rekam medis yang dicatat hari ini (semua perawat)
$total_rk_hari_ini = $pdo->query("SELECT COUNT(*) FROM riwayat_kesehatan WHERE DATE(created_at) = CURDATE() AND deleted_at IS NULL")->fetchColumn();

// ── 2. ALERT: Kandang hampir penuh (>= 80%) ─────────────────────────────────
$stmt_kandang = $pdo->query("
    SELECT k.nama_kandang, k.kapasitas,
           COUNT(pk.id_penempatan) AS terisi
    FROM kandang k
    LEFT JOIN penempatan_kandang pk ON pk.id_kandang = k.id_kandang AND pk.status = 'Aktif'
    GROUP BY k.id_kandang, k.nama_kandang, k.kapasitas
    HAVING k.kapasitas > 0 AND (COUNT(pk.id_penempatan) / k.kapasitas) >= 0.8
    ORDER BY (COUNT(pk.id_penempatan) / k.kapasitas) DESC
    LIMIT 5
");
$kandang_penuh = $stmt_kandang->fetchAll();

// ── 3. ALERT: Hewan intake baru (7 hari terakhir, belum ada rekam medis) ────
$stmt_intake = $pdo->query("
    SELECT h.kode_hewan, h.nama_hewan, h.tanggal_intake, h.status_adopsi
    FROM hewan h
    WHERE h.tanggal_intake >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
      AND NOT EXISTS (
          SELECT 1 FROM riwayat_kesehatan r
          WHERE r.id_hewan = h.id_hewan AND r.deleted_at IS NULL
      )
    ORDER BY h.tanggal_intake DESC
    LIMIT 5
");
$intake_baru = $stmt_intake->fetchAll();

// ── 4. RIWAYAT 5 TINDAKAN TERAKHIR PERAWAT YANG LOGIN ───────────────────────
$stmt_riwayat = $pdo->prepare("
    SELECT r.id_riwayat, r.tipe, r.tanggal, r.created_at, r.deskripsi,
           h.nama_hewan, v.nama_vaksin
    FROM riwayat_kesehatan r
    JOIN hewan h ON r.id_hewan = h.id_hewan
    LEFT JOIN vaksin v ON r.id_vaksin = v.id_vaksin
    WHERE r.id_pengguna = ? AND r.deleted_at IS NULL
    ORDER BY r.id_riwayat DESC
    LIMIT 5
");
$stmt_riwayat->execute([$uid]);
$riwayat_saya = $stmt_riwayat->fetchAll();
?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar_admin.php'; ?>

<style>
    /* ── Widget Statistik ───────────────────────── */
    .ds-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }
    .ds-card {
        background: #fff;
        border-radius: 16px;
        padding: 22px 24px;
        border: 1px solid rgba(0,0,0,0.07);
        box-shadow: 0 4px 18px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        gap: 18px;
    }
    .ds-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .ds-icon.orange  { background: #fff7ed; }
    .ds-icon.blue    { background: #eff6ff; }
    .ds-icon.green   { background: #f0fdf4; }
    .ds-icon.purple  { background: #f5f3ff; }
    .ds-num  { font-size: 30px; font-weight: 800; color: #0f172a; line-height: 1; }
    .ds-label{ font-size: 12px; color: #64748b; font-weight: 600; margin-top: 4px; }

    /* ── Alert & Tabel ───────────────────────────── */
    .ds-section {
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.07);
        box-shadow: 0 4px 18px rgba(0,0,0,0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .ds-section-head {
        display: flex; align-items: center; gap: 8px;
        padding: 16px 22px;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 700; font-size: 15px; color: #1e293b;
    }
    .ds-table { width: 100%; border-collapse: collapse; }
    .ds-table th {
        background: #f8fafc; padding: 10px 16px;
        font-size: 12px; font-weight: 700; color: #64748b;
        text-align: left; border-bottom: 1px solid #f1f5f9;
    }
    .ds-table td {
        padding: 11px 16px; font-size: 13px; color: #1e293b;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
    }
    .ds-table tr:last-child td { border-bottom: none; }
    .ds-empty { padding: 28px; text-align: center; color: #94a3b8; font-size: 13px; }

    /* Progress bar kandang */
    .pbar { width: 120px; height: 8px; background: #e2e8f0; border-radius: 8px; overflow: hidden; display: inline-block; vertical-align: middle; margin-left: 8px; }
    .pbar-fill { display: block; height: 100%; border-radius: 8px; }
    .pbar-fill.warn  { background: #f59e0b; }
    .pbar-fill.danger{ background: #ef4444; }

    /* Badge tipe riwayat */
    .badge-tipe {
        padding: 3px 9px; border-radius: 20px;
        font-size: 11px; font-weight: 700; display: inline-block;
    }
    .badge-vaksin    { background: #eff6ff; color: #1d4ed8; }
    .badge-periksa   { background: #f0fdf4; color: #166534; }
    .badge-obat      { background: #fff7ed; color: #c2410c; }
    .badge-lain      { background: #f5f3ff; color: #6d28d9; }

    /* Kunci jam */
    .lock-icon { font-size: 12px; color: #94a3b8; }
</style>

<div class="main-wrapper">

    <!-- Header -->
    <header class="admin-header">
        <div>
            <h2 style="margin:0; font-size:20px;">🩺 Dashboard Perawat Hewan</h2>
            <p style="margin:4px 0 0; font-size:13px; color:#64748b;">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']) ?></strong> — <?= date('l, d F Y') ?></p>
        </div>
        <div class="user-badge" style="background:#3b82f6; color:#fff; padding:6px 15px; border-radius:20px; font-weight:600; font-size:13px;">
            👤 <?= htmlspecialchars($_SESSION['username']) ?> · Perawat Hewan
        </div>
    </header>

    <!-- ── WIDGET STATISTIK ──────────────────────────── -->
    <div class="ds-grid">
        <div class="ds-card">
            <div class="ds-icon orange">🏥</div>
            <div>
                <div class="ds-num" style="color:#ea580c;"><?= $total_karantina ?></div>
                <div class="ds-label">Hewan Sakit / Karantina</div>
            </div>
        </div>
        <div class="ds-card">
            <div class="ds-icon blue">👍</div>
            <div>
                <div class="ds-num" style="color:#2563eb;"><?= $total_siap_rilis ?></div>
                <div class="ds-label">Menunggu Persetujuan Rilis</div>
            </div>
        </div>
        <div class="ds-card">
            <div class="ds-icon green">🐾</div>
            <div>
                <div class="ds-num" style="color:#16a34a;"><?= $total_tersedia ?></div>
                <div class="ds-label">Hewan Sehat & Tersedia</div>
            </div>
        </div>
        <div class="ds-card">
            <div class="ds-icon purple">📝</div>
            <div>
                <div class="ds-num" style="color:#7c3aed;"><?= $total_rk_hari_ini ?></div>
                <div class="ds-label">Rekam Medis Hari Ini</div>
            </div>
        </div>
    </div>

    <!-- ── BARIS 2: Alert (2 kolom) ─────────────────── -->
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:22px; margin-bottom:24px;">

        <!-- Alert Kandang Hampir Penuh -->
        <div class="ds-section">
            <div class="ds-section-head">⚠️ Kandang Hampir Penuh <span style="font-size:11px; color:#94a3b8; font-weight:500;">(≥80% kapasitas)</span></div>
            <?php if (count($kandang_penuh) > 0): ?>
            <table class="ds-table">
                <thead>
                    <tr>
                        <th>Kandang</th>
                        <th>Isi / Kapasitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kandang_penuh as $k):
                        $pct = $k['kapasitas'] > 0 ? round(($k['terisi'] / $k['kapasitas']) * 100) : 0;
                        $kls = $pct >= 100 ? 'danger' : 'warn';
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($k['nama_kandang']) ?></strong></td>
                        <td>
                            <span style="font-weight:700;"><?= $k['terisi'] ?> / <?= $k['kapasitas'] ?> Ekor</span>
                            <span class="pbar"><span class="pbar-fill <?= $kls ?>" style="width:<?= min($pct,100) ?>%"></span></span>
                            <span style="font-size:11px; color:#94a3b8;"><?= $pct ?>%</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="ds-empty">✅ Semua kandang masih dalam kapasitas aman.</div>
            <?php endif; ?>
        </div>

        <!-- Alert Intake Baru Belum Diperiksa -->
        <div class="ds-section">
            <div class="ds-section-head">🆕 Hewan Baru Belum Diperiksa <span style="font-size:11px; color:#94a3b8; font-weight:500;">(7 hari terakhir)</span></div>
            <?php if (count($intake_baru) > 0): ?>
            <table class="ds-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Hewan</th>
                        <th>Tgl Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($intake_baru as $h): ?>
                    <tr>
                        <td><span style="font-size:11px; color:#94a3b8; font-weight:700;"><?= htmlspecialchars($h['kode_hewan']) ?></span></td>
                        <td>
                            <a href="index.php?page=riwayat_kesehatan_create" style="color:#2563eb; font-weight:600; text-decoration:none;">
                                <?= htmlspecialchars($h['nama_hewan']) ?>
                            </a>
                        </td>
                        <td style="color:#94a3b8;"><?= date('d M', strtotime($h['tanggal_intake'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="ds-empty">✅ Semua hewan baru sudah diperiksa kesehatannya.</div>
            <?php endif; ?>
        </div>

    </div>

    <!-- ── RIWAYAT TINDAKAN TERAKHIR SAYA ───────────── -->
    <div class="ds-section">
        <div class="ds-section-head">
            🩺 5 Tindakan Terakhir Saya
            <a href="index.php?page=riwayat_kesehatan" style="margin-left:auto; font-size:12px; color:#3b82f6; font-weight:500; text-decoration:none;">Lihat Semua →</a>
        </div>
        <?php if (count($riwayat_saya) > 0):
            // Cek apakah masih bisa diubah (< 24 jam)
        ?>
        <table class="ds-table">
            <thead>
                <tr>
                    <th>Hewan</th>
                    <th>Tipe</th>
                    <th>Tgl Periksa</th>
                    <th>Dicatat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($riwayat_saya as $r):
                    $tipe_map = [
                        'Vaksinasi'  => ['label'=>'Vaksinasi', 'class'=>'badge-vaksin'],
                        'Pemeriksaan'=> ['label'=>'Periksa',   'class'=>'badge-periksa'],
                        'Pengobatan' => ['label'=>'Obat',      'class'=>'badge-obat'],
                    ];
                    $tipe_info = $tipe_map[$r['tipe']] ?? ['label'=>$r['tipe'], 'class'=>'badge-lain'];
                ?>
                <tr>
                    <td><strong>🐾 <?= htmlspecialchars($r['nama_hewan']) ?></strong></td>
                    <td>
                        <span class="badge-tipe <?= $tipe_info['class'] ?>"><?= $tipe_info['label'] ?></span>
                        <?php if (!empty($r['nama_vaksin'])): ?>
                            <br><small style="color:#94a3b8; font-size:11px;"><?= htmlspecialchars($r['nama_vaksin']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                    <td style="color:#94a3b8; font-size:12px;"><?= date('d M, H:i', strtotime($r['created_at'])) ?></td>
                    <td>
                        <span style="color:#16a34a; font-size:12px; font-weight:700;">✏️ Bisa diubah</span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="ds-empty">
                Belum ada rekam medis yang Anda catat.<br>
                <a href="index.php?page=riwayat_kesehatan_create" style="color:#3b82f6; font-weight:600;">+ Tambah Rekam Medis Pertama</a>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>