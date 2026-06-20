<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Transaksi Adopsi</h2>
        <a href="index.php?page=transaksi_adopsi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=transaksi_adopsi_edit&id=<?= $data['id_transaksi'] ?>" method="POST">
            <div class="form-group">
                <label>Pengadopsi</label>
                <select name="id_pengadopsi" class="form-control" required>
                    <?php foreach($a as $ad): ?><option value="<?= $ad['id_pengadopsi'] ?>" <?= $data['id_pengadopsi']==$ad['id_pengadopsi']?'selected':'' ?>><?= $ad['nama_lengkap'] ?></option><?php endforeach; ?>
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
                <label>Status Persetujuan</label>
                <select name="status_adopsi" class="form-control">
                    <option value="Proses" <?= $data['status_adopsi']=='Proses'?'selected':'' ?>>Dalam Proses</option>
                    <option value="Disetujui" <?= $data['status_adopsi']=='Disetujui'?'selected':'' ?>>Disetujui (Final)</option>
                    <option value="Ditolak" <?= $data['status_adopsi']=='Ditolak'?'selected':'' ?>>Ditolak</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nomor / Link E-Contract</label>
                <input type="text" name="e_contract" value="<?= htmlspecialchars($data['e_contract']) ?>" class="form-control">
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update Transaksi</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>