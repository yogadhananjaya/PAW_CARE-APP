<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Kandang</h2>
        <a href="index.php?page=kandang" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=kandang_create" method="POST">
            <div class="form-group">
                <label>Nama / Kode Kandang</label>
                <input type="text" name="nama_kandang" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kapasitas (Ekor)</label>
                <input type="number" name="kapasitas" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Tersedia">Tersedia</option>
                    <option value="Penuh">Penuh</option>
                    <option value="Perbaikan">Perbaikan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>