<?php
global $pdo;

// Ambil total pengadopsi yang status verifikasinya bukan 'Terverifikasi'
$stmt_count = $pdo->query("SELECT COUNT(*) FROM pengadopsi WHERE status_verifikasi != 'Terverifikasi'");
$count_menunggu = $stmt_count->fetchColumn();

// Ambil list 5 adopter dengan status_verifikasi bukan 'Terverifikasi' untuk ditampilkan di antrean
$stmt_list = $pdo->query("SELECT id_pengadopsi, nama, email FROM pengadopsi WHERE status_verifikasi != 'Terverifikasi' ORDER BY id_pengadopsi ASC LIMIT 5");
$antrean_ktp = $stmt_list->fetchAll();

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
            <div class="value">42</div>
        </div>
        <div class="stat-card alert">
            <h3>Hewan Karantina / Sakit</h3>
            <div class="value">8</div>
        </div>
        <div class="stat-card">
            <h3>Adopter Belum Verifikasi</h3>
            <div class="value"><?= $count_menunggu ?></div>
        </div>
        <div class="stat-card">
            <h3>Total Donasi Bulan Ini</h3>
            <div class="value" style="font-size: 24px; margin-top:10px;">Rp 4.500.000</div>
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
                        <tr>
                            <td><strong>Adopsi Baru</strong></td>
                            <td>Menunggu persetujuan E-Contract (Milo)</td>
                            <td><span class="badge badge-red">Urgent</span></td>
                        </tr>
                        <tr>
                            <td><strong>Verifikasi KTP</strong></td>
                            <td>Terdapat 3 user baru menunggu cek KTP</td>
                            <td><span class="badge badge-dark">Pending</span></td>
                        </tr>
                        <tr>
                            <td><strong>Kesehatan Hewan</strong></td>
                            <td>Jadwal Vaksinasi Rabies (Rocky) terlewat</td>
                            <td><span class="badge badge-red">Urgent</span></td>
                        </tr>
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
                                        <div style="font-weight: 700; color: var(--hitam);"><?= htmlspecialchars($adopter['nama']) ?></div>
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
            <a href="index.php?page=report_donasi" target="_blank" class="quick-action-btn danger">
                Unduh Laporan PDF
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