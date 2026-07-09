<?php
global $pdo;

// Ambil total pengadopsi yang status verifikasinya bukan 'Terverifikasi'
$stmt_count = $pdo->query("SELECT COUNT(*) FROM pengadopsi WHERE status_verifikasi != 'Terverifikasi'");
$count_menunggu = $stmt_count->fetchColumn();

// Ambil list 5 adopter dengan status_verifikasi bukan 'Terverifikasi' untuk ditampilkan di antrean
$stmt_list = $pdo->query("SELECT id_pengadopsi, nama_lengkap, email FROM pengadopsi WHERE status_verifikasi != 'Terverifikasi' ORDER BY id_pengadopsi ASC LIMIT 5");
$antrean_ktp = $stmt_list->fetchAll();

// Ambil total hewan tersedia
$stmt_hewan_tersedia = $pdo->query("SELECT COUNT(*) FROM hewan WHERE status_adopsi = 'Tersedia'");
$total_tersedia = $stmt_hewan_tersedia->fetchColumn();

// Ambil total hewan karantina
$stmt_hewan_karantina = $pdo->query("SELECT COUNT(*) FROM hewan WHERE status_adopsi = 'Karantina'");
$total_karantina = $stmt_hewan_karantina->fetchColumn();

$stmt_donasi_bulan = $pdo->query("SELECT SUM(nominal) FROM donasi WHERE status_konfirmasi = 'Dikonfirmasi' AND MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())");
$total_donasi = $stmt_donasi_bulan->fetchColumn() ?? 0;
// Ambil data adopsi per bulan untuk tahun ini
$stmt_chart = $pdo->query("
    SELECT MONTH(tanggal_adopsi) as bulan_num, COUNT(*) as jumlah 
    FROM transaksi_adopsi 
    WHERE YEAR(tanggal_adopsi) = YEAR(CURDATE()) 
    GROUP BY MONTH(tanggal_adopsi) 
    ORDER BY MONTH(tanggal_adopsi)
");
$chart_data = $stmt_chart->fetchAll();

$months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$adopsi_counts = array_fill(0, 12, 0);

foreach ($chart_data as $row) {
    $idx = intval($row['bulan_num']) - 1;
    if ($idx >= 0 && $idx < 12) {
        $adopsi_counts[$idx] = intval($row['jumlah']);
    }
}

// Ambil data realtime untuk Pemberitahuan Penting
// 1. Transaksi adopsi menunggu persetujuan kontrak
$stmt_notif_adopsi = $pdo->query("
    SELECT t.id_adopsi, h.nama_hewan, p.nama_lengkap 
    FROM transaksi_adopsi t
    JOIN hewan h ON t.id_hewan = h.id_hewan
    JOIN pengadopsi p ON t.id_pengadopsi = p.id_pengadopsi
    WHERE t.status_kontrak = 'Ditandatangani'
    LIMIT 2
");
$notif_adopsi = $stmt_notif_adopsi->fetchAll();

// 2. Hewan karantina yang butuh persetujuan rilis
$stmt_notif_karantina = $pdo->query("
    SELECT id_hewan, nama_hewan 
    FROM hewan 
    WHERE status_adopsi = 'Karantina' AND rekomendasi_adopsi = 1 
    LIMIT 2
");
$notif_karantina = $stmt_notif_karantina->fetchAll();
?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <div class="dash-header">
        <div class="dash-title">
            <h2>Overview System</h2>
            <p>Pantau aktivitas operasional shelter dan transaksi adopsi.</p>
        </div>
        <div class="user-profile">
            <div class="avatar">SA</div>
            <span><?= htmlspecialchars($_SESSION['username'] ?? 'SuperAdmin') ?></span>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Hewan Tersedia</h3>
            <div class="value"><?= $total_tersedia ?></div>
        </div>
        <div class="stat-card alert">
            <h3>Hewan Karantina / Sakit</h3>
            <div class="value"><?= $total_karantina ?></div>
        </div>
        <div class="stat-card">
            <h3>Adopter Belum Verifikasi</h3>
            <div class="value"><?= $count_menunggu ?></div>
        </div>
        <div class="stat-card">
            <h3>Total Donasi Bulan Ini</h3>
            <div class="value" style="font-size: 24px; margin-top:10px;">Rp <?= number_format($total_donasi, 0, ',', '.') ?></div>
        </div>
    </div>

    <div class="content-grid">
        <div style="display: flex; flex-direction: column; gap: 25px;">
            <!-- Grafik Adopsi Bulanan -->
            <div class="content-panel" style="background:#ffffff; border-radius:16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03); padding: 25px;">
                <div class="panel-header" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size:16px; font-weight:700; color:#0f172a; margin: 0; display:flex; align-items:center; gap:8px;">
                        📈 Grafik Hewan yang Telah Diadopsi (Per Bulan)
                    </h3>
                    <span style="font-size:12px; font-weight:600; color:#64748b; background:#f1f5f9; padding:4px 10px; border-radius:20px;">Tahun <?= date('Y') ?></span>
                </div>
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="adoptionChart"></canvas>
                </div>
            </div>

            <!-- Pemberitahuan Penting -->
            <div class="content-panel">
                <div class="panel-header">
                    <h3>Pemberitahuan Penting</h3>
                    <a href="index.php?page=transaksi_adopsi" class="link-danger">Lihat Semua</a>
                </div>
                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>Subjek</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $has_notif = false;
                        
                        // Render adopsi baru menunggu persetujuan
                        foreach ($notif_adopsi as $na) {
                            $has_notif = true;
                            echo '<tr>';
                            echo '<td><strong>Adopsi Baru</strong></td>';
                            echo '<td>Menunggu persetujuan E-Contract (' . htmlspecialchars($na['nama_hewan']) . ' oleh ' . htmlspecialchars($na['nama_lengkap']) . ')</td>';
                            echo '<td><span class="badge badge-red">Urgent</span></td>';
                            echo '</tr>';
                        }

                        // Render verifikasi KTP tertunda
                        if ($count_menunggu > 0) {
                            $has_notif = true;
                            echo '<tr>';
                            echo '<td><strong>Verifikasi KTP</strong></td>';
                            echo '<td>Terdapat ' . $count_menunggu . ' calon adopter baru menunggu verifikasi</td>';
                            echo '<td><span class="badge badge-dark">Pending</span></td>';
                            echo '</tr>';
                        }

                        // Render hewan karantina siap dirilis
                        foreach ($notif_karantina as $nk) {
                            $has_notif = true;
                            echo '<tr>';
                            echo '<td><strong>Kesehatan Hewan</strong></td>';
                            echo '<td>Hewan karantina (' . htmlspecialchars($nk['nama_hewan']) . ') siap dirilis ke katalog</td>';
                            echo '<td><span class="badge badge-red">Urgent</span></td>';
                            echo '</tr>';
                        }

                        // Jika tidak ada pemberitahuan penting
                        if (!$has_notif) {
                            echo '<tr>';
                            echo '<td colspan="3" style="text-align:center; color:#94a3b8; padding: 20px 0;">✅ Semua sistem berjalan dengan baik. Tidak ada notifikasi mendesak.</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Antrean Verifikasi KTP -->
            <div class="content-panel">
                <div class="panel-header">
                    <h3>⏳ Antrean Verifikasi KTP</h3>
                    <a href="index.php?page=pengadopsi" class="link-danger">Lihat Semua</a>
                </div>
                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>Nama Adopter</th>
                            <th style="text-align: right; padding-right: 25px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($antrean_ktp) > 0): ?>
                            <?php foreach ($antrean_ktp as $adopter): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 700; color: var(--hitam);"><?= htmlspecialchars($adopter['nama_lengkap']) ?></div>
                                        <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($adopter['email']) ?></div>
                                    </td>
                                    <td style="text-align: right; padding-right: 15px;">
                                        <a href="index.php?page=pengadopsi&id=<?= $adopter['id_pengadopsi'] ?>" class="btn-periksa">Periksa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 20px;">
                                    Tidak ada antrean verifikasi KTP saat ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="content-panel" style="height: fit-content;">
            <div class="panel-header">
                <h3>Aksi Cepat</h3>
            </div>
            <a href="index.php?page=hewan_create" class="quick-action-btn">
                + Tambah Data Hewan
            </a>
            <a href="index.php?page=pengadopsi" class="quick-action-btn">
                Verifikasi Adopter Baru
            </a>
            <a href="index.php?page=report_donasi_pemasukan" target="_blank" class="quick-action-btn danger">
                Laporan Donasi Pemasukan (PDF)
            </a>
            <a href="index.php?page=report_donasi" target="_blank" class="quick-action-btn danger">
                Laporan Pemasukan & Pengeluaran (PDF)
            </a>
        </div>
    </div>
</div>

<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('adoptionChart').getContext('2d');
    var adoptionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [{
                label: 'Jumlah Hewan Diadopsi',
                data: <?= json_encode($adopsi_counts) ?>,
                backgroundColor: 'rgba(79, 70, 229, 0.85)', 
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 1,
                borderRadius: 4, 
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            family: 'Outfit, Inter, sans-serif',
                            weight: '600'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        stepSize: 1,
                        color: '#64748b',
                        font: {
                            family: 'Outfit, Inter, sans-serif',
                            weight: '600'
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>