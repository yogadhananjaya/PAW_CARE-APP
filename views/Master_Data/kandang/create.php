<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Kandang</h2>
        <a href="index.php?page=kandang" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <?php if (!empty($error_duplikat)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_duplikat) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=kandang_create" method="POST">
            <!-- Kode Kandang -->
            <div class="form-group">
                <label>ID / Kode Kandang (Otomatis)</label>
                <input type="text" name="kode_kandang" class="form-control" value="<?= htmlspecialchars($auto_kode) ?>" readonly required>
            </div>
            <!-- Nama Kandang -->
            <div class="form-group">
                <label>Nama Kandang / Blok</label>
                <input type="text" name="nama_kandang" class="form-control" placeholder="Contoh: Kandang Kucing Blok A" value="<?= htmlspecialchars($_POST['nama_kandang'] ?? '') ?>" required>
            </div>
            <!-- Jenis Hewan -->
            <div class="form-group">
                <label>Jenis Hewan</label>
                <select name="id_jenis" class="form-control" required>
                    <option value="">-- Pilih Jenis Hewan --</option>
                    <?php foreach ($jenis_list as $j): ?>
                        <option value="<?= $j['id_jenis'] ?>" <?= (($_POST['id_jenis'] ?? '') == $j['id_jenis']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($j['nama_jenis']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Kapasitas (Ekor)</label>
                <input type="number" name="kapasitas" class="form-control" value="<?= htmlspecialchars($_POST['kapasitas'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <?php $post_status = $_POST['status'] ?? ''; ?>
                    <option value="Tersedia" <?= $post_status == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Penuh" <?= $post_status == 'Penuh' ? 'selected' : '' ?>>Penuh</option>
                    <option value="Perbaikan" <?= $post_status == 'Perbaikan' ? 'selected' : '' ?>>Perbaikan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>