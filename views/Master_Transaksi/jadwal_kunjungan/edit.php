<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Jadwal Kunjungan</h2>
        <a href="index.php?page=jadwal_kunjungan" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 650px;">
        <?php if (isset($_GET['error']) && $_GET['error'] === 'duplicate'): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ Adopter ini sudah memiliki jadwal aktif untuk hewan yang sama. Selesaikan atau batalkan jadwal sebelumnya terlebih dahulu.
            </div>
        <?php endif; ?>
        <form action="index.php?page=jadwal_kunjungan_edit&id=<?= $data['id_jadwal'] ?>" method="POST">
            <div class="form-group">
                <label>Pengadopsi</label>
                <select name="id_pengadopsi" class="form-control" required>
                    <option value="">-- Pilih Pengadopsi --</option>
                    <?php foreach($a as $ad): ?><option value="<?= $ad['id_pengadopsi'] ?>" <?= $data['id_pengadopsi']==$ad['id_pengadopsi']?'selected':'' ?>><?= $ad['nama'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Hewan yang Dikunjungi</label>
                <select name="id_hewan" class="form-control" required>
                    <?php foreach($h as $hw): ?><option value="<?= $hw['id_hewan'] ?>" <?= $data['id_hewan']==$hw['id_hewan']?'selected':'' ?>><?= $hw['nama_hewan'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Petugas Pendamping <small style="color:#999;">(opsional)</small></label>
                <select name="id_pengguna" class="form-control">
                    <option value="">-- Belum Ditugaskan --</option>
                    <?php foreach($p as $pg): ?><option value="<?= $pg['id_pengguna'] ?>" <?= $data['id_pengguna']==$pg['id_pengguna']?'selected':'' ?>><?= $pg['nama_pengguna'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Metode Kunjungan</label>
                <select name="metode" class="form-control" required>
                    <option value="Kunjungan ke Shelter" <?= $data['metode']=='Kunjungan ke Shelter'?'selected':'' ?>>Kunjungan ke Shelter</option>
                    <option value="Jemput ke Rumah" <?= $data['metode']=='Jemput ke Rumah'?'selected':'' ?>>Jemput ke Rumah Adopter</option>
                </select>
            </div>
            <div class="form-group">
                <label>Alamat Tujuan</label>
                <textarea name="alamat_tujuan" class="form-control" rows="2" placeholder="Alamat lengkap..."><?= htmlspecialchars($data['alamat_tujuan'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Tanggal & Jam Jadwal</label>
                <input type="datetime-local" name="tanggal_jadwal" value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_jadwal'])) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status_jadwal" class="form-control">
                    <option value="Menunggu" <?= $data['status_jadwal']=='Menunggu'?'selected':'' ?>>Menunggu Konfirmasi</option>
                    <option value="Dikonfirmasi" <?= $data['status_jadwal']=='Dikonfirmasi'?'selected':'' ?>>Dikonfirmasi</option>
                    <option value="Selesai" <?= $data['status_jadwal']=='Selesai'?'selected':'' ?>>Selesai / Hadir</option>
                    <option value="Batal" <?= $data['status_jadwal']=='Batal'?'selected':'' ?>>Dibatalkan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>
