<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<?php global $pdo; $jenis_list = $pdo->query("SELECT id_jenis, nama_jenis FROM jenis_hewan ORDER BY nama_jenis")->fetchAll(); ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Vaksin</h2>
        <a href="index.php?page=vaksin" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <?php if (!empty($error_duplikat)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_duplikat) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=vaksin_edit&id=<?= $data['id_vaksin'] ?>" method="POST">
            <div class="form-group">
                <label>Nama Vaksin</label>
                <input type="text" name="nama_vaksin" value="<?= htmlspecialchars($_POST['nama_vaksin'] ?? $data['nama_vaksin']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jenis Hewan</label>
                <div style="display:flex; gap:20px; padding:10px 0;">
                    <?php 
                    $curr_jenis = isset($_POST['id_jenis']) ? $_POST['id_jenis'] : explode(',', $data['id_jenis_list'] ?? '');
                    ?>
                    <?php foreach ($jenis_list as $j): ?>
                        <label style="display:flex; align-items:center; gap:6px; font-size:13px; cursor:pointer;">
                            <input type="checkbox" name="id_jenis[]" value="<?= $j['id_jenis'] ?>" <?= in_array($j['id_jenis'], $curr_jenis) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($j['nama_jenis']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <small style="color:#64748b;">Kosongkan = berlaku untuk semua jenis hewan</small>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($_POST['deskripsi'] ?? $data['deskripsi']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <?php $curr_status = $_POST['status'] ?? $data['status']; ?>
                    <option value="Tersedia" <?= $curr_status == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Habis" <?= $curr_status == 'Habis' ? 'selected' : '' ?>>Habis</option>
                    <option value="Discontinue" <?= $curr_status == 'Discontinue' ? 'selected' : '' ?>>Discontinue</option>
                </select>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($_POST['stok'] ?? $data['stok'] ?? 0) ?>" min="0" required>
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>