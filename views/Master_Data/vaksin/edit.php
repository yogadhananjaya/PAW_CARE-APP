<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Vaksin</h2>
        <a href="index.php?page=vaksin" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=vaksin_edit&id=<?= $data['id_vaksin'] ?>" method="POST">
            <div class="form-group">
                <label>Nama Vaksin</label>
                <input type="text" name="nama_vaksin" value="<?= htmlspecialchars($data['nama_vaksin']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>