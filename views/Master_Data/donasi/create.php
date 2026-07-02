<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Data Donasi / Keuangan</h2>
        <a href="index.php?page=donasi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 650px;">
        <?php if (!empty($error_validation)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_validation) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=donasi_create" method="POST">
            <!-- Nama Donatur -->
            <div class="form-group">
                <label>Nama Donatur / Instansi</label>
                <input type="text" name="nama_donatur" class="form-control" value="<?= htmlspecialchars($_POST['nama_donatur'] ?? '') ?>" required placeholder="Contoh: Budi Santoso / Yayasan ABC">
            </div>

             <div style="display:flex; gap:20px;">
                <!-- Nominal -->
                <div class="form-group" style="flex:1;">
                    <label>Nominal (Rupiah)</label>
                    <input type="number" name="nominal" class="form-control" value="<?= htmlspecialchars($_POST['nominal'] ?? '') ?>" required min="0" placeholder="Contoh: 500000">
                </div>

                <!-- Kategori -->
                <div class="form-group" style="flex:1;">
                    <label>Kategori Transaksi</label>
                    <select name="kategori" class="form-control" required>
                        <?php $post_kategori = $_POST['kategori'] ?? ''; ?>
                        <option value="Pemasukan" <?= $post_kategori == 'Pemasukan' ? 'selected' : '' ?>>Pemasukan (Donasi Masuk)</option>
                        <option value="Pengeluaran" <?= $post_kategori == 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran (Operasional)</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:20px;">
                <!-- Tanggal -->
                <div class="form-group" style="flex:1;">
                    <label>Tanggal Transaksi</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($_POST['tanggal'] ?? '') ?>" required>
                </div>

                <!-- Metode Pembayaran -->
                <div class="form-group" style="flex:1;">
                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control" required>
                        <?php $post_metode = $_POST['metode_pembayaran'] ?? ''; ?>
                        <option value="Transfer Bank" <?= $post_metode == 'Transfer Bank' ? 'selected' : '' ?>>Transfer Bank</option>
                        <option value="E-Wallet" <?= $post_metode == 'E-Wallet' ? 'selected' : '' ?>>E-Wallet</option>
                        <option value="Tunai" <?= $post_metode == 'Tunai' ? 'selected' : '' ?>>Tunai</option>
                    </select>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="form-group">
                <label>Keterangan <small style="color:#999;">(opsional)</small></label>
                <input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($_POST['keterangan'] ?? '') ?>" placeholder="Contoh: Donasi bulanan untuk pakan hewan">
            </div>

            <!-- Status Konfirmasi -->
            <div class="form-group">
                <label>Status Konfirmasi</label>
                <select name="status_konfirmasi" class="form-control">
                    <?php $post_status_konfirmasi = $_POST['status_konfirmasi'] ?? ''; ?>
                    <option value="Menunggu" <?= $post_status_konfirmasi == 'Menunggu' ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                    <option value="Dikonfirmasi" <?= $post_status_konfirmasi == 'Dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="Ditolak" <?= $post_status_konfirmasi == 'Ditolak' ? 'selected' : '' ?>>Ditolak / Batal</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>