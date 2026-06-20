<aside class="sidebar">
    <div class="sidebar-title">🐾 PawCare Admin</div>
    <ul class="sidebar-links">
        <li><a href="index.php?page=dashboard_superadmin">🏠 Dashboard</a></li>
        
        <li>
            <div class="dropdown-toggle" onclick="toggleDropdown()">
                <span>🐾 Daftar Hewan</span>
                <span id="caret-icon">▼</span>
            </div>
            <ul class="sidebar-dropdown" id="hewanDropdown">
                <li><a href="index.php?page=hewan">└ Hewan</a></li>
                <li><a href="index.php?page=jenis">└ Jenis Hewan</a></li>
                <li><a href="index.php?page=ras">└ Ras</a></li>
            </ul>
        </li>
        
        <li><a href="index.php?page=kandang">🏢 Kandang</a></li>
        <li><a href="index.php?page=vaksin">💉 Vaksin</a></li>
        <li><a href="index.php?page=pengadopsi">👥 Pengadopsi (Adopter)</a></li>
        <li><a href="index.php?page=pengguna">🔒 Pengguna / Staff</a></li>
        <li><a href="index.php?page=donasi">💰 Donasi</a></li>

        <li>
            <div class="dropdown-toggle" onclick="toggleTransaksi()">
                <span>📋 Data Transaksi</span>
                <span id="trans-icon">▼</span>
            </div>
            <ul class="sidebar-dropdown" id="transDropdown">
                <li><a href="index.php?page=riwayat_kesehatan">└ Riwayat Kesehatan</a></li>
                <li><a href="index.php?page=penempatan_kandang">└ Penempatan Kandang</a></li>
                <li><a href="index.php?page=jadwal_kunjungan">└ Jadwal Kunjungan</a></li>
                <li><a href="index.php?page=transaksi_adopsi">└ Transaksi Adopsi</a></li>
            </ul>
        </li>
        
        <li><a href="index.php?page=report_donasi" target="_blank" style="color: #3498db;">📥 Laporan Donasi (PDF)</a></li>
        <li><a href="index.php?page=logout" style="color: #e74c3c;">🚪 Logout</a></li>
    </ul>
</aside>