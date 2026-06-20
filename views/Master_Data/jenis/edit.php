<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Jenis Hewan</h2>
        <a href="index.php?page=jenis" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=jenis_edit&id=<?= $data['id_jenis'] ?>" method="POST">
            <div class="form-group">
                <label>Nama Jenis Hewan</label>
                <input type="text" name="nama_jenis" class="form-control" value="<?= htmlspecialchars($data['nama_jenis']) ?>" required>
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>