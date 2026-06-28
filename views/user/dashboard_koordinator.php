<?php
// =========================================================================
// DASHBOARD KOORDINATOR - Menampilkan ringkasan operasional & tugas harian
// =========================================================================

// Ambil koneksi database global
global $pdo;

// -------------------------------------------------------------------------
// 1. AMBIL DATA STATISTIK (angka-angka ringkasan)
// -------------------------------------------------------------------------

// Hitung riwayat kesehatan bulan ini
$sql_riwayat = "SELECT COUNT(*) FROM riwayat_kesehatan WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())";
$result_riwayat = $pdo->query($sql_riwayat);
$count_riwayat = $result_riwayat->fetchColumn();

// Hitung kunjungan hari ini
$sql_kunjungan = "SELECT COUNT(*) FROM jadwal_kunjungan WHERE DATE(tanggal_jadwal) = CURDATE()";
$result_kunjungan = $pdo->query($sql_kunjungan);
$count_kunjungan = $result_kunjungan->fetchColumn();

// Hitung adopsi yang sedang aktif
$sql_adopsi = "SELECT COUNT(*) FROM transaksi_adopsi WHERE status_kontrak = 'Aktif'";
$result_adopsi = $pdo->query($sql_adopsi);
$count_adopsi = $result_adopsi->fetchColumn();

// Hitung kandang yang tersedia
$sql_kandang = "SELECT COUNT(*) FROM kandang WHERE status = 'Tersedia'";
$result_kandang = $pdo->query($sql_kandang);
$count_kandang = $result_kandang->fetchColumn();

// -------------------------------------------------------------------------
// 2. AMBIL DATA TUGAS MENUNGGU
// -------------------------------------------------------------------------

// Tugas 1: Kunjungan yang masih menunggu konfirmasi
$sql_task_kunjungan = "SELECT j.id_jadwal, p.nama_lengkap as adopter, h.nama_hewan, j.tanggal_jadwal
    FROM jadwal_kunjungan j
    JOIN pengadopsi p ON j.id_pengadopsi = p.id_pengadopsi
    JOIN hewan h ON j.id_hewan = h.id_hewan
    WHERE j.status_jadwal = 'Menunggu'
    ORDER BY j.tanggal_jadwal ASC
    LIMIT 5";
$result_task_kunjungan = $pdo->query($sql_task_kunjungan);
$taskKunjungan = $result_task_kunjungan->fetchAll();

// Tugas 2: Kontrak yang sudah ditandatangani tapi belum diaktifkan
$sql_task_kontrak = "SELECT t.id_adopsi, p.nama_lengkap as adopter, h.nama_hewan
    FROM transaksi_adopsi t
    JOIN pengadopsi p ON t.id_pengadopsi = p.id_pengadopsi
    JOIN hewan h ON t.id_hewan = h.id_hewan
    WHERE t.status_kontrak = 'Ditandatangani'
    LIMIT 5";
$result_task_kontrak = $pdo->query($sql_task_kontrak);
$taskKontrak = $result_task_kontrak->fetchAll();

// Tugas 3: Kandang yang sudah terisi lebih dari 80%
$sql_task_kandang = "SELECT k.kode_kandang, k.nama_kandang, k.kapasitas,
    (SELECT COUNT(*) FROM penempatan_kandang pk WHERE pk.id_kandang = k.id_kandang AND pk.status = 'Aktif') as terisi
    FROM kandang k
    HAVING terisi > (k.kapasitas * 0.8)
    LIMIT 5";
$result_task_kandang = $pdo->query($sql_task_kandang);
$taskKandang = $result_task_kandang->fetchAll();

// Tugas 4: Hewan karantina yang sudah direkomendasikan perawat butuh persetujuan rilis
$sql_task_rilis = "SELECT id_hewan, kode_hewan, nama_hewan FROM hewan WHERE status_adopsi = 'Karantina' AND rekomendasi_adopsi = 1 LIMIT 5";
$result_task_rilis = $pdo->query($sql_task_rilis);
$taskRilis = $result_task_rilis->fetchAll();

// -------------------------------------------------------------------------
// 3. SIAPKAN DATA USER YANG SEDANG LOGIN
// -------------------------------------------------------------------------

// Ambil info user dari session
$username_session = "koordinator";
if (isset($_SESSION['username'])) {
    $username_session = $_SESSION['username'];
}

