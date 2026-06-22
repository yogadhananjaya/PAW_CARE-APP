<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Transaksi Adopsi</h2>
        <a href="index.php?page=transaksi_adopsi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=transaksi_adopsi_edit&id=<?= $data['id_adopsi'] ?>" method="POST">
            <div class="form-group">
                <label>Pengadopsi</label>
                <select name="id_pengadopsi" class="form-control" required>
                    <?php foreach($a as $ad): ?><option value="<?= $ad['id_pengadopsi'] ?>" <?= $data['id_pengadopsi']==$ad['id_pengadopsi']?'selected':'' ?>><?= $ad['nama'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Hewan yang Diadopsi</label>
                <select name="id_hewan" class="form-control" required>
                    <?php foreach($h as $hw): ?><option value="<?= $hw['id_hewan'] ?>" <?= $data['id_hewan']==$hw['id_hewan']?'selected':'' ?>><?= $hw['nama_hewan'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Adopsi</label>
                <input type="date" name="tanggal_adopsi" value="<?= $data['tanggal_adopsi'] ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Status Kontrak</label>
                <select name="status_kontrak" class="form-control">
                    <option value="Draft" <?= $data['status_kontrak']=='Draft'?'selected':'' ?>>Draft (Belum Ditandatangani)</option>
                    <option value="Ditandatangani" <?= $data['status_kontrak']=='Ditandatangani'?'selected':'' ?>>Ditandatangani</option>
                    <option value="Aktif" <?= $data['status_kontrak']=='Aktif'?'selected':'' ?>>Aktif (Final)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tanda Tangan Adopter (Base64)</label>
                <input type="text" name="ttd_adopter" value="<?= htmlspecialchars($data['ttd_adopter'] ?? '') ?>" class="form-control">
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update Transaksi</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>