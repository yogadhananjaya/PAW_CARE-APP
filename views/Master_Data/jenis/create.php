<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Jenis Hewan</h2>
        <a href="index.php?page=jenis" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=jenis_create" method="POST">
            <div class="form-group">
                <label>Nama Jenis Hewan</label>
                <input type="text" name="nama_jenis" class="form-control" placeholder="Contoh: Kucing, Anjing..." required autofocus>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>