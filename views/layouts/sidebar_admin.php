<!-- Container utama untuk Sidebar Panel Admin -->
<aside class="sidebar">
    <!-- Judul / Nama Aplikasi -->
    <div class="sidebar-title">
        <span class="paw-icon">🐾</span> PAW SHELTER
    </div>
    
    <!-- KATEGORI UTAMA -->
    <div class="sidebar-section-title">Utama</div>
    <ul class="sidebar-links">
        <li>
            <!-- Jika parameter page tidak di-set atau bernilai 'dashboard_superadmin', maka menu Dashboard diberi kelas 'active' (berwarna hitam) -->
            <a href="index.php?page=dashboard_superadmin" class="<?php if (!isset($_GET['page']) || $_GET['page'] == 'dashboard_superadmin') echo 'active'; ?>">
                🏠 Dashboard
            </a>
        </li>
    </ul>

    <!-- KATEGORI DATA MASTER -->
    <div class="sidebar-section-title">Data Master</div>
    <ul class="sidebar-links">
        <li>
            <!-- Tombol Dropdown Daftar Hewan. Otomatis 'active' jika salah satu dari halaman anak (hewan, jenis, ras) sedang dibuka -->
            <div class="dropdown-toggle <?php if (isset($_GET['page']) && in_array($_GET['page'], ['hewan', 'jenis', 'ras'])) echo 'active'; ?>" onclick="toggleDropdown()">
                <span>🐾 Daftar Hewan</span>
                <!-- Mengubah ikon segitiga cakar ke atas (▲) jika dropdown terbuka, dan ke bawah (▼) jika tertutup -->
                <span id="caret-icon"><?php echo (isset($_GET['page']) && in_array($_GET['page'], ['hewan', 'jenis', 'ras'])) ? '▲' : '▼'; ?></span>
            </div>
            <!-- Menggunakan kelas 'show' secara otomatis jika salah satu halaman anak (hewan, jenis, ras) aktif agar menu anak tidak tertutup secara tidak terduga -->
            <ul class="sidebar-dropdown <?php if (isset($_GET['page']) && in_array($_GET['page'], ['hewan', 'jenis', 'ras'])) echo 'show'; ?>" id="hewanDropdown">
                <li><a href="index.php?page=hewan" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'hewan') echo 'active'; ?>"><span class="sub-dot">🐕</span> Hewan</a></li>
                <li><a href="index.php?page=jenis" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'jenis') echo 'active'; ?>"><span class="sub-dot">📋</span> Jenis Hewan</a></li>
                <li><a href="index.php?page=ras" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'ras') echo 'active'; ?>"><span class="sub-dot">🏷️</span> Ras</a></li>
            </ul>
        </li>
        <li>
            <!-- Navigasi ke Data Kandang -->
            <a href="index.php?page=kandang" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'kandang') echo 'active'; ?>">
                🏢 Kandang
            </a>
        </li>
        <li>
            <!-- Navigasi ke Data Vaksin -->
            <a href="index.php?page=vaksin" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'vaksin') echo 'active'; ?>">
                💉 Vaksin
            </a>
        </li>
        <li>
            <!-- Navigasi ke Data Pengadopsi -->
            <a href="index.php?page=pengadopsi" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'pengadopsi') echo 'active'; ?>">
                👥 Pengadopsi
            </a>
        </li>
        <li>
            <!-- Navigasi ke Data Akun Pengguna (Koordinator / Perawat) -->
            <a href="index.php?page=pengguna" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'pengguna') echo 'active'; ?>">
                🔒 Pengguna
            </a>
        </li>
        <li>
            <!-- Navigasi ke Data Donasi -->
            <a href="index.php?page=donasi" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'donasi') echo 'active'; ?>">
                💰 Donasi
            </a>
        </li>
    </ul>

    <!-- KATEGORI DATA TRANSAKSI -->
    <div class="sidebar-section-title">Transaksi</div>
    <ul class="sidebar-links">
        <li>
            <!-- Tombol Dropdown Data Transaksi. Otomatis 'active' jika halaman anak (riwayat_kesehatan, penempatan_kandang, jadwal_kunjungan, transaksi_adopsi) aktif -->
            <div class="dropdown-toggle <?php if (isset($_GET['page']) && in_array($_GET['page'], ['riwayat_kesehatan', 'penempatan_kandang', 'jadwal_kunjungan', 'transaksi_adopsi'])) echo 'active'; ?>" onclick="toggleTransaksi()">
                <span>📋 Data Transaksi</span>
                <!-- Mengubah ikon segitiga cakar ke atas (▲) jika dropdown terbuka, dan ke bawah (▼) jika tertutup -->
                <span id="trans-icon"><?php echo (isset($_GET['page']) && in_array($_GET['page'], ['riwayat_kesehatan', 'penempatan_kandang', 'jadwal_kunjungan', 'transaksi_adopsi'])) ? '▲' : '▼'; ?></span>
            </div>
            <!-- Menggunakan kelas 'show' jika salah satu halaman anak transaksi aktif agar dropdown tetap terbuka setelah perpindahan halaman -->
            <ul class="sidebar-dropdown <?php if (isset($_GET['page']) && in_array($_GET['page'], ['riwayat_kesehatan', 'penempatan_kandang', 'jadwal_kunjungan', 'transaksi_adopsi'])) echo 'show'; ?>" id="transDropdown">
                <li><a href="index.php?page=riwayat_kesehatan" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'riwayat_kesehatan') echo 'active'; ?>"><span class="sub-dot">🩺</span> Riwayat Kesehatan</a></li>
                <li><a href="index.php?page=penempatan_kandang" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'penempatan_kandang') echo 'active'; ?>"><span class="sub-dot">📦</span> Penempatan Kandang</a></li>
                <li><a href="index.php?page=jadwal_kunjungan" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'jadwal_kunjungan') echo 'active'; ?>"><span class="sub-dot">📅</span> Jadwal Kunjungan</a></li>
                <li><a href="index.php?page=transaksi_adopsi" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'transaksi_adopsi') echo 'active'; ?>"><span class="sub-dot">🤝</span> Transaksi Adopsi</a></li>
            </ul>
        </li>
        <li>
            <!-- Link Cetak PDF Laporan Donasi -->
            <a href="index.php?page=report_donasi" target="_blank" style="color: #3498db;">
                📥 Laporan Donasi (PDF)
            </a>
        </li>
    </ul>

    <!-- KARTU PROFIL PENGGUNA (Tampil di bagian bawah sidebar) -->
    <div class="sidebar-user">
        <div class="user-info">
            <!-- Menampilkan inisial huruf besar pertama dari username user yang sedang login -->
            <div class="avatar-sm">
                <?php 
                $userLetter = isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'S';
                echo htmlspecialchars($userLetter);
                ?>
            </div>
            <!-- Nama & Role User -->
            <div>
                <div class="user-name"><?= htmlspecialchars($_SESSION['username'] ?? 'superadmin') ?></div>
                <div class="user-role"><?= htmlspecialchars($_SESSION['role'] ?? 'Superadmin') ?></div>
            </div>
        </div>
        <!-- Tombol Logout (Berwarna merah dengan konfirmasi) -->
        <a href="index.php?page=logout" class="logout-icon" title="Logout" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </a>
    </div>
</aside>