$nama_lengkap_session = "Koordinator";
if (isset($_SESSION['nama_lengkap'])) {
    $nama_lengkap_session = $_SESSION['nama_lengkap'];
}

$role_session = "Koordinator";
if (isset($_SESSION['role'])) {
    $role_session = $_SESSION['role'];
}

?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar_admin.php'; ?>

<!-- ===================================================================== -->
<!-- KONTEN UTAMA DASHBOARD KOORDINATOR                                     -->
<!-- ===================================================================== -->
<div class="main-wrapper">

    <!-- Header Halaman -->
    <header class="admin-header">
        <div>
            <h2>Dashboard Koordinator</h2>
            <p>Pantau operasional shelter dan tugas hari ini.</p>
        </div>
        <!-- Badge nama user yang sedang login -->
        <div style="background: #111111; color: #ffffff; padding: 8px 18px; border-radius: 20px; font-weight: 600; font-size: 13px;">
            👤 <?php echo htmlspecialchars($nama_lengkap_session); ?>
        </div>
    </header>

    <!-- ================================================================= -->
    <!-- BAGIAN 1: EMPAT KARTU STATISTIK (GRID)                           -->
    <!-- ================================================================= -->
    <div class="stats-grid" style="margin-bottom: 30px;">

        <!-- Stat 1: Riwayat Kesehatan Bulan Ini -->
        <div class="stat-card">
            <h3>Riwayat Kesehatan (Bulan Ini)</h3>
            <div class="value"><?php echo $count_riwayat; ?></div>
        </div>

        <!-- Stat 2: Kunjungan Hari Ini -->
        <div class="stat-card">
            <h3>Kunjungan Hari Ini</h3>
            <div class="value"><?php echo $count_kunjungan; ?></div>
        </div>

        <!-- Stat 3: Adopsi Aktif -->
        <div class="stat-card">
            <h3>Adopsi Aktif</h3>
            <div class="value"><?php echo $count_adopsi; ?></div>
        </div>

        <!-- Stat 4: Kandang Tersedia -->
        <div class="stat-card">
            <h3>Kandang Tersedia</h3>
            <div class="value"><?php echo $count_kandang; ?></div>
        </div>

    </div>

    <!-- ================================================================= -->
    <!-- BAGIAN 2: DUA KOLOM (TUGAS & AKSI CEPAT)                         -->
    <!-- ================================================================= -->
    <div class="content-grid">

        <!-- Kolom Kiri: Daftar Tugas Menunggu -->
        <div class="content-panel">
            <div class="panel-header">
                <h3>⚠️ Tugas Menunggu</h3>
            </div>

            <?php
            // Hitung total semua tugas (untuk mengecek apakah kosong)
            $total_tugas = count($taskKunjungan) + count($taskKontrak) + count($taskKandang) + count($taskRilis);

            // Jika semua tugas kosong, tampilkan pesan
            if ($total_tugas == 0) {
                echo '<p style="color: #94a3b8; font-size: 14px; padding: 20px 0; text-align: center;">✅ Tidak ada tugas menunggu saat ini.</p>';
            }
            ?>

            <?php
            // -----------------------------------------------------------------
            // Tugas 1: Kunjungan menunggu konfirmasi
            // -----------------------------------------------------------------
            if (count($taskKunjungan) > 0) {
                echo '<div style="margin-bottom: 18px;">';
                echo '<div style="font-weight: 700; font-size: 13px; color: #d97706; margin-bottom: 8px;">📅 Kunjungan Menunggu Konfirmasi</div>';
                
                foreach ($taskKunjungan as $tk) {
                    $nama_adopter   = htmlspecialchars($tk['adopter']);
                    $nama_hewan     = htmlspecialchars($tk['nama_hewan']);
                    $jam_kunjungan  = date('H:i', strtotime($tk['tanggal_jadwal']));
                    
                    echo '<div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">';
                    echo '<div>';
                    echo '<strong>' . $nama_adopter . '</strong>';
                    echo '<span style="color: #64748b;"> — ' . $nama_hewan . '</span>';
                    echo '</div>';
                    echo '<span style="font-size: 12px; color: #94a3b8;">' . $jam_kunjungan . '</span>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>

            <?php
            // -----------------------------------------------------------------
            // Tugas 2: Kontrak siap aktivasi
            // -----------------------------------------------------------------
            if (count($taskKontrak) > 0) {
                echo '<div style="margin-bottom: 18px;">';
                echo '<div style="font-weight: 700; font-size: 13px; color: #0369a1; margin-bottom: 8px;">🤝 Kontrak Siap Aktivasi</div>';
                
                foreach ($taskKontrak as $tr) {
                    $nama_adopter = htmlspecialchars($tr['adopter']);
                    $nama_hewan   = htmlspecialchars($tr['nama_hewan']);
                    $link_edit    = 'index.php?page=transaksi_adopsi_edit&id=' . $tr['id_adopsi'];
                    
                    echo '<div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">';
                    echo '<div>';
                    echo '<strong>' . $nama_adopter . '</strong>';
                    echo '<span style="color: #64748b;"> — ' . $nama_hewan . '</span>';
                    echo '</div>';
                    echo '<a href="' . $link_edit . '" style="font-size: 12px; font-weight: 600; color: #15803d; text-decoration: none;">Aktifkan →</a>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>

            <?php
            // -----------------------------------------------------------------
            // Tugas 3: Kandang mendekati penuh (lebih dari 80%)
            // -----------------------------------------------------------------
            if (count($taskKandang) > 0) {
                echo '<div style="margin-bottom: 18px;">';
                echo '<div style="font-weight: 700; font-size: 13px; color: #ef4444; margin-bottom: 8px;">📦 Kandang Mendekati Penuh (&gt;80%)</div>';
                
                foreach ($taskKandang as $tkd) {
                    $nama_kandang  = htmlspecialchars($tkd['nama_kandang']);
                    $kode_kandang  = htmlspecialchars($tkd['kode_kandang']);
                    $terisi        = $tkd['terisi'];
                    $kapasitas     = $tkd['kapasitas'];
                    
                    echo '<div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">';
                    echo '<div>';
                    echo '<strong>' . $nama_kandang . '</strong>';
                    echo '<span style="color: #64748b;"> (' . $kode_kandang . ')</span>';
                    echo '</div>';
                    echo '<span style="font-size: 12px; color: #ef4444; font-weight: 600;">' . $terisi . '/' . $kapasitas . '</span>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>

            <?php
            // -----------------------------------------------------------------
            // Tugas 4: Persetujuan Rilis Hewan Karantina
            // -----------------------------------------------------------------
            if (count($taskRilis) > 0) {
                echo '<div style="margin-bottom: 18px;">';
                echo '<div style="font-weight: 700; font-size: 13px; color: #10b981; margin-bottom: 8px;">🏥 Persetujuan Rilis Hewan Karantina</div>';
                
                foreach ($taskRilis as $trd) {
                    $nama_hewan = htmlspecialchars($trd['nama_hewan']);
                    $kode_hewan = htmlspecialchars($trd['kode_hewan'] ?? '');
                    $link_rilis = 'index.php?page=hewan_confirm&id=' . $trd['id_hewan'];
                    
                    echo '<div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">';
                    echo '<div>';
                    echo '<strong>' . $nama_hewan . '</strong>';
                    echo '<span style="color: #64748b;"> — ' . $kode_hewan . '</span>';
                    echo '</div>';
                    echo '<a href="' . $link_rilis . '" style="font-size: 12px; font-weight: 600; color: #10b981; text-decoration: none;" onclick="return confirm(\'Setujui rilis hewan ini ke katalog adopsi?\')">Setujui &amp; Rilis →</a>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>

        </div>

        <!-- Kolom Kanan: Aksi Cepat -->
        <div class="content-panel" style="height: fit-content;">
            <div class="panel-header">
                <h3>🏃 Aksi Cepat</h3>
            </div>

            <!-- Tombol ke Jadwal Kunjungan -->
            <a href="index.php?page=jadwal_kunjungan" class="quick-action-btn">
                📅 Jadwal Kunjungan
            </a>

            <!-- Tombol ke Penempatan Kandang -->
            <a href="index.php?page=penempatan_kandang_koordinator" class="quick-action-btn">
                📦 Penempatan Kandang
            </a>

            <!-- Tombol ke halaman Kelola Adopsi (warna merah supaya menonjol) -->
            <a href="index.php?page=transaksi_adopsi" class="quick-action-btn" style="border-color: #DE3B3B; color: #DE3B3B;">
                🤝 Kelola Adopsi
            </a>
        </div>

    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
