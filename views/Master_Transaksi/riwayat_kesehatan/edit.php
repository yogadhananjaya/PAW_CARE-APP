<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Rekam Medis Hewan</h2>
        <a href="index.php?page=riwayat_kesehatan" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=riwayat_kesehatan_edit&id=<?= $data['id_riwayat'] ?>" method="POST">
            <div class="form-group">
                <label>Pilih Hewan</label>
                <select name="id_hewan" class="form-control" required>
                    <?php foreach($h as $hw): ?><option value="<?= $hw['id_hewan'] ?>" <?= $data['id_hewan']==$hw['id_hewan']?'selected':'' ?>><?= $hw['nama_hewan'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Pemberian Vaksin (Opsional)</label>
                <select name="id_vaksin" class="form-control">
                    <option value="">-- Tidak Ada Vaksin --</option>
                    <?php foreach($v as $vk): ?><option value="<?= $vk['id_vaksin'] ?>" <?= $data['id_vaksin']==$vk['id_vaksin']?'selected':'' ?>><?= $vk['nama_vaksin'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Perawat Penanggung Jawab</label>
                <select name="id_pengguna" class="form-control" required>
                    <?php foreach($p as $pw): ?><option value="<?= $pw['id_pengguna'] ?>" <?= $data['id_pengguna']==$pw['id_pengguna']?'selected':'' ?>><?= $pw['username'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Periksa</label>
                <input type="date" name="tanggal_periksa" value="<?= $data['tanggal_periksa'] ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Diagnosa Kesehatan</label>
                <textarea name="diagnosa" class="form-control" rows="3" required><?= htmlspecialchars($data['diagnosa']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Tindakan yang Diberikan</label>
                <textarea name="tindakan" class="form-control" rows="3" required><?= htmlspecialchars($data['tindakan']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>