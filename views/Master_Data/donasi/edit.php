<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Data Donasi</h2>
        <a href="index.php?page=donasi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=donasi_edit&id=<?= $data['id_donasi'] ?>" method="POST">
            <div class="form-group">
                <label>Nama Donatur / Instansi</label>
                <input type="text" name="nama_donatur" value="<?= htmlspecialchars($data['nama_donatur']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jumlah Donasi (Rupiah)</label>
                <input type="number" name="jumlah" value="<?= htmlspecialchars($data['jumlah']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Status Dana</label>
                <select name="status" class="form-control">
                    <option value="Pending" <?= $data['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Dikonfirmasi" <?= $data['status'] == 'Dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="Ditolak" <?= $data['status'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak / Batal</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>