<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Data Donasi</h2>
        <a href="index.php?page=donasi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=donasi_create" method="POST">
            <div class="form-group">
                <label>Nama Donatur / Instansi</label>
                <input type="text" name="nama_donatur" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jumlah Donasi (Rupiah)</label>
                <input type="number" name="jumlah" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Status Dana</label>
                <select name="status" class="form-control">
                    <option value="Pending">Pending (Menunggu Verifikasi Mutasi)</option>
                    <option value="Dikonfirmasi">Dikonfirmasi</option>
                    <option value="Ditolak">Ditolak / Batal</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>