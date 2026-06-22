<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Penempatan Kandang</h2>
        <a href="index.php?page=penempatan_kandang" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=penempatan_kandang_create" method="POST">
            <div class="form-group">
                <label>Pilih Hewan</label>
                <select name="id_hewan" class="form-control" required>
                    <?php foreach($h as $hw): ?><option value="<?= $hw['id_hewan'] ?>"><?= $hw['nama_hewan'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Pilih Kandang</label>
                <select name="id_kandang" class="form-control" required>
                    <?php foreach($k as $kd): ?><option value="<?= $kd['id_kandang'] ?>"><?= htmlspecialchars($kd['kode_kandang']) ?> - <?= htmlspecialchars($kd['nama_kandang']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tanggal Keluar (Kosongkan jika masih menempati)</label>
                <input type="date" name="tanggal_keluar" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>