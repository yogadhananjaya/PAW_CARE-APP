<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Buat Transaksi Adopsi</h2>
        <a href="index.php?page=transaksi_adopsi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 600px;">
        <form action="index.php?page=transaksi_adopsi_create" method="POST">
            <div class="form-group">
                <label>Pengadopsi (Hanya yg Terverifikasi)</label>
                <select name="id_pengadopsi" class="form-control" required>
                    <?php foreach($a as $ad): ?><option value="<?= $ad['id_pengadopsi'] ?>"><?= $ad['nama_lengkap'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Hewan yang Diadopsi</label>
                <select name="id_hewan" class="form-control" required>
                    <?php foreach($h as $hw): ?><option value="<?= $hw['id_hewan'] ?>"><?= $hw['nama_hewan'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Adopsi / TTD Kontrak</label>
                <input type="date" name="tanggal_adopsi" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Status Persetujuan</label>
                <select name="status_adopsi" class="form-control">
                    <option value="Proses">Dalam Proses</option>
                    <option value="Disetujui">Disetujui (Final)</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nomor / Link E-Contract (Opsional)</label>
                <input type="text" name="e_contract" class="form-control" placeholder="Contoh: DOC-2026-001">
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan Transaksi</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>