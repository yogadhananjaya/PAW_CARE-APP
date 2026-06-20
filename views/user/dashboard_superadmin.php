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
            <div class="value">15</div>
        </div>
        <div class="stat-card">
            <h3>Total Donasi Bulan Ini</h3>
            <div class="value" style="font-size: 24px; margin-top:10px;">Rp 4.500.000</div>
        </div>
    </div>

    <div class="content-grid">
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

        <div class="content-panel">
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

<?php include __DIR__ . '/../layouts/footer.php'; ?>