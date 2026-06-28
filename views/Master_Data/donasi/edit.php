<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Data Donasi / Keuangan</h2>
        <a href="index.php?page=donasi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 650px;">
        <?php if (!empty($error_validation)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_validation) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=donasi_edit&id=<?= $data['id_donasi'] ?>" method="POST">
            <!-- Nama Donatur -->
            <div class="form-group">
                <label>Nama Donatur / Instansi</label>
                <input type="text" name="nama_donatur" value="<?= htmlspecialchars($_POST['nama_donatur'] ?? $data['nama_donatur']) ?>" class="form-control" required>
            </div>

             <div style="display:flex; gap:20px;">
                <!-- Nominal (bukan jumlah) -->
                <div class="form-group" style="flex:1;">
                    <label>Nominal (Rupiah)</label>
                    <input type="number" name="nominal" value="<?= htmlspecialchars($_POST['nominal'] ?? $data['nominal']) ?>" class="form-control" required min="0">
                </div>

                <!-- Kategori -->
                <div class="form-group" style="flex:1;">
                    <label>Kategori Transaksi</label>
                    <select name="kategori" class="form-control" required>
                        <?php $curr_kategori = $_POST['kategori'] ?? $data['kategori']; ?>
                        <option value="Pemasukan" <?= $curr_kategori == 'Pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                        <option value="Pengeluaran" <?= $curr_kategori == 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:20px;">
                <!-- Tanggal -->
                <div class="form-group" style="flex:1;">
                    <label>Tanggal Transaksi</label>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($_POST['tanggal'] ?? $data['tanggal']) ?>" class="form-control" required>
                </div>

                <!-- Metode Pembayaran -->
                <div class="form-group" style="flex:1;">
                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control" required>
                        <?php $curr_metode = $_POST['metode_pembayaran'] ?? $data['metode_pembayaran']; ?>
                        <option value="Transfer Bank" <?= $curr_metode == 'Transfer Bank' ? 'selected' : '' ?>>Transfer Bank</option>
                        <option value="E-Wallet" <?= $curr_metode == 'E-Wallet' ? 'selected' : '' ?>>E-Wallet</option>
                        <option value="Tunai" <?= $curr_metode == 'Tunai' ? 'selected' : '' ?>>Tunai</option>
                    </select>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="form-group">
                <label>Keterangan</label>
                <input type="text" name="keterangan" value="<?= htmlspecialchars($_POST['keterangan'] ?? $data['keterangan'] ?? '') ?>" class="form-control">
            </div>

            <!-- Status Konfirmasi (bukan status) -->
            <div class="form-group">
                <label>Status Konfirmasi</label>
                <select name="status_konfirmasi" class="form-control">
                    <?php $curr_status_konfirmasi = $_POST['status_konfirmasi'] ?? $data['status_konfirmasi']; ?>
                    <option value="Menunggu" <?= $curr_status_konfirmasi == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="Dikonfirmasi" <?= $curr_status_konfirmasi == 'Dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="Ditolak" <?= $curr_status_konfirmasi == 'Ditolak' ? 'selected' : '' ?>>Ditolak / Batal</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>