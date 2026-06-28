<?php
// =============================================================================
// SIDEBAR KHUSUS UNTUK KOORDINATOR
// Hanya menampilkan menu Transaksi (4 modul) + shortcut
// =============================================================================

// Ambil halaman yang sedang dibuka (untuk menandai menu aktif)
$current_page = "";
if (isset($_GET['page'])) {
    $current_page = $_GET['page'];
}

// Ambil info user dari session
$username_session = "koordinator";
if (isset($_SESSION['username'])) {
    $username_session = $_SESSION['username'];
}

$role_session = "Koordinator";
if (isset($_SESSION['role'])) {
    $role_session = $_SESSION['role'];
}

$transaksi_pages = array('penempatan_kandang_koordinator', 'jadwal_kunjungan', 'transaksi_adopsi');

// -----------------------------------------------------------------------------
// Fungsi-fungsi pembantu untuk menentukan kelas CSS aktif
// -----------------------------------------------------------------------------

// Fungsi menandai menu tunggal yang aktif
function koordIsActive($current, $target) {
    if ($current == $target) {
        return 'active';
    }
    return '';
}

// Fungsi menandai dropdown yang sedang aktif (jika halaman anak dibuka)
function koordIsDropdownActive($current, $pages) {
    if (in_array($current, $pages)) {
        return 'active';
    }
    return '';
}

// Fungsi menampilkan isi dropdown (terbuka jika halaman anak aktif)
function koordIsDropdownOpen($current, $pages) {
    if (in_array($current, $pages)) {
        return 'show';
    }
    return '';
}

// Fungsi mengubah ikon panah dropdown
function koordGetCaretIcon($current, $pages) {
    if (in_array($current, $pages)) {
        return '▲';
    }
    return '▼';
}
?>
<!-- ===================================================================== -->
<!-- TAMPILAN SIDEBAR                                                    -->
<!-- ===================================================================== -->
<aside class="sidebar">

    <!-- Logo / Nama Aplikasi -->
    <div class="sidebar-title" style="justify-content: center; padding: 20px 15px;">
        <img src="assets/img/logo.png" alt="PawCare Logo" style="height: 75px; max-width: 100%; object-fit: contain; display: block;">
    </div>

    <!-- Bagian Menu Utama -->
    <div class="sidebar-section-title">Utama</div>
    <ul class="sidebar-links">
        <li>
            <a href="index.php?page=dashboard_koordinator" class="<?php echo koordIsActive($current_page, 'dashboard_koordinator'); ?>">
                📊 Dashboard Koordinator
            </a>
        </li>
    </ul>

    <!-- Bagian Menu Transaksi -->
    <div class="sidebar-section-title">Transaksi</div>
    <ul class="sidebar-links">
        <li>
            <a href="index.php?page=penempatan_kandang_koordinator" class="<?php echo koordIsActive($current_page, 'penempatan_kandang_koordinator'); ?>">
                📦 Penempatan Kandang
            </a>
        </li>
        <li>
            <a href="index.php?page=jadwal_kunjungan" class="<?php echo koordIsActive($current_page, 'jadwal_kunjungan'); ?>">
                📅 Jadwal Kunjungan
            </a>
        </li>
        <li>
            <a href="index.php?page=transaksi_adopsi" class="<?php echo koordIsActive($current_page, 'transaksi_adopsi'); ?>">
                🤝 Transaksi Adopsi
            </a>
        </li>
    </ul>

    <!-- Menu Operasional: Intake Hewan Baru -->
    <div class="sidebar-section-title">Operasional</div>
    <ul class="sidebar-links">
        <li>
            <a href="index.php?page=hewan" class="<?php echo koordIsActive($current_page, 'hewan'); ?>">
                🐾 Intake Hewan Baru
            </a>
        </li>
    </ul>

    <!-- Halaman Utama di atas User Profile -->
    <ul class="sidebar-links" style="margin-top: 20px; margin-bottom: 10px;">
        <li>
            <a href="index.php?page=landing" class="<?php echo koordIsActive($current_page, 'landing'); ?>">
                🏠 Halaman Utama
            </a>
        </li>
    </ul>

    <!-- Profil User di bagian bawah sidebar -->
    <div class="sidebar-user">
        <div class="user-info">
            <div class="avatar-sm">
                <?php
                $huruf_pertama = strtoupper(substr($username_session, 0, 1));
                echo htmlspecialchars($huruf_pertama);
                ?>
            </div>
            <div>
                <div class="user-name"><?php echo htmlspecialchars($username_session); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($role_session); ?></div>
            </div>
        </div>
        <!-- Tombol Logout -->
        <a href="index.php?page=logout" class="logout-icon" title="Keluar" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </a>
    </div>
</aside>
