<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Data Donasi / Keuangan</h2>
        <a href="index.php?page=donasi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 650px;">
        <form action="index.php?page=donasi_create" method="POST">
            <!-- Hubungkan ke Pengadopsi (Opsional) -->
            <div class="form-group">
                <label>Hubungkan ke Pengadopsi <small style="color:#999;">(Opsional)</small></label>
                <select name="id_pengadopsi" id="id_pengadopsi" class="form-control" onchange="updateDonaturName()">
                    <option value="">-- Bukan Pengadopsi Terdaftar --</option>
                    <?php foreach ($p as $adopter): ?>
                        <option value="<?= $adopter['id_pengadopsi'] ?>" data-nama="<?= htmlspecialchars($adopter['nama']) ?>">
                            <?= htmlspecialchars($adopter['nama']) ?> (<?= htmlspecialchars($adopter['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nama Donatur -->
            <div class="form-group">
                <label>Nama Donatur / Instansi</label>
                <input type="text" name="nama_donatur" class="form-control" required placeholder="Contoh: Budi Santoso / Yayasan ABC">
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
                <!-- Nominal -->
                <div class="form-group" style="flex:1;">
                    <label>Nominal (Rupiah)</label>
                    <input type="number" name="nominal" class="form-control" required min="0" placeholder="Contoh: 500000">
                </div>

                <!-- Kategori -->
                <div class="form-group" style="flex:1;">
                    <label>Kategori Transaksi</label>
                    <select name="kategori" class="form-control" required>
                        <option value="Pemasukan">Pemasukan (Donasi Masuk)</option>
                        <option value="Pengeluaran">Pengeluaran (Operasional)</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:20px;">
                <!-- Tanggal -->
                <div class="form-group" style="flex:1;">
                    <label>Tanggal Transaksi</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <!-- Metode Pembayaran -->
                <div class="form-group" style="flex:1;">
                    <label>Metode Pembayaran</label>
                    <input type="text" name="metode_pembayaran" class="form-control" placeholder="Contoh: Transfer BCA, Tunai">
                </div>
            </div>

            <!-- Keterangan -->
            <div class="form-group">
                <label>Keterangan <small style="color:#999;">(opsional)</small></label>
                <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Donasi bulanan untuk pakan hewan">
            </div>

            <!-- Status Konfirmasi -->
            <div class="form-group">
                <label>Status Konfirmasi</label>
                <select name="status_konfirmasi" class="form-control">
                    <option value="Menunggu">Menunggu Konfirmasi</option>
                    <option value="Dikonfirmasi">Dikonfirmasi</option>
                    <option value="Ditolak">Ditolak / Batal</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>