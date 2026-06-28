<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Ras Hewan</h2>
        <a href="index.php?page=ras" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <?php if (!empty($error_duplikat)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_duplikat) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=ras_edit&id=<?= $data['id_ras'] ?>" method="POST">
            <div class="form-group">
                <label>Jenis Hewan</label>
                <select name="id_jenis" class="form-control" required>
                    <?php $selected_jenis = $_POST['id_jenis'] ?? $data['id_jenis']; ?>
                    <?php foreach($jenis as $j): ?>
                        <option value="<?= $j['id_jenis'] ?>" <?= $j['id_jenis'] == $selected_jenis ? 'selected' : '' ?>><?= $j['nama_jenis'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nama Ras</label>
                <input type="text" name="nama_ras" value="<?= htmlspecialchars($_POST['nama_ras'] ?? $data['nama_ras']) ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>