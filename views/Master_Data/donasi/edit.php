<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Data Donasi / Keuangan</h2>
        <a href="index.php?page=donasi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 650px;">
        <form action="index.php?page=donasi_edit&id=<?= $data['id_donasi'] ?>" method="POST">
            <!-- Hubungkan ke Pengadopsi (Opsional) -->
            <div class="form-group">
                <label>Hubungkan ke Pengadopsi <small style="color:#999;">(Opsional)</small></label>
                <select name="id_pengadopsi" id="id_pengadopsi" class="form-control" onchange="updateDonaturName()">
                    <option value="">-- Bukan Pengadopsi Terdaftar --</option>
                    <?php foreach ($p as $adopter): ?>
                        <option value="<?= $adopter['id_pengadopsi'] ?>" <?= $data['id_pengadopsi'] == $adopter['id_pengadopsi'] ? 'selected' : '' ?> data-nama="<?= htmlspecialchars($adopter['nama']) ?>">
                            <?= htmlspecialchars($adopter['nama']) ?> (<?= htmlspecialchars($adopter['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nama Donatur -->
            <div class="form-group">
                <label>Nama Donatur / Instansi</label>
                <input type="text" name="nama_donatur" value="<?= htmlspecialchars($data['nama_donatur']) ?>" class="form-control" required>
            </div>

            <script>
            function updateDonaturName() {
                var select = document.getElementById('id_pengadopsi');
                var selectedOption = select.options[select.selectedIndex];
                var nameInput = document.getElementsByName('nama_donatur')[0];
                if (selectedOption.value !== "") {
                    nameInput.value = selectedOption.getAttribute('data-nama');
                }
            }
            </script>

            <div style="display:flex; gap:20px;">
                <!-- Nominal (bukan jumlah) -->
                <div class="form-group" style="flex:1;">
                    <label>Nominal (Rupiah)</label>
                    <input type="number" name="nominal" value="<?= htmlspecialchars($data['nominal']) ?>" class="form-control" required min="0">
                </div>

                <!-- Kategori -->
                <div class="form-group" style="flex:1;">
                    <label>Kategori Transaksi</label>
                    <select name="kategori" class="form-control" required>
                        <option value="Pemasukan" <?= $data['kategori'] == 'Pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                        <option value="Pengeluaran" <?= $data['kategori'] == 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:20px;">
                <!-- Tanggal -->
                <div class="form-group" style="flex:1;">
                    <label>Tanggal Transaksi</label>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>" class="form-control" required>
                </div>

                <!-- Metode Pembayaran -->
                <div class="form-group" style="flex:1;">
                    <label>Metode Pembayaran</label>
                    <input type="text" name="metode_pembayaran" value="<?= htmlspecialchars($data['metode_pembayaran'] ?? '') ?>" class="form-control">
                </div>
            </div>

            <!-- Keterangan -->
            <div class="form-group">
                <label>Keterangan</label>
                <input type="text" name="keterangan" value="<?= htmlspecialchars($data['keterangan'] ?? '') ?>" class="form-control">
            </div>

            <!-- Status Konfirmasi (bukan status) -->
            <div class="form-group">
                <label>Status Konfirmasi</label>
                <select name="status_konfirmasi" class="form-control">
                    <option value="Menunggu" <?= $data['status_konfirmasi'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="Dikonfirmasi" <?= $data['status_konfirmasi'] == 'Dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="Ditolak" <?= $data['status_konfirmasi'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak / Batal</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>