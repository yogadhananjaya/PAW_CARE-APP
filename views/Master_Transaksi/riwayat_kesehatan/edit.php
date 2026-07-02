<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Rekam Medis Hewan</h2>
        <a href="index.php?page=riwayat_kesehatan" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 650px;">
        <?php if (!empty($error_duplikat)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_duplikat) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=riwayat_kesehatan_edit&id=<?= $data['id_riwayat'] ?>" method="POST">
            <!-- Pilih Hewan -->
            <div class="form-group">
                <label>Pilih Hewan</label>
                <select name="id_hewan" id="hewan_select" class="form-control" required onchange="filterVaksin()">
                    <?php foreach($h as $hw): ?>
                        <option value="<?= $hw['id_hewan'] ?>" data-jenis="<?= $hw['id_jenis'] ?>" <?= $data['id_hewan'] == $hw['id_hewan'] ? 'selected' : '' ?>><?= $hw['nama_hewan'] ?></option>
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
                <select name="id_vaksin" id="vaksin_select" class="form-control">
                    <option value="">-- Pilih Vaksin --</option>
                    <?php foreach($v as $vk): ?>
                        <?php $v_disabled = ($vk['status'] !== 'Tersedia' || ($vk['stok'] ?? 0) == 0) && $data['id_vaksin'] != $vk['id_vaksin']; ?>
                        <option value="<?= $vk['id_vaksin'] ?>" data-jenis-list="<?= $vk['id_jenis_list'] ?? '' ?>" <?= $data['id_vaksin'] == $vk['id_vaksin'] ? 'selected' : '' ?> <?= $v_disabled ? 'disabled style="color:#94a3b8;"' : '' ?>>
                            <?= htmlspecialchars($vk['nama_vaksin']) ?>
                            <?php if ($vk['status'] !== 'Tersedia'): ?> (<?= $vk['status'] ?>)
                            <?php elseif (($vk['stok'] ?? 0) == 0): ?> (Habis - Stok 0)
                            <?php elseif (($vk['stok'] ?? 0) < 5): ?> ⚠️ (Segera Restock)
                            <?php endif; ?>
                        </option>
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
function filterVaksin() {
    var hewan = document.getElementById('hewan_select');
    var jenis = hewan.options[hewan.selectedIndex].getAttribute('data-jenis');
    var opts = document.getElementById('vaksin_select').options;
    for (var i = 1; i < opts.length; i++) {
        var vList = opts[i].getAttribute('data-jenis-list');
        if (!vList) { opts[i].style.display = ''; continue; }
        var arr = vList.split(',');
        opts[i].style.display = arr.includes(jenis) ? '' : 'none';
    }
    opts[0].selected = true;
}
filterVaksin();
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>