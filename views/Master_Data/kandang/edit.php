<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Kandang</h2>
        <a href="index.php?page=kandang" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <?php if (!empty($error_duplikat)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_duplikat) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=kandang_edit&id=<?= $data['id_kandang'] ?>" method="POST">
            <!-- Kode Kandang -->
            <div class="form-group">
                <label>ID / Kode Kandang (Otomatis)</label>
                <input type="text" name="kode_kandang" value="<?= htmlspecialchars($data['kode_kandang']) ?>" class="form-control" readonly required>
            </div>
            <!-- Nama Kandang -->
            <div class="form-group">
                <label>Nama Kandang / Blok</label>
                <input type="text" name="nama_kandang" value="<?= htmlspecialchars($_POST['nama_kandang'] ?? $data['nama_kandang']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kapasitas (Ekor)</label>
                <input type="number" name="kapasitas" value="<?= htmlspecialchars($_POST['kapasitas'] ?? $data['kapasitas']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <?php $curr_status = $_POST['status'] ?? $data['status']; ?>
                    <option value="Tersedia" <?= $curr_status == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Penuh" <?= $curr_status == 'Penuh' ? 'selected' : '' ?>>Penuh</option>
                    <option value="Perbaikan" <?= $curr_status == 'Perbaikan' ? 'selected' : '' ?>>Perbaikan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>