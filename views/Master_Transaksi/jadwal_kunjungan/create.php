<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Jadwal Kunjungan</h2>
        <a href="index.php?page=jadwal_kunjungan" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=jadwal_kunjungan_create" method="POST">
            <div class="form-group">
                <label>Pengadopsi</label>
                <select name="id_pengadopsi" class="form-control" required>
                    <?php foreach($a as $ad): ?><option value="<?= $ad['id_pengadopsi'] ?>"><?= $ad['nama_lengkap'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Hewan yang Dikunjungi</label>
                <select name="id_hewan" class="form-control" required>
                    <?php foreach($h as $hw): ?><option value="<?= $hw['id_hewan'] ?>"><?= $hw['nama_hewan'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal & Jam</label>
                <input type="datetime-local" name="tanggal_kunjungan" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Menunggu">Menunggu Konfirmasi</option>
                    <option value="Dikonfirmasi">Dikonfirmasi</option>
                    <option value="Selesai">Selesai / Hadir</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>