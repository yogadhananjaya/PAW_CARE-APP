<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<!-- CSS Tambahan untuk Penyesuaian Tampilan Premium -->
<style>
    .stat-pemasukan::before { background-color: #2ecc71 !important; }
    .stat-pengeluaran::before { background-color: #e74c3c !important; }
    .stat-saldo::before { background-color: #3498db !important; }
</style>

<?php
// === HITUNG TOTAL PEMASUKAN & PENGELUARAN ===
// Kita membuat variabel awal bernilai 0
$total_pemasukan = 0;
$total_pengeluaran = 0;

// Kita loop semua data dari database
foreach ($data as $row) {
    // Jika kategori Pemasukan dan sudah Dikonfirmasi, kita tambahkan ke total pemasukan
    if ($row['kategori'] == 'Pemasukan') {
        if ($row['status_konfirmasi'] == 'Dikonfirmasi') {
            $total_pemasukan = $total_pemasukan + $row['nominal'];
        }
    }
    // Jika kategori Pengeluaran, kita tambahkan ke total pengeluaran
    if ($row['kategori'] == 'Pengeluaran') {
        $total_pengeluaran = $total_pengeluaran + $row['nominal'];
    }
}

// Saldo kas bersih adalah total pemasukan dikurangi pengeluaran
$saldo_bersih = $total_pemasukan - $total_pengeluaran;

// === PISAHKAN DATA TRANSAKSI ===
// Kita siapkan wadah kosong untuk menampung data
$donasi_pending = array();
$semua_transaksi = $data; // Semua data dimasukkan ke jurnal agar transparan

foreach ($data as $row) {
    // Masukkan ke daftar pending jika status konfirmasi masih Menunggu
    if ($row['status_konfirmasi'] == 'Menunggu') {
        $donasi_pending[] = $row;
    }
}
?>

<div class="main-wrapper">
    <!-- Header Halaman -->
    <header class="admin-header">
        <div>
            <h2>Data Donasi & Keuangan</h2>
            <p>Rekapitulasi pemasukan dan pengeluaran dana shelter PawCare.</p>
        </div>
    </header>

    <!-- Baris Tiga Kartu Statistik Atas (Pemasukan, Pengeluaran, Saldo) -->
    <div class="stats-grid">
        <!-- Kartu 1: Total Pemasukan -->
        <div class="stat-card stat-pemasukan">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="background-color: #e2fbe8; color: #2ecc71; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="6" width="20" height="12" rx="2" ry="2"></rect>
                        <circle cx="12" cy="12" r="2"></circle>
                        <line x1="6" y1="12" x2="6" y2="12"></line>
                        <line x1="18" y1="12" x2="18" y2="12"></line>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; margin: 0; font-weight: 700;">Total Pemasukan</h3>
                    <div style="color: #2ecc71; font-size: 28px; font-weight: 800; margin-top: 5px;">
                        Rp <?= number_format($total_pemasukan, 0, ',', '.') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu 2: Total Pengeluaran -->
        <div class="stat-card stat-pengeluaran">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="background-color: #fce4e4; color: #e74c3c; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                        <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; margin: 0; font-weight: 700;">Total Pengeluaran</h3>
                    <div style="color: #e74c3c; font-size: 28px; font-weight: 800; margin-top: 5px;">
                        Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu 3: Saldo Kas Bersih -->
        <div class="stat-card stat-saldo">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="background-color: #ebf3ff; color: #3182ce; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path>
                        <path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path>
                        <path d="M18 12a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4v-6h-4z"></path>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; margin: 0; font-weight: 700;">Saldo Kas Bersih</h3>
                    <div style="color: #3182ce; font-size: 28px; font-weight: 800; margin-top: 5px;">
                        Rp <?= number_format($saldo_bersih, 0, ',', '.') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tata Letak Dua Kolom Tengah (Pending & Form Pengeluaran) -->
    <div class="content-grid" style="margin-bottom: 25px;">
        <!-- Kiri: Konfirmasi Donasi Baru (Pending) -->
        <div class="content-panel" style="padding: 24px; border-radius: 16px; background-color: var(--putih); border: 1px solid var(--krem-gelap); box-shadow: 0 4px 15px rgba(0,0,0,0.01);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--krem-gelap); padding-bottom: 12px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--hitam); display: flex; align-items: center; gap: 8px;">
                    <span>🏆</span> Konfirmasi Donasi Baru (Pending)
                </h3>
            </div>
            
            <table class="crud-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Donatur</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Bukti</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($donasi_pending)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                                Tidak ada donasi baru yang menunggu konfirmasi.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($donasi_pending as $row): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_donatur']) ?></strong>
                                </td>
                                <td style="color: #2ecc71; font-weight: 700;">Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['metode_pembayaran'] ?? '-') ?></td>
                                <td>
                                    <?php if (!empty($row['url_bukti'])): ?>
                                        <a href="assets/img/bukti/<?= htmlspecialchars($row['url_bukti']) ?>" target="_blank" class="btn" style="padding: 4px 10px; font-size: 11px; border-radius: 4px; border: 1px solid var(--hitam); font-weight: 600;">Lihat Bukti</a>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 12px;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="index.php?page=donasi_confirm&id=<?= $row['id_donasi'] ?>" class="btn" style="background-color: #2ecc71; color: var(--putih); padding: 6px 12px; font-size: 12px; border-radius: 6px; font-weight: 700;">Setujui</a>
                                        <a href="index.php?page=donasi_reject&id=<?= $row['id_donasi'] ?>" class="btn" style="background-color: #e74c3c; color: var(--putih); padding: 6px 12px; font-size: 12px; border-radius: 6px; font-weight: 700;" onclick="return confirm('Tolak donasi ini?');">Tolak</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Kanan: Input Pengeluaran Kas -->
        <div class="content-panel" style="padding: 24px; border-radius: 16px; background-color: var(--putih); border: 1px solid var(--krem-gelap); box-shadow: 0 4px 15px rgba(0,0,0,0.01);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--krem-gelap); padding-bottom: 12px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--hitam); display: flex; align-items: center; gap: 8px;">
                    <span>📉</span> Input Pengeluaran Kas
                </h3>
            </div>
            
            <form action="index.php?page=donasi_create" method="POST">
                <!-- Data Default untuk Pengeluaran -->
                <input type="hidden" name="kategori" value="Pengeluaran">
                <input type="hidden" name="nama_donatur" value="Operasional">
                <input type="hidden" name="metode_pembayaran" value="Tunai">
                <input type="hidden" name="status_konfirmasi" value="Dikonfirmasi">
                
                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 700; color: var(--hitam);">Nominal Pengeluaran (Rp)</label>
                    <input type="number" name="nominal" class="form-control" placeholder="Contoh: 150000" required min="1" style="border: 1px solid #E2DCD0; border-radius: 8px; padding: 10px 12px; font-size: 13px;">
                </div>
                
                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 700; color: var(--hitam);">Keterangan Pengeluaran</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Beli Pakan Kucing 10kg, Bayar Listrik" required style="border: 1px solid #E2DCD0; border-radius: 8px; padding: 10px 12px; font-size: 13px;">
                </div>
                
                <div class="form-group">
                    <label style="font-size: 13px; font-weight: 700; color: var(--hitam);">Tanggal Transaksi</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required style="border: 1px solid #E2DCD0; border-radius: 8px; padding: 10px 12px; font-size: 13px;">
                </div>
                
                <button type="submit" class="btn" style="background-color: #DE3B3B; color: var(--putih); width: 100%; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 14px; margin-top: 10px; cursor: pointer;">Catat Pengeluaran</button>
            </form>
        </div>
    </div>

    <!-- Buku Jurnal Keuangan Bawah -->
    <div class="card" style="padding: 24px; border-radius: 16px; background-color: var(--putih); border: 1px solid var(--krem-gelap); box-shadow: 0 4px 15px rgba(0,0,0,0.01);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--krem-gelap); padding-bottom: 12px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--hitam); display: flex; align-items: center; gap: 8px;">
                <span>📝</span> Buku Jurnal Keuangan Shelter (Semua Transaksi)
            </h3>
            <!-- Tombol Tambah Pemasukan secara manual -->
            <a href="index.php?page=donasi_create" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px; font-weight: 700; border-radius: 30px;">+ Catat Pemasukan Manual</a>
        </div>
        
        <table class="crud-table" style="width: 100%;">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan / Transaksi</th>
                    <th>Nominal</th>
                    <th>Metode / Sumber</th>
                    <th>Status Konfirmasi</th>
                    <th style="text-align: center; width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($semua_transaksi)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                            Belum ada data transaksi tercatat.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($semua_transaksi as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['kode_donasi'] ?? '') ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                            <td>
                                <span style="padding:4px 10px; border-radius:20px; font-size:11px; font-weight:bold; 
                                    background-color: <?= $row['kategori'] == 'Pemasukan' ? '#e2fbe8; color:#2ecc71;' : '#fce4e4; color:#e74c3c;' ?>">
                                    <?= strtoupper($row['kategori']) ?>
                                </span>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($row['keterangan'] ?? 'Tanpa Keterangan') ?></strong>
                                <br><small style="color: #7f8c8d; font-size: 11px;">Dari/Untuk: <?= htmlspecialchars($row['nama_donatur']) ?></small>
                            </td>
                            <td style="font-weight: 700; color: <?= $row['kategori'] == 'Pemasukan' ? '#2ecc71;' : '#e74c3c;' ?>">
                                <?= $row['kategori'] == 'Pemasukan' ? '+ ' : '- ' ?>Rp <?= number_format($row['nominal'], 0, ',', '.') ?>
                            </td>
                            <td><?= htmlspecialchars($row['metode_pembayaran'] ?? '-') ?></td>
                            <td>
                                <?php if ($row['status_konfirmasi'] == 'Dikonfirmasi'): ?>
                                    <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; background-color: #e2fbe8; color: #2ecc71;">Dikonfirmasi</span>
                                <?php elseif ($row['status_konfirmasi'] == 'Ditolak'): ?>
                                    <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; background-color: #fce4e4; color: #e74c3c;">Ditolak</span>
                                <?php else: ?>
                                    <span style="padding:4px 8px; border-radius:10px; font-size:12px; font-weight:bold; background-color: #fff3cd; color: #f1c40f;">Menunggu</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 4px; justify-content: center;">
                                    <a href="index.php?page=donasi_edit&id=<?= $row['id_donasi'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                    </a>
                                    <a href="index.php?page=donasi_delete&id=<?= $row['id_donasi'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data transaksi ini?');" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>