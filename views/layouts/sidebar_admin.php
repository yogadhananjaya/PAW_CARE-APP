<!-- Container utama untuk Sidebar Panel Admin -->
<aside class="sidebar">
    <!-- Logo / Nama Aplikasi -->
    <div class="sidebar-title" style="justify-content: center; padding: 20px 15px;">
        <img src="assets/img/logo.png" alt="PawCare Logo"
            style="height: 75px; max-width: 100%; object-fit: contain; display: block;">
    </div>

    <!-- KATEGORI UTAMA -->
    <div class="sidebar-section-title">Utama</div>
    <ul class="sidebar-links">
        <li>
            <?php if (($_SESSION['role'] ?? '') === 'SuperAdmin'): ?>
                <a href="index.php?page=dashboard_superadmin"
                    class="<?php if (!isset($_GET['page']) || $_GET['page'] == 'dashboard_superadmin')
                        echo 'active'; ?>">
                    📊 Dashboard
                </a>
            <?php elseif (($_SESSION['role'] ?? '') === 'Koordinator'): ?>
                <a href="index.php?page=dashboard_koordinator"
                    class="<?php if (!isset($_GET['page']) || $_GET['page'] == 'dashboard_koordinator')
                        echo 'active'; ?>">
                    📊 Dashboard Koordinator
                </a>
            <?php else: ?>
                <a href="index.php?page=dashboard_staff"
                    class="<?php if (!isset($_GET['page']) || $_GET['page'] == 'dashboard_staff')
                        echo 'active'; ?>">
                    📊 Dashboard Perawat
                </a>
            <?php endif; ?>
        </li>
    </ul>

    <!-- KATEGORI DATA MASTER (Hanya untuk SuperAdmin) -->
    <?php if (($_SESSION['role'] ?? '') === 'SuperAdmin'): ?>
    <div class="sidebar-section-title">Data Master</div>
    <ul class="sidebar-links">
        <li>
            <!-- Tombol Dropdown Daftar Hewan -->
            <div class="dropdown-toggle <?php if (isset($_GET['page']) && in_array($_GET['page'], ['hewan', 'jenis', 'ras']))
                echo 'active'; ?>"
                onclick="toggleDropdown()">
                <span>🐾 Daftar Hewan</span>
                <span
                    id="caret-icon"><?php echo (isset($_GET['page']) && in_array($_GET['page'], ['hewan', 'jenis', 'ras'])) ? '▲' : '▼'; ?></span>
            </div>
            <ul class="sidebar-dropdown <?php if (isset($_GET['page']) && in_array($_GET['page'], ['hewan', 'jenis', 'ras']))
                echo 'show'; ?>"
                id="hewanDropdown">
                <li><a href="index.php?page=hewan"
                        class="<?php if (isset($_GET['page']) && $_GET['page'] == 'hewan')
                            echo 'active'; ?>"><span
                            class="sub-dot">🐕</span> Hewan</a></li>
                <li><a href="index.php?page=jenis"
                        class="<?php if (isset($_GET['page']) && $_GET['page'] == 'jenis')
                            echo 'active'; ?>"><span
                            class="sub-dot">📋</span> Jenis Hewan</a></li>
                <li><a href="index.php?page=ras"
                        class="<?php if (isset($_GET['page']) && $_GET['page'] == 'ras')
                            echo 'active'; ?>"><span
                            class="sub-dot">🏷️</span> Ras</a></li>
            </ul>
        </li>
        <li>
            <!-- Navigasi ke Data Kandang -->
            <a href="index.php?page=kandang"
                class="<?php if (isset($_GET['page']) && $_GET['page'] == 'kandang')
                    echo 'active'; ?>">
                🏢 Kandang
            </a>
        </li>
        <li>
            <!-- Navigasi ke Data Vaksin -->
            <a href="index.php?page=vaksin"
                class="<?php if (isset($_GET['page']) && $_GET['page'] == 'vaksin')
                    echo 'active'; ?>">
                💉 Vaksin
            </a>
        </li>
        <li>
            <!-- Navigasi ke Data Pengadopsi -->
            <a href="index.php?page=pengadopsi"
                class="<?php if (isset($_GET['page']) && $_GET['page'] == 'pengadopsi')
                    echo 'active'; ?>">
                👥 Pengadopsi
            </a>
        </li>
        <li>
            <!-- Navigasi ke Data Akun Pengguna -->
            <a href="index.php?page=pengguna"
                class="<?php if (isset($_GET['page']) && $_GET['page'] == 'pengguna')
                    echo 'active'; ?>">
                🔒 Pengguna
            </a>
        </li>
        <li>
            <!-- Navigasi ke Data Donasi -->
            <a href="index.php?page=donasi"
                class="<?php if (isset($_GET['page']) && $_GET['page'] == 'donasi')
                    echo 'active'; ?>">
                💰 Donasi
            </a>
        </li>
    </ul>
    <?php endif; ?>

    <!-- KATEGORI DATA TRANSAKSI -->
    <div class="sidebar-section-title">Transaksi</div>
    <ul class="sidebar-links">
        <?php if (($_SESSION['role'] ?? '') === 'Koordinator'): ?>
            <!-- ponytail: Khusus Koordinator tampil langsung tanpa dropdown -->
            <li>
                <a href="index.php?page=riwayat_kesehatan" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'riwayat_kesehatan') echo 'active'; ?>">
                    🩺 Riwayat Kesehatan
                </a>
            </li>
            <li>
                <a href="index.php?page=penempatan_kandang_koordinator" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'penempatan_kandang_koordinator') echo 'active'; ?>">
                    📦 Penempatan Kandang
                </a>
            </li>
            <li>
                <a href="index.php?page=jadwal_kunjungan" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'jadwal_kunjungan') echo 'active'; ?>">
                    📅 Jadwal Kunjungan
                </a>
            </li>
            <li>
                <a href="index.php?page=transaksi_adopsi" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'transaksi_adopsi') echo 'active'; ?>">
                    🤝 Transaksi Adopsi
                </a>
            </li>
        <?php elseif (in_array($_SESSION['role'] ?? '', ['Perawat', 'Perawat Hewan'])): ?>
            <!-- ponytail: Khusus Perawat/Staff tampil langsung tanpa dropdown, hanya modul relevan harian -->
            <li>
                <a href="index.php?page=riwayat_kesehatan" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'riwayat_kesehatan') echo 'active'; ?>">
                    🩺 Riwayat Kesehatan
                </a>
            </li>
            <li>
                <a href="index.php?page=penempatan_kandang_koordinator" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'penempatan_kandang_koordinator') echo 'active'; ?>">
                    📦 Penempatan Kandang 
                </a>
            </li>
            <li>
                <a href="index.php?page=hewan" class="<?php if (isset($_GET['page']) && $_GET['page'] == 'hewan') echo 'active'; ?>">
                    🐕 Katalog Hewan
                </a>
            </li>
        <?php else: ?>
            <!-- Tampilan biasa dengan dropdown untuk SuperAdmin -->
            <li>
                <div class="dropdown-toggle <?php if (isset($_GET['page']) && in_array($_GET['page'], ['riwayat_kesehatan', 'penempatan_kandang_koordinator', 'jadwal_kunjungan', 'transaksi_adopsi']))
                    echo 'active'; ?>"
                    onclick="toggleTransaksi()">
                    <span>📋 Data Transaksi</span>
                    <span
                        id="trans-icon"><?php echo (isset($_GET['page']) && in_array($_GET['page'], ['riwayat_kesehatan', 'penempatan_kandang_koordinator', 'jadwal_kunjungan', 'transaksi_adopsi'])) ? '▲' : '▼'; ?></span>
                </div>
                <ul class="sidebar-dropdown <?php if (isset($_GET['page']) && in_array($_GET['page'], ['riwayat_kesehatan', 'penempatan_kandang_koordinator', 'jadwal_kunjungan', 'transaksi_adopsi']))
                    echo 'show'; ?>"
                    id="transDropdown">
                    <li><a href="index.php?page=riwayat_kesehatan"
                            class="<?php if (isset($_GET['page']) && $_GET['page'] == 'riwayat_kesehatan')
                                echo 'active'; ?>"><span
                                class="sub-dot">🩺</span> Riwayat Kesehatan</a></li>
                    <li><a href="index.php?page=penempatan_kandang_koordinator"
                            class="<?php if (isset($_GET['page']) && $_GET['page'] == 'penempatan_kandang_koordinator')
                                echo 'active'; ?>"><span
                                class="sub-dot">📦</span> Penempatan Kandang</a></li>
                    <li><a href="index.php?page=jadwal_kunjungan"
                            class="<?php if (isset($_GET['page']) && $_GET['page'] == 'jadwal_kunjungan')
                                echo 'active'; ?>"><span
                                class="sub-dot">📅</span> Jadwal Kunjungan</a></li>
                    <li><a href="index.php?page=transaksi_adopsi"
                            class="<?php if (isset($_GET['page']) && $_GET['page'] == 'transaksi_adopsi')
                                echo 'active'; ?>"><span
                                class="sub-dot">🤝</span> Transaksi Adopsi</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (($_SESSION['role'] ?? '') === 'SuperAdmin'): ?>
            <li>
                <!-- Link Cetak PDF Laporan Donasi -->
                <a href="index.php?page=report_donasi" target="_blank" style="color: #3498db;">
                    📥 Laporan Donasi (PDF)
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <!-- Halaman Utama di atas User Profile -->
    <ul class="sidebar-links" style="margin-top: 15px;">
        <li>
            <a href="index.php?page=landing"
                class="<?php if (isset($_GET['page']) && $_GET['page'] == 'landing')
                    echo 'active'; ?>">
                🏠 Halaman Utama
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
        <a href="index.php?page=logout" class="logout-icon" title="Logout"
            onclick="return confirm('Apakah Anda yakin ingin keluar?');">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </a>
    </div>
</aside>