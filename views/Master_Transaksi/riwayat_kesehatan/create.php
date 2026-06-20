<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Rekam Medis Hewan</h2>
        <a href="index.php?page=riwayat_kesehatan" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=riwayat_kesehatan_create" method="POST">
            <div class="form-group">
                <label>Pilih Hewan</label>
                <select name="id_hewan" class="form-control" required>
                    <?php foreach($h as $hw): ?><option value="<?= $hw['id_hewan'] ?>"><?= $hw['nama_hewan'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Pemberian Vaksin (Opsional)</label>
                <select name="id_vaksin" class="form-control">
                    <option value="">-- Tidak Ada Vaksin --</option>
                    <?php foreach($v as $vk): ?><option value="<?= $vk['id_vaksin'] ?>"><?= $vk['nama_vaksin'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Perawat Penanggung Jawab</label>
                <select name="id_pengguna" class="form-control" required>
                    <?php foreach($p as $pw): ?><option value="<?= $pw['id_pengguna'] ?>"><?= $pw['username'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Periksa</label>
                <input type="date" name="tanggal_periksa" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Diagnosa Kesehatan</label>
                <textarea name="diagnosa" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Tindakan yang Diberikan</label>
                <textarea name="tindakan" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>