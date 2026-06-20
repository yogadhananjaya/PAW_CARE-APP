<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Dashboard Pegawai (Staff)</h2>
        <div class="user-badge" style="background:#3498db; color:#fff; padding:6px 15px; border-radius:20px; font-weight:600;">
            👤 <?= htmlspecialchars($_SESSION['username']) ?> (Staff Operasional)
        </div>
    </header>

    <section style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-bottom: 30px;">
        <div class="card" style="border-left:5px solid #3498db;">
            <h4 style="color:#7f8c8d;">Tugas Harian</h4>
            <p style="font-size:14px; margin-top:10px;">Gunakan menu Transaksi untuk memperbarui Riwayat Kesehatan hewan dan mengecek Jadwal Kunjungan hari ini.</p>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>