<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Vaksin</h2>
        <a href="index.php?page=vaksin" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=vaksin_create" method="POST">
            <div class="form-group">
                <label>Nama Vaksin</label>
                <input type="text" name="nama_vaksin" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Habis">Habis</option>
                    <option value="Discontinue">Discontinue</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>