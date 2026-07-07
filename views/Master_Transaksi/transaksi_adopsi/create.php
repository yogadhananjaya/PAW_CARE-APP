<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Buat Transaksi Adopsi</h2>
        <a href="index.php?page=transaksi_adopsi" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 650px;">
        <?php if (!empty($error_duplikat)): ?>
            <div
                style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_duplikat) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=transaksi_adopsi_create" method="POST">
            <!-- Pengadopsi (Hanya yang Terverifikasi) -->
            <div class="form-group">
                <label>Pengadopsi <small style="color:#999;">(Hanya yang Terverifikasi KTP)</small></label>
                <select name="id_pengadopsi" class="form-control" required>
                    <option value="">-- Pilih Pengadopsi --</option>
                    <?php foreach ($a as $ad): ?>
                        <option value="<?= $ad['id_pengadopsi'] ?>"><?= $ad['nama'] ?></option><?php endforeach; ?>
                </select>
            </div>

            <!-- Hewan yang Diadopsi -->
            <div class="form-group">
                <label>Hewan yang Diadopsi</label>
                <select name="id_hewan" class="form-control" required>
                    <option value="">-- Pilih Hewan --</option>
                    <?php foreach ($h as $hw): ?>
                        <option value="<?= $hw['id_hewan'] ?>"><?= $hw['nama_hewan'] ?></option><?php endforeach; ?>
                </select>
            </div>

            <!-- Penanggung Jawab Admin -->
            <div class="form-group">
                <label>Admin / Koordinator Penanggung Jawab <small style="color:#999;">(opsional)</small></label>
                <select name="id_pengguna" class="form-control">
                    <option value="">-- Belum Ditugaskan --</option>
                    <?php foreach ($pg as $p): ?>
                        <option value="<?= $p['id_pengguna'] ?>"><?= $p['nama_pengguna'] ?></option><?php endforeach; ?>
                </select>
            </div>

            <!-- Tanggal Adopsi -->
            <div class="form-group">
                <label>Tanggal Adopsi / Penandatanganan Kontrak</label>
                <input type="date" name="tanggal_adopsi" class="form-control" required>
            </div>

            <!-- Status Kontrak -->
            <div class="form-group">
                <label>Status Kontrak</label>
                <select name="status_kontrak" class="form-control">
                    <option value="Draft">Draft (Belum Ditandatangani)</option>
                    <option value="Ditandatangani">Ditandatangani</option>
                    <option value="Aktif">Aktif (Final)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan Transaksi</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>