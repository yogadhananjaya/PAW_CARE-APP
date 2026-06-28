<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Rekam Medis Hewan</h2>
        <a href="index.php?page=riwayat_kesehatan" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 650px;">
        <form action="index.php?page=riwayat_kesehatan_edit&id=<?= $data['id_riwayat'] ?>" method="POST">
            <!-- Pilih Hewan -->
            <div class="form-group">
                <label>Pilih Hewan</label>
                <select name="id_hewan" class="form-control" required>
                    <?php foreach($h as $hw): ?>
                        <option value="<?= $hw['id_hewan'] ?>" <?= $data['id_hewan'] == $hw['id_hewan'] ? 'selected' : '' ?>><?= $hw['nama_hewan'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tipe Rekam Medis -->
            <div class="form-group">
                <label>Tipe Rekam Medis</label>
                <select name="tipe" class="form-control" required id="tipe_select" onchange="toggleVaksin()">
                    <option value="Perawatan" <?= $data['tipe'] == 'Perawatan' ? 'selected' : '' ?>>Perawatan (Pemeriksaan Umum)</option>
                    <option value="Vaksinasi" <?= $data['tipe'] == 'Vaksinasi' ? 'selected' : '' ?>>Vaksinasi</option>
                </select>
            </div>

            <!-- Vaksin (muncul jika tipe = Vaksinasi) -->
            <div class="form-group" id="vaksin_section" style="<?= $data['tipe'] == 'Vaksinasi' ? '' : 'display:none;' ?>">
                <label>Pilih Vaksin</label>
                <select name="id_vaksin" class="form-control">
                    <option value="">-- Tidak Ada / Pilih Vaksin --</option>
                    <?php foreach($v as $vk): ?>
                        <option value="<?= $vk['id_vaksin'] ?>" <?= $data['id_vaksin'] == $vk['id_vaksin'] ? 'selected' : '' ?>><?= $vk['nama_vaksin'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Perawat Penanggung Jawab -->
            <div class="form-group">
                <label>Perawat Penanggung Jawab</label>
                <select name="id_pengguna" class="form-control" required>
                    <?php foreach($p as $pw): ?>
                        <option value="<?= $pw['id_pengguna'] ?>" <?= $data['id_pengguna'] == $pw['id_pengguna'] ? 'selected' : '' ?>><?= $pw['nama_pengguna'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tanggal -->
            <div class="form-group">
                <label>Tanggal Pemeriksaan</label>
                <input type="date" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>" class="form-control" required>
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label>Deskripsi / Catatan Medis</label>
                <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-warning" style="margin-top:15px;">Update</button>
        </form>
    </div>
</div>

<script>
function toggleVaksin() {
    var tipe = document.getElementById('tipe_select').value;
    document.getElementById('vaksin_section').style.display = (tipe === 'Vaksinasi') ? 'block' : 'none';
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>